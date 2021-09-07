<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\PersonalDataModel;
use App\Models\PasswordRessetModel;

class Email extends BaseController
{
    protected $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected $user_model;
    protected $personal_data_model;
    protected $password_resset_model;

    public function __construct()
    {
        $this->user_model=new UserModel();//Экземпляр модели
        $this->personal_data_model=new PersonalDataModel(); //Экземпляр модели
        $this->password_resset_model= new PasswordRessetModel(); //Экземпляр модели
        helper('form', 'url','array'); // Подгрузка хелперов
    }

    public function send_welcome($user_data,$title){
        $message="Дорогой пользователь, поздравляем вас с успешной регистрацией в нашей системе! Немного позже вам будет отправлено письмо для прохождения верификации, это обязательная процедура для новых пользователей. \r\nС уважением, команда администрации сайта.";
        //$to='kirovi400@gmail.com';
        $to=$user_data['email'];//Указание кому будет отправлено письмо
        $email=$this->set_email($message,$title,$to);
        return $email->send();
    }

    public function send_activate_code($user_data)
    {
        $activate_code=$this->generate_string($this->permitted_chars,20); //Генерация кода активации
        $update=['verification_code' =>$activate_code]; //Массив данных для обновления
        $this->user_model->update($user_data['user_id'],$update); //Обновление данных в таблице "users"
        $user_data['activate_code'] = $activate_code; //Указание кода активации в массиве
        $message = view('email/message_template/email_verification',$user_data); //Формирование письма
        //$message=$user_data['last_name']." ".$user_data['first_name']." ".$user_data['middle_name'].", пожалуйста, верифицируйте свою почту: {unwrap}".base_url()."/email/verification/".$activate_code."{/unwrap}";
        //$to='kirovi400@gmail.com';
        $to=$user_data['email'];//Указание кому будет отправлено письмо
        $subject='Активация аккаунта';//Указание темы письма
        $email=$this->set_email($message,$subject,$to);
        return $email->send();
        
    }

    public function send_resset(){
        if($this->request->getMethod()!='post'){
			return view('email/email_resset_view');
		}
        else{
            $resset_data=$this->user_model->where(['email'=>$this->request->getVar('email')])->first();
            if(!empty($resset_data)){
                $personal_data=$this->personal_data_model->where(['user_id'=>$resset_data['id']])->first();
                $all_data=array_merge($resset_data,$personal_data);
                if($resset_data['active']){
                    $resset_code=$this->generate_string($this->permitted_chars,20);
                    $this->password_resset_model->where(['user_id'=>$all_data['id']])->delete();
                    $this->password_resset_model->insert([
                        'user_id'=>$all_data['id'],
                        'resset_code' => $resset_code
                    ]);
                    $all_data['resset_code'] = $resset_code;
                    $message = view('email/message_template/email_recovery',$all_data);
                    //$message = 'Для восстановления пароля перейдите по url: {unwrap}'.base_url().'/password/change/'.$all_data['resset_code'].'{/unwrap}';
                    $to=$all_data['email'];
                    //$to=$all_data['email'];
                    $subject='Восстановление пароля | MPT HUNTER';
                    $email=$this->set_email($message,$subject,$to);
                    if($email->send()){
                        session()->setFlashdata('success_resset', 'На почту было отправлено письмо для восстановления пароля!');
                        return view('email/email_resset_view');
                    }
                    else{
                        session()->setFlashdata('error_resset', 'Ошибка отправки письма!');
                        return view('email/email_resset_view');
                    }

                }
                else{
                    $this->send_activate_code($all_data);
                    session()->setFlashdata('error_resset', 'Не пройдена верификация аккаунта! Вам отправлено повторно письмо для верификации аккаунта!');
                    return view('email/email_resset_view');
                }
            }
            else{
                session()->setFlashdata('error_resset', 'Аккаунта с указанной почтой не существует!');
                return view('email/email_resset_view');
            }
        }
    }

    public function verification($code){
        $data=[
            'verification_code' =>$code,
            'active' => 0
        ];
        $user_data=$this->user_model->where($data)->first();
        if(!empty($user_data)){
            session()->setFlashdata('success_verify','Верификация успешно пройдена! Теперь можете пройти авторизацию.');
            $this->user_model->update($user_data['id'],['active'=>true]);
            return redirect()->to(base_url().'/login');
        }
        else{
            $check_val_code=$this->user_model->where(['verification_code'=>$code])->first();
            if(!empty($check_val_code)){
                session()->setFlashdata('error_verify','Верификация была пройдена ранее!');
                return redirect()->to(base_url().'/login');
            }
            else{
                session()->setFlashdata('error_verify','Неверный код верификации!');
                return redirect()->to(base_url().'/login');
            }
        }
    }

    public function send_info($data){
        $message='Для вас была создана учётная запись в веб-сервисе MPT HUNTER<br>Ваши данные для входа:<br>Email: '.$data['email'].'<br>Пароль: '.$data['password'];
        $subject='Данные учётной записи | MPT HUNTER';
        $email=$this->set_email($message,$subject,$data['email']);
        $email->send();
        return true;
    }

    public function set_email($message,$subject,$to)
    {
        $email = \Config\Services::email();//Экземпляр сервиса Email
        $email->setTo($to);//Кому отправляем
        $email->setSubject($subject);//Устанавливаем тему
        $email->setMessage($message);//Устанавливаем сообщение
        return $email; //Вовзращаем экземпляр
    }

    function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        $time=time();
        $random_character=$random_character.''.$time;
        return $random_string;
    }
}