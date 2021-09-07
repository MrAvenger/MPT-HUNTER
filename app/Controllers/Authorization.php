<?php
namespace App\Controllers; //Пространство имён (контроллер)
use App\Models\UserModel; //Подключение модели по работе с таблицей "users"
use App\Models\PersonalDataModel;//Подключение модели по работе с таблицей "personal_data"
use App\Controllers\Email;//Подключение контроллера по работе с почтой
use App\Models\PasswordRessetModel;//Подключение модели по работе с таблицей "password_ressets"
use App\Models\OrganizationModel;//Подключение модели по работе с таблицей "organizations"
use App\Models\StudentOrganizationModel;
use App\Models\GroupsSpecializationsModel;

class Authorization extends BaseController
{
	protected $google_client;//переменная google клиента.
	protected $user_model; //переменная модели пользователя.
	protected $personal_data_model;//переменная модели персональных данных пользователей.
	protected $email;//переменная контроллера по работе с почтой.
	protected $password_resset_model; //переменная модели сброса паролей.
	protected $organization_model;//переменная модели организаций.
	protected $student_organization_model;
	protected $groups_specializations_model;
	//Создаём функцию конструктора, в которой подключим все необходимые библиотеки, хелперы.
	public function __construct()
    {
		$this->google_client=$this->google_auth_config();//Формируем google клиент
        $this->user_model = new UserModel();//Создаём экземпляр модели пользователя
		$this->personal_data_model=new PersonalDataModel();//Создаём экземпляр модели персональных данных пользователя
		$this->email=new Email();//Создаём экземпляр контроллера по работе с письмами
		$this->password_resset_model= new PasswordRessetModel(); //Экземпляр модели
		$this ->organization_model=new OrganizationModel(); //Создаём экземпляр модели организаций
		$this->student_organization_model=new StudentOrganizationModel();
		$this->groups_specializations_model= new GroupsSpecializationsModel();
        helper('form', 'url','array'); // Подгрузка хелперов (чтобы работать с валидацией форм, ссылками, массивами)
    }
	//Стандартная функция контроллера
	public function index()
	{
		//Если пользователь авторизован (проверяем по сессии)
		if(session('isLoggedIn')){
			return redirect()->to('profile'); // Перенаправление на страницу "Профиль"
		}
		//Иначе
		else{
			return redirect()->to('login'); // Перенаправление на страницу "Авторизация"
		}

	}
	//Функция для авторизации
	public function login(){
		//Если со страницы быле переданы данные методом "post" (вход по логину и паролю)
		if($this->request->getMethod()=='post'){
			$user_info=$this->user_model->where('email',$this->request->getVar('email'))->first(); //Получаем пользователя
			//Если пользователь есть
			if($user_info){
				$personal_data=$this->personal_data_model->where('user_id',$user_info['id'])->first();
				//Если аккаунт активирован
				if($user_info['active']){
					$request_pass=$this->request->getVar('password');//Получаем пароль пришедший с формы
					$db_hash_pass=$user_info['password'];//Получаем хеш пароля из бд
					//Сравним хеши паролей					
					if(password_verify($request_pass,$db_hash_pass)){
						unset($personal_data['user_id']);	//Убираем по ключам не нужные нам значения из массива		
						$all_user_data=array_merge($user_info,$personal_data);//Объединяем данные пользователя из таблицы "users" и "personal_data"
						//Если есть связанные данные с пользователем из таблицы "organizations" и пользователь является работодателем
						if($this->organization_model->where('user_id',$user_info['id'])->first()&&$user_info['role']=='Работодатель'){
							$org_data=$this->organization_model->where('user_id',$user_info['id'])->first();//Получем данные о организации, к которой привязан пользователь
							$org_data['org_id']=$org_data['id'];
							unset($org_data['id'],$org_data['user_id']);//Убираем по ключам не нужные нам значения из массива
							$all_user_data=array_merge($all_user_data,$org_data);//Добавляем к ранее известным данным массива данные о организации
						}
						$this->set_user_session($all_user_data);//Устанавливаем данные пользователя в сессию
						return redirect()->to('/');//Перенаправляем на базовый метод текущего контроллера
					}
					//Иначе
					else{
						session()->setFlashdata('error_login','Неверный пароль!');//Устанавливаем в сессию сообщение
					}
				}
				//Иначе
				else{
					$data_email=array_merge($personal_data,$user_info); //Объединение данных, для передачи в метод контроллера "Email"
					$this->email->send_activate_code($data_email); //Передача данных в метод контроллера для отправки письма
					session()->setFlashdata('error_login','Не пройдена верификация! Отправлено письмо с новым кодом для прохождения верификации!');//Устанавливаем в сессию сообщение
				}
			}
			//Если пользователя нет
			else{
				session()->setFlashdata('error_login','Аккаунта с такой почтой нет!'); //Устанавливаем в сессию сообщение
			}
		}
		//Если вход через почту google (передаётся код методом "get")
		else if($this->request->getVar('code')){
			$token = $this->google_client->fetchAccessTokenWithAuthCode($_GET["code"]); //Получаем токен по пришедшему коду
			//Не произошла ошибка с получением токена
			if(!isset($token["error"]))
			{
				$this->google_client->setAccessToken($token['access_token']);//Устанавливаем токен доступа
				$google_service = new \Google_Service_Oauth2($this->google_client);//Подключаем сервис
				$data = $google_service->userinfo->get();//Берём всю информацию из аккаунта google
				$user_info=$this->user_model->where('email',$data['email'])->first();//Получаем пользователя
				//Если есть пользователь
				if($user_info){
					$personal_data=$this->personal_data_model->where('user_id',$user_info['id'])->first();//Получаем персональные данные
					//Если учётная запись активирована
					if($user_info['active']){
						unset($personal_data['user_id']);//Убираем по ключам не нужные нам значения из массива	
						$all_user_data=array_merge($user_info,$personal_data);//Объединяем данные пользователя из таблицы "users" и "personal_data"
						//Если есть связанные данные с пользователем из таблицы "organizations" и пользователь является работодателем
						if($this->organization_model->where('user_id',$user_info['id'])->first()&&$user_info['role']=='Работодатель'){
							$org_data=$this->organization_model->where('user_id',$user_info['id'])->first();//Получем данные о организации, к которой привязан пользователь
							$org_data['org_id']=$org_data['id'];
							unset($org_data['id'],$org_data['user_id']);//Убираем по ключам не нужные нам значения из массива
							$all_user_data=array_merge($all_user_data,$org_data);//Добавляем к ранее известным данным массива данные о организации
						}
						$this->set_user_session($all_user_data);//Устанавливаем данные пользователя в сессию
						return redirect()->to('/');//Перенаправляем на базовый метод текущего контроллера
					}
					//Иначе
					else{
						$data_email=array_merge($personal_data,$user_info);//Данные для письма
						$this->email->send_activate_code($data_email);//Отправляем повторно код активации
						session()->setFlashdata('error_login','Не пройдена верификация! Отправлено письмо с новым кодом для прохождения верификации!');//Устанавливаем в сессию сообщение
					}

				}
				//Иначе
				else{
					session()->setFlashdata('error_login','Аккаунта с такой почтой нет!');//Устанавливаем в сессию сообщение
					return redirect()->to('register');
				}
			}
			//Иначе
			else{
				session()->setFlashdata('error_login','Произошла ошибка при авторизации через google!');//Устанавливаем в сессию сообщение
			}
		}
		$data=[];
		$login_button = '<a href="'.$this->google_client->createAuthUrl().'"><img src="'.base_url().'/assets/img/design/google.png" class="btn img-fluid" style="max-width: 200px;max-height: 100px;" /></a>';//Копка для входа по google почте
		$data['login_button'] = $login_button;//Кнопку записываем в $data
		return view('login_view', $data); //Возвращаем станицу для авторизации
	}
	//Функция для регистрации
	public function register(){
		//Если не была нажата кнопка "Зарегистрироваться".
		if($this->request->getMethod()!='post'){
			return view('register_view');
			
		}
		//Иначе
		else{
			//Правила для валидации
			$signup = [
				'first_name' => [
					'rules'  => 'required|min_length[1]|max_length[50]',
					'errors' => [
						'required' => 'Имя - обязательное поле!',
						'min_length' => 'Имя должно содержать не менее 1 символов!',
						'max_length' => 'Имя должно содержать не более 50 символов!'
					]
				],
				'last_name' => [
					'rules'  => 'required|min_length[1]|max_length[50]',
					'errors' => [
						'required' => 'Фамилия - обязательное поле!',
						'min_length' => 'Фамилия должна содержать не менее 1 символов!',
						'max_length' => 'Фамилия должна содержать не более 50 символов!'
					]
				],
				'middle_name' => [
					'rules'  => 'max_length[50]',
					'errors' => [
						'max_length' => 'Отчество должно содержать не более 50 символов!'
					]
				],
				'email'    => [
					'rules'  => 'required|valid_email|min_length[3]|max_length[200]|is_unique[users.email]|email_mpt_Validation[email]',
					'errors' => [
						'valid_email' => 'Не валидный Email!',
						'required' => 'Email - обязательное поле!',
						'min_length' => 'Email должен содержать не менее 3 символов!',
						'max_length' => 'Email должен содержать не более 200 символов!',
						'is_unique' => 'Аккаунт с указанной почтой уже существует!',
						'email_mpt_Validation' => 'Почта не привязана к домену мпт!'
					]
				],
				'password' => [
					'rules'  => 'required|min_length[8]|max_length[20]|pass_Validation[password]',
					'errors' => [
						'required' => 'Пароль - обязательное поле!',
						'min_length' => 'Пароль должен содержать не менее 8 символов!',
						'max_length' => 'Пароль должен содержать не более 20 символов!',
						'pass_Validation' => 'Пароль должен содержать латинские символы верхнего и нижнего регистра, цифры и специальные знаки!'
					]
				],
				'specialization' => [
					'rules'  => 'required',
					'errors' => [
						'required' => 'Специальность - обязательное поле!',
						
					]
				],
				'groups' => [
					'rules'  => 'required',
					'errors' => [
						'required' => 'Группа - обязательное поле!',
						
					]
				],
			];
			//Если валидация не прошла
			if (!$this->validate($signup)){
				$data['validation'] = $this->validator;
				return view('register_view',$data);
			}
			//Иначе
			else {
				// Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
				$personal_data=[
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => $this->request->getVar('last_name'),
					'middle_name' => $this->request->getVar('middle_name'),
					'specialization_id' => $this->request->getVar('specialization'),
					'group_id' => $this->request->getVar('groups')
				];
				$user_data=[
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),
				];
				if(!$this->groups_specializations_model->where(['specialization_id'=>$this->request->getVar('specialization'),'group_id'=>$this->request->getVar('groups')])->first()){
					return json_encode(['validation'=>'<ul><li>Не пытайтесь установить неверные значения!</li></ul>']);
				}
				//Если пользователь добавлен
				if($this->user_model->insert($user_data)){
					$inserted_user_data=$this->user_model->where('email',$user_data['email'])->first();//Получаем добавленного пользователя
					$personal_data['user_id']=$inserted_user_data['id'];//Устанавливаем занчение id пользователя
					$this->personal_data_model->insert($personal_data);//Записываем персональные данные
					$data_email=array_merge($personal_data,$user_data);//Данные для отправки письма
					//Если письмо отправлено
					if($this->email->send_activate_code($data_email)&&$this->email->send_welcome($data_email,'Регистрация на MPT-HUNTER')){
						session()->setFlashdata('success_reg', 'Регистрация прошла успешно! На почту отправлено письмо с инструкцией для прохождения верификации.');//Устанавливаем в сессию сообщение
						return redirect()->to('login');//Перенаправление на авторизацию
					}
					//Иначе
					else{
						$this->user_model->where('id',$personal_data['user_id'])->delete();//Удаление записи о пользователе (раз уж письмо не отправлено, то активировать аккаунт не выйдет)
						session()->setFlashdata('error_reg', 'Не удалось отправить письмо верификации! Регистрация не пройдена!');//Устанавливаем в сессию сообщение
						return view('register_view');//Возвращаем страницу регистрации
					}
				}
				else{
					session()->setFlashdata('error_reg', 'Ошибка создания учётной записи!');//Устанавливаем в сессию сообщение
					return view('register_view');//Возвращаем страницу регистрации
				}
			}
			
		}
	}
	//Конфигурация для google почты
	public function google_auth_config(){
		include_once  APPPATH. "/Libraries/vendor/autoload.php";
		$google_client = new \Google_Client();
		$google_client->setClientId('320094496366-6vh6svdfbga6l9sungmfvk2t9jrbts70.apps.googleusercontent.com'); //Клиент id
		$google_client->setClientSecret('28dvXsmEYs75YRf2NZywdWkw'); //Секретный клиент.ключ
		$google_client->setRedirectUri("".base_url()."/login"); //Адрес страницы авторизации (не менять)
		$google_client->addScope('email');
		$google_client->addScope('profile');
		return $google_client;
	}
	//Функция установки данных в сессию
	public function set_user_session($user){
		$user['isLoggedIn']=true;
		if(!empty($user['date_birth'])){
			$user['date_birth']=date('d.m.Y',strtotime($user['date_birth']));
		}
		unset($user['password']);
		unset($user['verification_code']);
		session()->set($user);
	}
	//Функция для изменения пароля
	public function change_password($code){
		$pass_data=$this->password_resset_model->where(['resset_code'=>$code])->first();//Получаем данные из таблицы "password_ressets"
		$data=['code'=>$code];//Записываем в переменную код
		//Если запись с кодом для сброса есть
		if($pass_data){
			//Если не метод "post"
			if($this->request->getMethod()!='post'){
				return view('password_resset_view',$data);//Возвращаем страницу для сброса пароля
			}
			//Иначе
			else{
				//Прописываем правила валидации пришедших данных
				$rules = [
					'password' => [
						'rules'  => 'required|min_length[8]|max_length[20]|pass_Validation[password]',
						'errors' => [
							'required' => 'Пароль - обязательное поле!',
							'min_length' => 'Пароль должен содержать не менее 8 символов!',
							'max_length' => 'Пароль должен содержать не более 20 символов!',
							'pass_Validation' => 'Пароль должен содержать латинские символы верхнего и нижнего регистра, цифры и специальные знаки!'
						]
					],
					'confirm_password' => [
						'rules'  => 'matches[password]',
						'errors' => [
							'matches' => 'Неправильно введён повторный пароль!'
						]
					],
				];
				//Если валидация не прошла, получаем массив ошибок
				if (!$this->validate($rules)){
					$data['validation'] = $this->validator;//Ошибки валидации
					return view('password_resset_view',$data);//Возвращаем вид
				}
				//Иначе
				else{
					$this->user_model->update($pass_data['user_id'],['password'=>$this->request->getVar('password')]); //Обновляем пароль
					session()->setFlashdata('success_resset_pswd','Успешное изменение пароля!');//Устанавливаем в сессию сообщение
					$this->password_resset_model->where('user_id',$pass_data['user_id'])->delete();//Удаляем запись для сброса пароля
					return redirect()->to(base_url().'/login');//Перенаправление на страницу авторизации
				}
			}
		}
		//Иначе
		else{
			session()->setFlashdata('error_resset_pswd','Неверный код восстановления пароля!');//Устанавливаем в сессию сообщение
			return redirect()->to(base_url().'/login');//Перенаправление на страницу авторизации
		}
	}

	public function update_rights()
	{
		if($this->request->getMethod()=='post'){
			$user=$this->user_model->where('id',session('id'))->first();
			if($user){
				if(session('role')=='Студент'){
					$in_org=[
                        'user_id'=>session('id'),
                        'status'=>'Закреплён'
                    ];
                    if($this->student_organization_model->where($in_org)->first()){
                        $user['in_org']=true;
						
                    }
                    else{
                        $user['in_org']=false;
						
                    }
				}
			}
			else{
				$user=[];
			}
			return json_encode($user);
		}
	}

	//Функция для выхода из приложжения
    public function logout()
    {
        session()->destroy();//Уничтожаем сессию
        return redirect()->to('/login');//Перенаправляем на авторизацию
    }

}