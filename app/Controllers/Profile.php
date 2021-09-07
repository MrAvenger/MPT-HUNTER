<?php

namespace App\Controllers;
use App\Models\PersonalDataModel;
use App\Models\OrganizationModel;
use App\Models\UserModel;
use App\Models\GroupsSpecializationsModel;

class Profile extends BaseController
{
    protected $personal_data_model;
    protected $organization_model;
    protected $user_model;
    protected $groups_specializations_model;
	//Создаём функцию конструктора, в которой подключим все необходимые библиотеки, хелперы.
	public function __construct()
    {
		$this->personal_data_model=new PersonalDataModel();
        $this->organization_model=new OrganizationModel();
        $this->user_model=new UserModel();
        $this->groups_specializations_model= new GroupsSpecializationsModel();
        helper('form', 'url','array','filesystem'); // Подгрузка хелперов
    }

    public function edit_profile()
    {
        $data=[];
        $user_pass=[];
        if($this->request->getMethod()=="post"){
            $rules = [
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
                'sex' => [
					'rules'  => 'max_length[10]|sex_Validation[sex]',
					'errors' => [
						'sex_Validation' => 'Похоже вы решили подменить значение пола на невозможное!'
					]
				],
                'date_birth' => [
					'rules'  => 'date_birth_Validation[date_birth]',
					'errors' => [
						'date_birth_Validation' => 'Вам должно быть минимум 17 лет!'
					]
				],
                
			];

            if(!empty($this->request->getVar('password'))){
                $rules['password'] = [
					'rules'  => 'min_length[8]|max_length[20]|pass_Validation[password]',
					'errors' => [
						'min_length' => 'Пароль должен содержать не менее 8 символов!',
						'max_length' => 'Пароль должен содержать не более 20 символов!',
						'pass_Validation' => 'Пароль должен содержать латинские символы верхнего и нижнего регистра, цифры и специальные знаки!'
					]
                ];
            }

            if(session('role')=='Студент'){
                $rules['specialization'] = [
					'rules'  => 'required',
					'errors' => [
                        'required' => 'Специальность - это обязательное поле!',
					]
                ];
                $rules['groups'] = [
					'rules'  => 'required',
					'errors' => [
						'required' => 'Группа - это обязательное поле!',
					]
                ];
            }

            if($my_image=$this->request->getFile('photo')){
                if(is_file($my_image)){
                    if($my_image->getSize()>0){
                        $rules['photo'] = [
                            'rules'=>'uploaded[photo]|mime_in[photo,image/jpg,image/jpeg,image/gif,image/png]|max_size[photo,4096]',
                            'errors' => [
                                'uploaded' => 'Не загружен!',
                                'mime_in' => 'Файл должен быть формата: jpg|jpeg|gif|png',
                                'max_size' => 'Слишком большой файл!'
                            ]
                        ];
                        
                    }

                    
                }

            }

            if (!$this->validate($rules)){
				$data['validation'] = $this->validator;
			}
            else{
                $date_birth=strtotime($this->request->getVar('date_birth'));
                $bd_date_birth=date('Y.m.d',$date_birth);
                $personal_data=[
                    'first_name' =>$this->request->getVar('first_name'),
                    'last_name' =>$this->request->getVar('last_name'),
                    'middle_name' =>$this->request->getVar('middle_name'),
                    'sex' =>$this->request->getVar('sex'),
                    'date_birth'  =>$bd_date_birth,
                    'number_phone' => $this->request->getVar('number_phone'),
                ];
                if(session('role')=='Студент'){
                    if(!$this->groups_specializations_model->where(['specialization_id'=>$this->request->getVar('specialization'),'group_id'=>$this->request->getVar('groups')])->first()){
                        return json_encode(['validation'=>'<ul><li>Не пытайтесь установить неверные значения!</li></ul>']);
                    }
                    $personal_data['specialization_id'] = $this->request->getVar('specialization');
                    $personal_data['group_id'] = $this->request->getVar('groups');
                }
                if(is_file($my_image)){
                    $file_name=$my_image->getRandomName();
                    $personal_data['photo'] = $file_name;
                    $my_image->move(WRITEPATH.'uploads/profile/'.session('id'),$file_name);
                }
                if($this->personal_data_model->update(session('id'),$personal_data)){
                    if(!empty($this->request->getVar('password'))){
                        $user_pass['password']=$this->request->getVar('password');
                        $this->user_model->update(session('id'),$user_pass);
                    }
                    session()->setFlashdata('success_edit_profile','Личные данные обновлены!');
                    $personal_data['date_birth']=date('d.m.Y',$date_birth);
                    session()->set($personal_data);
                    return redirect()->to(base_url().'/profile');
                }
                else{
                    session()->setFlashdata('error_edit_profile','Ошибка обновления данных профиля!');
                    return redirect()->to(base_url().'/profile/edit');
                }
            } 
        }
        echo view('default/header',['title'=>'Редактирование профиля']);
        echo view('profile/edit/body',$data);
        echo view('default/footer');
    }

    public function edit_org()
    {
        $data=[];
        if($this->request->getMethod()=="post"){
            $rules = [
				'org_adress' => [
					'rules'  => 'required|min_length[5]|max_length[255]',
					'errors' => [
						'required' => 'Адрес организации - обязательное поле!',
						'min_length' => 'Фамилия должна содержать не менее 5 символов!',
						'max_length' => 'Фамилия должна содержать не более 255 символов!'
					]
				],
                'post' => [
					'rules'  => 'required|min_length[5]|max_length[100]',
					'errors' => [
						'required' => 'Должность - обязательное поле!',
						'min_length' => 'Должность должна содержать не менее 5 символов!',
						'max_length' => 'Должность должна содержать не более 100 символов!'
					]
				],
				'org_description' => [
					'rules'  => 'max_length[500]',
					'errors' => [
						'max_length' => 'Описание должно содержать не более 500 символов!'
					]
				],
			];

            if(!$this->organization_model->where(['org_name'=>$this->request->getVar('org_name'),'user_id'=>session('id')])->first()){
                $rules['org_name'] = [
					'rules'  => 'required|min_length[5]|max_length[200]|is_unique[organizations.org_name]',
					'errors' => [
						'required' => 'Название организации - обязательное поле!',
						'min_length' => 'Поле "Название организации" должно содержать не менее 5 символов!',
						'max_length' => 'Поле "Название организации" должно содержать не более 200 символов!',
                        'is_unique' => 'Такая организация уже есть!'
					]
                ];
            }
            else{
                $rules['org_name'] = [
					'rules'  => 'required|min_length[5]|max_length[200]',
					'errors' => [
						'required' => 'Название организации - обязательное поле!',
						'min_length' => 'Поле "Название организации" должно содержать не менее 5 символов!',
						'max_length' => 'Поле "Название организации" должно содержать не более 200 символов!',
					]
                ];
            }

            if($my_image=$this->request->getFile('org_photo')){
                if(is_file($my_image)){
                    if($my_image->getSize()>0){
                        $rules['org_photo'] = [
                            'rules'=>'uploaded[org_photo]|mime_in[org_photo,image/jpg,image/jpeg,image/gif,image/png]|max_size[org_photo,4096]',
                            'errors' => [
                                'uploaded' => 'Не загружен!',
                                'mime_in' => 'Файл должен быть формата: jpg|jpeg|gif|png',
                                'max_size' => 'Слишком большой файл!'
                            ]
                        ];
                        
                    }

                    
                }

            }

            if (!$this->validate($rules)){
				$data['validation'] = $this->validator;
			}
            else{
                $org_data=[
                    'org_name' =>$this->request->getVar('org_name'),
                    'org_adress' =>$this->request->getVar('org_adress'),
                    'org_description' =>$this->request->getVar('org_description'),
                    'user_id' =>session('id'),
                ];
                if(is_file($my_image)){
                    $file_name=$my_image->getRandomName();
                    $org_data['org_photo'] = $file_name;
                    if(!empty(session('org_name'))){
                        $my_image->move(WRITEPATH.'uploads/organizations/'.session('id'),$file_name);
                    }
                    else{
                        $my_image->move(WRITEPATH.'uploads/organizations/'.session('id'),$file_name);
                    }
                    
                }
                if(!$org=$this->organization_model->where('user_id',session('id'))->first()){
                    if($this->organization_model->insert($org_data)){
                        $this->personal_data_model->update(session('id'),['post'=>$this->request->getVar('post')]);
                        session()->setFlashdata('success_edit_org','Данные по организации успешно обновлены!');
                        unset($org_data['user_id']);
                        session()->set($org_data);
                        return redirect()->to(site_url('profile'));
                    }
                    else{
                        session()->setFlashdata('success_edit_org','Ошибка обновления данных!');
                    }
                }
                else{
                    if($this->organization_model->update($org['id'],$org_data)){
                        $this->personal_data_model->update(session('id'),['post'=>$this->request->getVar('post')]);
                        session()->setFlashdata('success_edit_org','Данные по организации успешно обновлены!');
                        unset($org_data['user_id']);
                        session()->set($org_data);
                        return redirect()->to(site_url('profile'));
                    }
                    else{
                        session()->setFlashdata('success_edit_org','Ошибка обновления данных!');
                    }
                }

            } 
        }
        echo view('default/header',['title'=>'Редактирование данных организации']);
        echo view('organization/edit/body',$data);
        echo view('default/footer');
    }
}