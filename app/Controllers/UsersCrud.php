<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\PersonalDataModel;
use App\Models\GroupsSpecializationsModel;
use App\Models\SpecializationModel;
use App\Models\GroupModel;
use App\Controllers\Email;//Подключение контроллера по работе с почтой

class UsersCrud extends BaseController
{
    protected $group_model;
    protected $user_model;
    protected $personal_data_model;
    protected $groups_specializations_model;
    protected $specialization_model;
    protected $email;//переменная контроллера по работе с почтой.

    public function __construct()
    {
        $this->user_model=new UserModel();//Экземпляр модели
        $this->personal_data_model=new PersonalDataModel(); //Экземпляр модели
        $this->groups_specializations_model= new GroupsSpecializationsModel();
        $this->specialization_model=new SpecializationModel();
        $this->group_model=new GroupModel();
        $this->email=new Email();//Создаём экземпляр контроллера по работе с письмами
        helper('form', 'url','array','filesystem'); // Подгрузка хелперов
    }

    public function get_All()
    {
        if(session('role')=='Администратор'){
            $data_users=$this->user_model->findAll();
            $data=[];
            foreach ($data_users as $key => $user) {
                $personal_data=$this->personal_data_model->where('user_id',$user['id'])->first();
                $data[]=array(
                    $user['id'],
                    $personal_data['last_name'].' '.$personal_data['first_name'].' '.$personal_data['middle_name'],
                    $user['email'],
                    $user['role'],
                    '<button type="button" class="btn btn-outline-warning" onclick="open_user_modal('.$user['id'].',2)">Изменить</button>'.
                    '<button type="button" class="btn btn-outline-danger" onclick="open_user_modal('.$user['id'].',3)">Удалить</button>'
                );
            }
            $result = array(
                "draw" => $this->request->getVar('draw'),
                "recordsTotal" => count($data_users),
                "recordsFiltered" => count($data_users),
                "data" => $data
            );
            echo json_encode($result);
        }
    }

    public function get_users()
    {
        if(session('role')=='Администратор'){
            $all_data=[];
            $users=$this->user_model->findAll();
            foreach ($users as $key => $user) {
                $pers_data=$this->personal_data_model->where('user_id',$user['id'])->first();
                $row=array_merge($user,$pers_data);
                array_push($all_data,$row);
            }
            return json_encode($all_data);
        }
    }

    public function get()
    {
        if(session('role')=='Администратор'){
            if($this->request->getVar('user_id')){
                $data=[];
                $user_id=$this->request->getVar('user_id');
                $user=$this->user_model->where('id',$user_id)->first();
                $pers=$this->personal_data_model->where('user_id',$user_id)->first();
                if($user['role']=='Куратор'){
                    $spec=$this->specialization_model->where('user_id',$user['id'])->first();
                    $pers['specialization_id']=$spec['id'];
                }
                $data=array_merge($user,$pers);
                return json_encode($data);
            }
        }
    }

    public function multiply_add()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'){
                $validation =  \Config\Services::validation();
                if($thisFile=$this->request->getFile('file')){
                    $count=0;
                    if($thisFile->isValid()){
                        $rules['file'] = [
                            'rules'=>'uploaded[file]|ext_in[file,xls,xlsx]|max_size[file,4096]',
                            'errors' => [
                                'uploaded' => 'Не загружен!',
                                'ext_in' => 'Файл должен быть формата: xls или xlsx',
                                'max_size' => 'Слишком большой файл!'
                            ]
                        ];
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
                            $file_excel=$this->request->getFile('file');
                            $ext=$file_excel->getClientExtension();
                            if($ext=='xls'){
                                $render=new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                            }
                            else{
                                $render=new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                            }
                            $spreadsheet=$render->load($file_excel);
                            $data=$spreadsheet->getActiveSheet()->toArray();
                            foreach ($data as $key => $row) {
                                if($key==0){
                                    continue;
                                }
                                $first_name=$row[0];
                                $last_name=$row[1];
                                $middle_name=$row[2];
                                $specialization=$row[3];
                                $group=$row[4];  
                                $email=$row[5];                            
                                if($first_name&&$last_name){
                                    $special_id=null;
                                    $group_id=null;
                                    if($this->request->getVar('use_data')&&$this->request->getVar('group_mass')&&$this->request->getVar('specialization_mass')){
                                        $special_id=$this->request->getVar('specialization_mass');
                                        $group_id=$this->request->getVar('group_mass');
                                        
                                    }
                                    else{
                                        if($specialization){
                                            if($this->specialization_model->where('name',$specialization)->first()){
                                                $spec_row=$this->specialization_model->where('name',$specialization)->first();
                                                $special_id=$spec_row['id'];
                                            }
                                            else{
                                               $this->specialization_model->insert(['name'=>$specialization]);
                                               $spec_row=$this->specialization_model->where('name',$specialization)->first();
                                               $special_id=$spec_row['id'];
                                            }
                                        }
                                        if($group){
                                            if($this->group_model->where('name',$group)->first()){
                                                $group_row=$this->group_model->where('name',$group)->first();
                                                $group_id=$group_row['id'];
                                            }
                                            else{
                                               $this->group_model->insert(['name'=>$group]);
                                               $group_row=$this->group_model->where('name',$group)->first();
                                               $group_id=$group_row['id'];
                                            }
                                        }
    
                                    }
                                    $inset_pers=[
                                        'first_name'=>$first_name,
                                        'last_name'=>$last_name,
                                        'middle_name'=>$middle_name,
                                        'specialization_id'=>$special_id,
                                        'group_id'=>$group_id
                                    ];
                                    $inser_user=[
                                        'email'=>$email,
                                        'password'=>$this->generatePassword()
                                    ];
                                    if(!$this->user_model->where('email',$inser_user['email'])->first()){
                                        if($user=$this->user_model->insert($inser_user)){
                                            $user_data=$this->user_model->where('email',$inser_user['email'])->first();
                                            $inset_pers['user_id']=$user_data['id'];
                                            $this->personal_data_model->insert($inset_pers);
                                            $data_email=array_merge($inset_pers,$inser_user);
                                            $this->email->send_info($data_email);
                                            $count=$count+1;
                                        }
                                    }
                                }
                            }
                        }
                        return json_encode(['add_success'=>'Всего добавлено: '.$count.' студентов']);
                    }
                    else{
                        return json_encode(['validation'=>'Невалидный файл']);
                    }
    
                }
                else{
                    return json_encode(['error'=>'Выберите файл!']);
                }
            }
           
        }
    }

    public function add()
    {
        //return json_encode(true);
        if(session('role')=='Администратор'){
            if($this->request->getMethod()=='post'){
                $validation =  \Config\Services::validation();
                $data=[];
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
                            'date_birth_Validation' => 'Студенту должно быть минимум 17 лет!'
                        ]
                    ],
                    'email'    => [
                        'rules'  => 'required|valid_email|min_length[3]|max_length[200]|is_unique[users.email]',
                        'errors' => [
                            'valid_email' => 'Не валидный Email!',
                            'required' => 'Email - обязательное поле!',
                            'min_length' => 'Email должен содержать не менее 3 символов!',
                            'max_length' => 'Email должен содержать не более 200 символов!',
                            'is_unique' => 'Аккаунт с указанной почтой уже существует!',
                            //'email_mpt_Validation' => 'Почта не привязана к домену мпт!'
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
                    ]
                ];
                switch($this->request->getVar('role')){
                    case 'Студент':{
                        $rules_stud = [                            
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
                        $rules=array_merge($rules,$rules_stud);
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
                                'active' =>$this->request->getVar('active')
                            ];
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }
                            
                            if(!$this->groups_specializations_model->where(['specialization_id'=>$this->request->getVar('specialization'),'group_id'=>$this->request->getVar('groups')])->first()){
                                return json_encode(['validation'=>'<ul><li>Не пытайтесь установить неверные значения!</li></ul>']);
                            }
                        }
                    }break;
                    case 'Куратор':{
                        $rules_curator = [
                            'specialization' => [
                                'rules'  => 'required',
                                'errors' => [
                                    'required' => 'Специальность - обязательное поле!',
                                    
                                ]
                            ],
                        ];
                        $rules=array_merge($rules,$rules_curator);
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),
                                'specialization_id' => $this->request->getVar('specialization'),
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'password' => $this->request->getVar('password'),
                                'role'=>'Куратор',
                                'active' =>$this->request->getVar('active')
                            ];
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }
                            
                            if(!$this->specialization_model->where(['id'=>$this->request->getVar('specialization')])->first()){
                                return json_encode(['validation'=>'<ul><li>Такой специальности нет!</li></ul>']);
                            }
                            else{
                                $kurator=$this->specialization_model->where(['id'=>$this->request->getVar('specialization')])->first();
                                if($this->user_model->where(['id'=>$kurator['user_id'],'role'=>'Куратор'])->first()&&$kurator['user_id']!=$this->request->getVar('user_id')){
                                    return json_encode(['validation'=>'<ul><li>У данной специальности уже есть куратор!</li></ul>']);
                                }                          
                            }
                        }
                    }break;
                    case 'Работодатель':{
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'password' => $this->request->getVar('password'),
                                'role'=>'Работодатель',
                                'active' =>$this->request->getVar('active')
                            ];
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }                            
                        }    
                    }break;
                    case 'Администратор':{
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),                                
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'password' => $this->request->getVar('password'),
                                'role'=>'Администратор',
                                'active' =>true
                            ];
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }                            
                        }  
                    }break;
                }
                
                $user_data['active']=true;
                if($this->request->getVar('role')=='Администратор'&&!session('is_root')){
                    json_encode(['error'=>'Только root администратор может выдавать права администратора!']);
                }
                //Если пользователь добавлен
                if($this->user_model->insert($user_data)){                    
                    $inserted_user_data=$this->user_model->where('email',$user_data['email'])->first();//Получаем добавленного пользователя
                    if($this->request->getVar('role')=='Куратор'){
                        $this->specialization_model->where(['id'=>$this->request->getVar('specialization')])->update($this->request->getVar('specialization'),['user_id'=>$inserted_user_data['id']]);
                    }
                    if(is_file($my_image)){
                        $file_name=$my_image->getRandomName();
                        $personal_data['photo'] = $file_name;
                        $my_image->move(WRITEPATH.'uploads/profile/'.$inserted_user_data['id'],$file_name);
                    }
                    $personal_data['user_id']=$inserted_user_data['id'];//Устанавливаем значение id пользователя
                    $personal_data['number_phone']=$this->request->getVar('number_phone');
                    $personal_data['sex']=$this->request->getVar('sex');
                    $this->personal_data_model->insert($personal_data);//Записываем персональные данные
                    $data_email=array_merge($personal_data,$user_data);//Данные для отправки письма
                    $this->email->send_info($data_email);
                    return json_encode(true);
                }
                else{
                    return json_encode(['validation'=>'Ошибка создания учётной записи!']);
                }
            }
        }
    }

    public function edit()
    {
        if(session('role')=='Администратор'){
            if($this->request->getMethod()=='post'){
                $validation =  \Config\Services::validation();
                $data=[];
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
                            'date_birth_Validation' => 'Студенту должно быть минимум 17 лет!'
                        ]
                    ],
                    'email'    => [
                        'rules'  => 'required|valid_email|min_length[3]|max_length[200]',
                        'errors' => [
                            'valid_email' => 'Не валидный Email!',
                            'required' => 'Email - обязательное поле!',
                            'min_length' => 'Email должен содержать не менее 3 символов!',
                            'max_length' => 'Email должен содержать не более 200 символов!',
                            //'is_unique' => 'Аккаунт с указанной почтой уже существует!',
                            //'email_mpt_Validation' => 'Почта не привязана к домену мпт!'
                        ]
                    ]
                ];
                if($this->request->getVar('password')){
                    $rules_password = [
                        'password' => [
                            'rules'  => 'required|min_length[8]|max_length[20]|pass_Validation[password]',
                            'errors' => [
                                'required' => 'Пароль - обязательное поле!',
                                'min_length' => 'Пароль должен содержать не менее 8 символов!',
                                'max_length' => 'Пароль должен содержать не более 20 символов!',
                                'pass_Validation' => 'Пароль должен содержать латинские символы верхнего и нижнего регистра, цифры и специальные знаки!'
                            ]
                        ]
                    ];
                    $rules=array_merge($rules,$rules_password);
                }
                switch($this->request->getVar('role')){
                    case 'Студент':{
                        $rules_stud = [                            
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
                        $rules=array_merge($rules,$rules_stud);
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),
                                'sex' => $this->request->getVar('sex'),
                                'specialization_id' => $this->request->getVar('specialization'),
                                'group_id' => $this->request->getVar('groups')
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'role'=>$this->request->getVar('role')
                            ];
                            if($this->request->getVar('password')){
                                $user_data['password']=$this->request->getVar('password');
                            }
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }
                            
                            if(!$this->groups_specializations_model->where(['specialization_id'=>$this->request->getVar('specialization'),'group_id'=>$this->request->getVar('groups')])->first()){
                                return json_encode(['validation'=>'<ul><li>Не пытайтесь установить неверные значения!</li></ul>']);
                            }
                        }
                    }break;
                    case 'Куратор':{
                        $rules_curator = [
                            'specialization' => [
                                'rules'  => 'required',
                                'errors' => [
                                    'required' => 'Специальность - обязательное поле!',
                                    
                                ]
                            ],
                        ];
                        $rules=array_merge($rules,$rules_curator);
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),
                                'sex' => $this->request->getVar('sex'),
                                'specialization_id' => $this->request->getVar('specialization'),
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'role'=>'Куратор'
                            ];
                            if($this->request->getVar('password')){
                                $user_data['password']=$this->request->getVar('password');
                            }
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }
                            
                            if(!$this->specialization_model->where(['id'=>$this->request->getVar('specialization')])->first()){
                                return json_encode(['validation'=>'<ul><li>Такой специальности нет!</li></ul>']);
                            }
                            else{
                                $kurator=$this->specialization_model->where(['id'=>$this->request->getVar('specialization')])->first();
                                if($this->user_model->where(['id'=>$kurator['user_id'],'role'=>'Куратор'])->first()&&$kurator['user_id']!=$this->request->getVar('user_id')){
                                    return json_encode(['validation'=>'<ul><li>У данной специальности уже есть куратор!</li></ul>']);
                                }                                
                            }
                        }
                    }break;
                    case 'Работодатель':{
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),
                                'sex' => $this->request->getVar('sex'),
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'role'=>'Работодатель',
                            ];
                            if($this->request->getVar('password')){
                                $user_data['password']=$this->request->getVar('password');
                            }
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }                            
                        }    
                    }break;
                    case 'Администратор':{
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
                            // Получаем данные с html формы (разделяем на два массива, поскольку запись будем делать в разные таблицы)
                            $personal_data=[
                                'first_name' => $this->request->getVar('first_name'),
                                'last_name' => $this->request->getVar('last_name'),
                                'middle_name' => $this->request->getVar('middle_name'),  
                                'sex' => $this->request->getVar('sex'),                              
                            ];
                            $user_data=[
                                'email' => $this->request->getVar('email'),
                                'role'=>'Администратор',
                            ];
                            if($this->request->getVar('password')){
                                $user_data['password']=$this->request->getVar('password');
                            }
                            $date_birth=strtotime($this->request->getVar('date_birth'));
                            if($date_birth){
                                $bd_date_birth=date('Y.m.d',$date_birth);
                                $personal_data['date_birth']=$bd_date_birth;
                            }                            
                        }  
                    }break;
                }

                if($this->user_model->where('id',$this->request->getVar('user_id'))->first()){
                    $user=$this->user_model->where('id',$this->request->getVar('user_id'))->first();
                    if($user['role']=='Администратор'&&!session('is_root')&&$user['id']!=session('id')){
                        return json_encode(['error'=>'Только root администратор может снять администратора!']);
                    }
                    else if($user['role']=='Администратор'&&$user['id']==session('id')&&$this->request->getVar('role')!='Администратор'&&!session('is_root')){
                        return json_encode(['error'=>'Только root администратор может снять администратора!']);
                    }
                    else if($user['role']=='Администратор'&&$user['id']==session('id')&&$this->request->getVar('role')!='Администратор'&&session('is_root')){
                        $all_roots=$this->user_model->where(['role'=>'Администратор','is_root'=>true])->findAll();
                        if(count($all_roots)<=1){
                            return json_encode(['error'=>'Вы не можете снять себя с поста администратора! Только у вас root права!']);
                        }                        
                    }
                }
                //Если пользователь изменён
                if($this->user_model->update($this->request->getVar('user_id'),$user_data)){                    
                    if($this->request->getVar('role')=='Куратор'){
                        $this->specialization_model->where(['user_id'=>$this->request->getVar('user_id')])->update($this->request->getVar('specialization'),['user_id'=>'null']);
                        $this->specialization_model->where(['id'=>$this->request->getVar('specialization')])->update($this->request->getVar('specialization'),['user_id'=>$this->request->getVar('user_id')]);
                    }
                    // $inserted_user_data=$this->user_model->where('email',$user_data['email'])->first();//Получаем добавленного пользователя
                    if(is_file($my_image)){
                        $file_name=$my_image->getRandomName();
                        $personal_data['photo'] = $file_name;
                        $my_image->move(WRITEPATH.'uploads/profile/'.$this->request->getVar('user_id'),$file_name);
                    }
                    $personal_data['number_phone']=$this->request->getVar('number_phone');
                    $this->personal_data_model->update($this->request->getVar('user_id'),$personal_data);//Записываем персональные данные
                    return json_encode(true);
                }
                else{
                    return json_encode(['validation'=>'Ошибка изменения учётной записи!']);
                }
            }
        }
    }

    public function delete()
    {
        
        if(session('role')=='Администратор'){
            if($this->request->getVar('id')){
                
                $user_id=$this->request->getVar('id');
                if($this->user_model->where('id',$user_id)->first()){
                    $user=$this->user_model->where('id',$user_id)->first();
                    if($user['role']=='Администратор'&&!session('is_root')){
                        return json_encode(['error'=>'Только root администратор может это сделать!']);
                    }
                    else if($user['role']=='Администратор'&&session('role')=='Администратор'&&session('is_root')){
                        $roots=$this->user_model->where(['role'=>'Администратор','is_root'=>true])->findAll();
                        if(count($roots)==1){
                            $root=$this->user_model->where(['role'=>'Администратор','is_root'=>true,'id'=>$user['id']])->first();
                            if($root){
                                return json_encode(['error'=>'Вы единственный администратор с полными правами! Вы не можете этого сделать!']);
                            }
                        }
                        if($user['role']=='Администратор'&&$user['is_root']){
                            return json_encode(['error'=>'Вы не можете удалить администратора с полными правами!']);
                        }
                        else{
                            $this->specialization_model->where(['user_id'=>$user_id])->update($this->request->getVar('specialization'),['user_id'=>'null']);
                            $this->user_model->where('id',$user_id)->delete();
                            return json_encode(true);
                        }
                                                    
                    }
                    else{
                        $this->specialization_model->where(['user_id'=>$user_id])->update($this->request->getVar('specialization'),['user_id'=>'null']);
                        $this->user_model->where('id',$user_id)->delete();
                        return json_encode(true);
                    }
                }
                else{
                    return json_encode(false);
                }
                
            }
            else if($this->request->getVar('users')){
                $users=$this->request->getVar('users');
                foreach ($users as $key => $value) {
                    if($this->user_model->where('id',$value)->first()){
                        $user=$this->user_model->where('id',$value)->first();
                        if($user['role']=='Администратор'&&!session('is_root')){
                            return json_encode(['error'=>'Только root администратор может это сделать!']);
                        }
                        else if($user['role']=='Администратор'&&session('role')=='Администратор'&&session('is_root')){
                            $roots=$this->user_model->where(['role'=>'Администратор','is_root'=>true])->findAll();
                            if(count($roots)<=1){
                                return json_encode(['error'=>'Вы единственный администратор с полными правами! Вы не можете этого сделать!']);

                            }
                            if($user['role']=='Администратор'&&$user['is_root']){
                                $root=$this->user_model->where(['role'=>'Администратор','is_root'=>true,'id'=>$user['id']])->first();
                                if($root){
                                    return json_encode(['error'=>'Вы единственный администратор с полными правами! Вы не можете этого сделать!']);
                                }
                            }
                            else{
                                $this->specialization_model->where(['user_id'=>$user_id])->update($this->request->getVar('specialization'),['user_id'=>'null']);
                                $this->user_model->where('id',$user_id)->delete();
                                return json_encode(true);
                            }                            
                        }
                    }
                }
                foreach ($users as $key => $value) {
                    $this->specialization_model->where(['user_id'=>$user_id])->update($this->request->getVar('specialization'),['user_id'=>'null']);
                    $user=$this->user_model->where('id',$value)->delete();
                }
                return json_encode(true);
            }
        }
        else{
            return json_encode(['error'=>'Нет прав доступа!']);
        }
    }

    function generatePassword($upper = 2, $lower = 4, $numeric = 2, $other = 1): string
    {
        $password = '';
        $passwordOrder = [];
        for ($i = 0; $i < $upper; $i++) {
            $passwordOrder[] = chr(rand(65, 90));
        }
        for ($i = 0; $i < $lower; $i++) {
            $passwordOrder[] = chr(rand(97, 122));
        }
        for ($i = 0; $i < $numeric; $i++) {
            $passwordOrder[] = chr(rand(48, 57));
        }
        for ($i = 0; $i < $other; $i++) {
            $passwordOrder[] = chr(rand(33, 47));
        }
        shuffle($passwordOrder);
        foreach ($passwordOrder as $char) {
            $password .= $char;
        }
        return $password;
    }
}