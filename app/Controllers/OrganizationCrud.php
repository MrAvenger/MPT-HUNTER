<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\OrganizationModel;
use App\Models\PersonalDataModel;

class OrganizationCrud extends BaseController
{
    protected $personal_data_model;
    protected $user_model;
    protected $organization_model;

    public function __construct()
    {
        $this->personal_data_model=new PersonalDataModel();
        $this->user_model=new UserModel();//Экземпляр модели
        $this->organization_model=new OrganizationModel(); //Экземпляр модели
        helper('form', 'url','array'); // Подгрузка хелперов
    }

    public function get_all()
    {
        if($this->request->getMethod()=='post'){
            switch(session('role')){
                case 'Куратор':{
                    $orgs=[];
                    $result=[];
                    if($this->request->getVar('search')){
                        $search_val=$this->request->getVar('search');
                        $orgs=$this->organization_model->where("org_name LIKE '%".$search_val."%'")->findAll();
                    }
                    else{
                        $orgs=$this->organization_model->findAll();
                    }
                    if($orgs){
                        foreach ($orgs as $key => $item) {
                            if($item['org_photo']){
                                $item['org_photo']=base_url().'/writable/uploads/organizations/'.$item['user_id'].'/'.$item['org_photo'];
                            }
                            else{
                                $item['org_photo']=base_url().'/assets/img/design/org_photo.jpg';
                            }
                            array_push($result,$item);
                        }
                    }
                    return json_encode($result);
                }break;
                case 'Администратор':{
                    $data=[];
                    $all_data=$this->organization_model->findAll();
                    if($all_data){
                        foreach ($all_data as $key => $item) {
                            $photo_url='';
                            $emploer=$this->personal_data_model->where('user_id',$item['user_id'])->first();
                            $emploer_data=$emploer['last_name'].' '.$emploer['first_name'].' '.$emploer['middle_name'];
                            $post='';
                            if($item['org_photo']){
                                $photo_url=base_url().'/writable/uploads/organizations/'.$item['user_id'].'/'.$item['org_photo'];
                            }
                            else{
                                $photo_url=base_url().'/assets/img/design/org_photo.jpg';
                            }
                            if($emploer['post']){
                                $post=$emploer['post'];
                            }
                            else{
                                $post='Не указано';
                            }
                            $data[]=array(
                                $item['id'],
                                '<img style="max-width:200px;" src="'.$photo_url.'"></img>',
                                $item['org_name'],
                                $emploer_data,
                                $post,
                                $item['org_adress'],
                                '<button type="button" onclick="org_info('.$item['id'].')" class="btn btn-outline-info">Подробно</button>'.
                                '<button type="button" onclick="open_org_modal('.$item['id'].',1);" class="btn btn-outline-warning">Изменить</button>'.
                                '<button type="button" onclick="open_org_modal('.$item['id'].',2);" class="btn btn-outline-danger">Удалить</button>'
                            );
                        }
                    }
                    $result = array(
                        "draw" => $this->request->getVar('draw'),
                        "recordsTotal" => count($data),
                        "recordsFiltered" => count($data),
                        "data" => $data
                    );
                    return json_encode($result);
                }break;
            }
        }

    }

    public function get()
    {
        if($this->request->getMethod()=='post'&&(session('role')=='Администратор'||session('role')=='Студент')||session('role')=='Работодатель'||session('role')=='Куратор'){
            $data=[];
            $result=[];
            $data=$this->organization_model->where('id',$this->request->getVar('id'))->first();
            if($data){
                $result=$data;
                $user=$this->user_model->where('id',$data['user_id'])->first();
                $result['email']=$user['email'];
                if($user){
                    $pers_data=$this->personal_data_model->where('user_id',$data['user_id'])->first();
                    $result['first_name']=$pers_data['first_name'];
                    $result['last_name']=$pers_data['last_name'];
                    $result['middle_name']=$pers_data['middle_name'];
                    $result['post']=$pers_data['post'];
                    $result['number_phone']=$pers_data['number_phone'];
    
                }
                array_merge($result,$data);
            }
            return json_encode($result);   
        }        
    }

    public function get_employers()
    {
        if($this->request->getMethod()=='post'&&session('role')=='Администратор'){
            $all_data=[];
            $users=$this->user_model->where('role','Работодатель')->findAll();
            foreach ($users as $key => $user) {
                $pers=$this->personal_data_model->where('user_id',$user['id'])->first();
                $pers['email']=$user['email'];
                unset($pers['user_id']);
                $row=array_merge($user,$pers);
                array_push($all_data,$row);
            }
            return json_encode($all_data);
        }
    }

    public function add()
    {
        $validation =  \Config\Services::validation();
        $data=[];
        if($this->request->getMethod()=="post"&&session('role')=='Администратор'){
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
                'user' => [
					'rules'  => 'required|is_unique[organizations.user_id]',
					'errors' => [
                        'required'=>'Укажите представителя!',
						'max_length' => 'Описание должно содержать не более 255 символов!',
                        'is_unique'=>'Данный пользователь является представителем другой организации!'
					]
				],
			];

            if(!$this->organization_model->where(['org_name'=>$this->request->getVar('org_name'),'user_id'=>$this->request->getVar('user')])->first()){
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
                $errors = array_unique($validation->getErrors());
                $list_errors='<ul>';
                foreach ($errors as $key => $value) {
                    $list_errors=$list_errors.'<li>'.$value.'</li>';
                }
                $list_errors=$list_errors.'</ul>';
                $data['validation'] = $list_errors;
                //return json_encode(['validation'=>'ВАЛ']);
                return json_encode($data);
			}
            else{
                $org_data=[
                    'org_name' =>$this->request->getVar('org_name'),
                    'org_adress' =>$this->request->getVar('org_adress'),
                    'org_description' =>$this->request->getVar('org_description'),
                    'user_id' =>$this->request->getVar('user'),
                ];
                if(is_file($my_image)){
                    $file_name=$my_image->getRandomName();
                    $org_data['org_photo'] = $file_name;
                    if(!empty(session('org_name'))){
                        $my_image->move(WRITEPATH.'uploads/organizations/'.$this->request->getVar('user'),$file_name);
                    }
                    else{
                        $my_image->move(WRITEPATH.'uploads/organizations/'.$this->request->getVar('user'),$file_name);
                    }
                    
                }
                if(!$org=$this->organization_model->where('user_id',$this->request->getVar('user'))->first()){
                    if($this->organization_model->insert($org_data)){
                        $this->personal_data_model->update($this->request->getVar('user'),['post'=>$this->request->getVar('post')]);
                        return json_encode(true);
                    }
                    else{
                        return json_encode(['validation'=>'<ul><li>Ошибка обновления данных представителя!</li></ul>']);
                    }
                }
                else{
                    return json_encode(['validation'=>'<ul><li>Пользователь уже привязан к организации!</li></ul>']);
                }
            } 
        }
    }

    public function edit()
    {
        $validation = \Config\Services::validation();
        $data=[];
        if($this->request->getMethod()=="post"&&session('role')=='Администратор'){
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
                'user' => [
					'rules'  => 'required',
					'errors' => [
                        'required'=>'Укажите представителя!',
					]
				],
			];

            if(!$this->organization_model->where(['org_name'=>$this->request->getVar('org_name'),'user_id'=>$this->request->getVar('user')])->first()){
                $rules['org_name'] = [
					'rules'  => 'required|min_length[5]|max_length[200]',
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

            if(!$this->validate($rules)){
                $errors = array_unique($validation->getErrors());
                $list_errors='<ul>';
                foreach ($errors as $key => $value) {
                    $list_errors=$list_errors.'<li>'.$value.'</li>';
                }
                $list_errors=$list_errors.'</ul>';
                $data['validation'] = $list_errors;
                return json_encode($data);
			}
            else{
                if($this->organization_model->where('user_id',$this->request->getVar('user'))->first()){
                    $check=$this->organization_model->where('user_id',$this->request->getVar('user'))->first();
                    if($check['id']!=$this->request->getVar('id_org')){
                        return json_encode(['validation'=>'Данный пользователь привязан к другой организации!']);
                    }
                }
                $org_data=[
                    'org_name' =>$this->request->getVar('org_name'),
                    'org_adress' =>$this->request->getVar('org_adress'),
                    'org_description' =>$this->request->getVar('org_description'),
                    'user_id' =>$this->request->getVar('user'),
                ];
                if(is_file($my_image)){
                    $file_name=$my_image->getRandomName();
                    $org_data['org_photo'] = $file_name;
                    $my_image->move(WRITEPATH.'uploads/organizations/'.$this->request->getVar('user'),$file_name);
                    
                }
                if($this->organization_model->update($this->request->getVar('id_org'),$org_data)){
                    $this->personal_data_model->update($this->request->getVar('user'),['post'=>$this->request->getVar('post')]);
                    return json_encode(true);
                }
                else{
                    return json_encode(['validation'=>'Произошла ошибка!']);
                }
            } 
        }
    }

    public function delete()
    {
        if($this->request->getMethod()=='post'&&session('role')=='Администратор'){
            if($this->organization_model->where('id',$this->request->getVar('id'))->delete()){
                return json_encode(true);
            }
        }        
    }
}