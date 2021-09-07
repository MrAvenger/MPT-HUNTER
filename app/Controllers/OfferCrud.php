<?php

namespace App\Controllers;
use App\Models\OrganizationModel;
use App\Models\JobOfferModel;
use App\Models\JobOfferRequireModel;
use App\Models\StudentOfferModel;
use App\Models\StudentOrganizationModel;

class OfferCrud extends BaseController
{
    protected $organization_model;
    protected $job_offer_model;
    protected $job_offer_require_model;
    protected $student_offer_model;
    protected $student_organization_model;
	//Создаём функцию конструктора, в которой подключим все необходимые библиотеки, хелперы.
	public function __construct()
    {
        $this->organization_model=new OrganizationModel();
        $this->job_offer_model= new JobOfferModel();
        $this->job_offer_require_model= new JobOfferRequireModel();
        $this->student_offer_model=new StudentOfferModel();
        $this->student_organization_model=new StudentOrganizationModel();
        helper('form', 'url','array','filesystem'); // Подгрузка хелперов
    }

    public function get_data()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Работодатель'){
                $array_offers=$this->job_offer_model->where('org_id',session('org_id'))->findAll();
                if($array_offers){
                    $org_data=$this->organization_model->where('user_id',session('id'))->first();
                    //$offer_requirements=$this->job_offer_require_model->where('offer_id',$org_data['id'])->findAll();
                    
                    $org_data['photo']=base_url().'/writable/uploads/organizations/'.$org_data['user_id'].'/'.$org_data['org_photo'];
                    $offer_itog_data=[
                        'org' => $org_data,
                        'offers' =>$array_offers,
                        'role' => session('role')
                    ];
                    return json_encode($offer_itog_data);
                }
                else{
                    return json_encode(null);
                }

            }
            else{
                $array_offers=[];
                if($this->request->getVar('search')){
                    $search_val=$this->request->getVar('search');
                    $array_offers=$this->job_offer_model->where("offer_name LIKE '%".$search_val."%'")->findAll();
                }
                else{
                    $array_offers=$this->job_offer_model->findAll();
                }
                
                if($array_offers){
                    $org_data=$this->organization_model->findAll();
                    // $org_data['photo']=base_url().'/uploads/organizations/'.$org_data['org_name'].'/'.$org_data['org_photo'];
                    $all_info_offers=[];
                    foreach ($array_offers as $key => $item) {
                        $org=$this->organization_model->where('id',$item['org_id'])->first();
                        if($org){
                            unset($org['id']);
                            if($org['org_photo']){
                                $org['org_photo']=base_url().'/writable/uploads/organizations/'.$org['user_id'].'/'.$org['org_photo'];
                            }
                            else{
                                $org['org_photo']=base_url().'/assets/img/design/org_photo.jpg';
                            }
                            $favorite_and_respond=[
                                'user_id' => session('id'),
                                'offer_id' => $item['id']
                            ];
                            if($this->student_offer_model->where($favorite_and_respond)->first()){
                                $rf_data=$this->student_offer_model->where($favorite_and_respond)->first();
                                if($rf_data['is_favorite']){
                                    $item['is_favorite']=true;
                                }
                                else if($rf_data['is_favorite']){
                                    $item['is_favorite']=false;
                                }
                                if($rf_data['is_respond']){
                                    $item['is_respond']=true;
                                }
                                else if($rf_data['is_respond']){
                                    $item['is_respond']=false;
                                }
                            }
                            $row=array_merge($item,$org);
                            array_push($all_info_offers,$row);
                        }

                    }
                    $offer_itog_data=[
                        'all_info' => $all_info_offers,
                        'role' => session('role')
                    ];
                    return json_encode($offer_itog_data);
                }
                else{
                    return json_encode(null);
                }
            }
        }
    }

    public function get()
    {
        if($this->request->getMethod()=='post'&&(session('role')=='Работодатель'||session('role')=='Студент')){
            $id=$this->request->getVar('id');
            $offer=$this->job_offer_model->where('id',$id)->first();
            $offer_require=$this->job_offer_require_model->where('offer_id',$offer['id'])->findAll();
            $offer['offer_name']=htmlspecialchars($offer['offer_name']);
            $offer['offer_description']=htmlspecialchars($offer['offer_description']);
            $data=[
                'offer' =>$offer,
                'requirements' =>$offer_require
            ];
            if(session('role')=='Студент'){
                $org_data=$this->organization_model->where('id',$offer['org_id'])->first();
                $data['org']=$org_data;
            }
            return json_encode($data);
        }
    }

    public function add()
    {
        if($this->request->getMethod()=='post'&&session('role')=='Работодатель'){
            $data_offer_where=[
                'org_id' => session('org_id'),
                'offer_name' => $this->request->getVar('offer_name')
            ];
            $old_offer=$this->job_offer_model->where($data_offer_where)->first();
            if(!$old_offer){
                $validation =  \Config\Services::validation();
                $rules = [
                    'offer_name' => [
                        'rules'  => 'required|min_length[5]|max_length[200]|is_unique[job_offers.offer_name]',
                        'errors' => [
                            'required' => 'Укажите вакансию! (наименование предложения)',
                            'min_length' => 'Минимальная длина наименования вакансии - 5 символов!',
                            'max_length' => 'Максимальная длина наименования вакансии - 200 символов!',
                            'is_unique' => 'Вы уже создавали предлжение с такой вакансией (наименованием)'
                        ]
                    ],
                    'salary'    => [
                        'rules'  => 'is_salary_Validation[salary]',
                        'errors' => [
                            'is_salary_Validation' => 'Введите правильное значение зарплаты',
                            
                        ]
                    ],
                    'offer_description'    => [
                        'rules'  => 'max_length[255]',
                        'errors' => [
                            'max_length' => 'Описание может составлять максимум 255 символов.'
                        ]
                    ],
                ];
                if($this->request->getVar('require')){
                    foreach($this->request->getVar('require') as $key => $cat){
                        $rules[ 'require.' . $key ] = [
                            'rules'  => 'min_length[5]|max_length[150]',
                            'errors' => [
                                'min_length' => 'Минимальная длина поля требования 5 символов.',
                                'max_length' => 'Максимальная длина поля требования - 150 символов.'
                            ]
                        ];
                    }
                }
        
                if(!$this->validate($rules)){
                    $errors = $validation->listErrors();
                    $data['validation'] = $errors;
                    return json_encode($data);
                }
                else{
                    if(!$this->request->getVar('require')){
                        $data['validation'] = '<ul><li>Укажите хотя бы одно требование к практиканту</li></ul>';
                        return json_encode($data);
                    }
                    else{
                        $offer_data=[
                            'org_id' => session('org_id'),
                            'employment' => $this->request->getVar('employment'),
                            'offer_name' => $this->request->getVar('offer_name'),
                            'salary' => $this->request->getVar('salary'),
                            'offer_description' => $this->request->getVar('offer_description'),
                        ];
                        $new_array=$this->request->getVar('require');
                        if(!$this->check_array($new_array,$new_array)){
                            $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                            return json_encode($data);
                        }
                        if($this->job_offer_model->insert($offer_data)){
                            $offer=$this->job_offer_model->where('offer_name',$offer_data['offer_name'])->first();
                            foreach ($this->request->getVar('require') as $value) {
                                $require_data=[
                                    'offer_id' => $offer['id'],
                                    'name' => $value
                                ];
                                $this->job_offer_require_model->insert($require_data);
                            }
                            return json_encode(true);
                        }
                    }
                }
            }
            else{
                return json_encode(['validation'=>'Предложение (вакансия) с таким наименованием уже есть!']);
            }
            
        }

    }

    public function edit($id)
    {
        if($this->request->getMethod()=='post' &&session('role')=='Работодатель'){
            $data_offer_where=[
                'org_id' => session('org_id'),
                'offer_name' => $this->request->getVar('offer_name')
            ];
            $old_offer=$this->job_offer_model->where($data_offer_where)->first();
            if(!$old_offer){
                $validation =  \Config\Services::validation();
                $old_require_exist=true;
                $new_require_exist=true;
                $old_array=[];
                $new_array=[];
                $rules = [
                    'offer_name' => [
                        'rules'  => 'required|min_length[5]|max_length[200]',
                        'errors' => [
                            'required' => 'Укажите вакансию! (наименование предложения)',
                            'min_length' => 'Минимальная длина наименования вакансии - 5 символов!',
                            'max_length' => 'Максимальная длина наименования вакансии - 200 символов!'
                        ]
                    ],
                    'salary'    => [
                        'rules'  => 'is_salary_Validation[salary]',
                        'errors' => [
                            'is_salary_Validation' => 'Введите правильное значение зарплаты'
                        ]
                    ],
                    'offer_description'    => [
                        'rules'  => 'max_length[255]',
                        'errors' => [
                            'max_length' => 'Описание может составлять максимум 255 символов.'
                        ]
                    ],
                ];
                if($this->request->getVar('require')){
                    foreach($this->request->getVar('require') as $key => $value){
                        $rules[ 'require.' . $key ] = [
                            'rules'  => 'min_length[5]|max_length[150]',
                            'errors' => [
                                'min_length' => 'Минимальная длина поля требования 5 символов.',
                                'max_length' => 'Максимальная длина поля требования - 150 символов.'
                            ]
                        ];
                    }
                    $new_require_exist=true;
                    $new_array=$this->request->getVar('require');
                }
                else{
                    $new_require_exist=false;
                }
    
                if($this->request->getVar('old_require')){
                    foreach($this->request->getVar('old_require') as $key => $value){
                        $rules[ 'old_require.' . $key ] = [
                            'rules'  => 'min_length[5]|max_length[150]',
                            'errors' => [
                                'min_length' => 'Минимальная длина поля требования 5 символов.',
                                'max_length' => 'Максимальная длина поля требования - 150 символов.'
                            ]
                        ];
                    }
                    $old_require_exist=true;
                    $old_array=$this->request->getVar('old_require');
                }
                else{
                    $old_require_exist=false;
                    $this->job_offer_require_model->where($id)->delete();
                }
        
                if(!$this->validate($rules)){
                    $errors = $validation->listErrors();
                    $data['validation'] = $errors;
                    return json_encode($data);
                }
                else{
                    if(!$this->request->getVar('require')&&!$this->request->getVar('old_require')){
                        $data['validation'] = '<ul><li>Укажите хотя бы одно требование к практиканту</li></ul>';
                        return json_encode($data);
                    }
                    else{
                        $offer_data=[
                            'org_id' => session('org_id'),
                            'employment' => $this->request->getVar('employment'),
                            'offer_name' => $this->request->getVar('offer_name'),
                            'salary' => $this->request->getVar('salary'),
                            'offer_description' => $this->request->getVar('offer_description'),
                        ];
                        if($new_require_exist&&$old_require_exist){
                            foreach ($new_array as $key1 => $value_new) {
                                foreach ($old_array as $key2 => $value_old) {
                                    if($value_new==$value_old){
                                        $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                        return json_encode($data);
                                    }
                                }
                            }
                            if(!$this->check_array($new_array,$new_array)){
                                $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                return json_encode($data);
                            }
                            else if(!$this->check_array($old_array,$old_array)){
                                $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                return json_encode($data);
                            }
                        }
                        else if(!$old_require_exist&&$new_require_exist){
                            if(!$this->check_array($new_array,$new_array)){
                                $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                return json_encode($data);
                            }
                        }
                        else if($old_require_exist&&!$new_require_exist){
                            if(!$this->check_array($old_array,$old_array)){
                                $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                return json_encode($data);
                            }
                        }
                        if($this->job_offer_model->update($id,$offer_data)){
                            if($old_require_exist){
                                $old=[];
                                foreach ($old_array as $key => $value) {
                                    $where_data=[
                                        'id' => $key,
                                        'offer_id' => $id,
                                    ];
                                    $data=[
                                        'name'=>$value
                                    ];
                                    if($this->job_offer_require_model->where($where_data)->first()){
                                        if(!$this->job_offer_require_model->update($key,$data)){
                                            return json_encode(['validation'=>'Ошибка!']);
                                        }
                                    }
                                    $row_data=[
                                        'id' => $key,
                                        'offer_id' => $id,
                                        'name' => $value
                                    ];
                                    array_push($old,$row_data);
                                }
                                $old_all_data=$this->job_offer_require_model->where('offer_id',$id)->findAll();
                                foreach ($old_all_data as $key1 => $value1) {
                                    foreach ($old as $key2 => $value2) {
                                        if($value2['name'] != $value1['name']&&$value1['id']!=$value2['id']){
                                            $this->job_offer_require_model->where('id',$value1['id'])->delete();
                                        }
                                    }
                                    //$this->job_offer_require_model->where($result)->delete();
                                }
                            }
                            else{
                                $this->job_offer_require_model->where('offer_id',$id)->delete();
                            }
                            if($new_require_exist){
                                foreach ($new_array as $key => $value) {
                                    $data_insert=[
                                        'offer_id' => $id,
                                        'name' => $value
                                    ];
                                    if(!$this->job_offer_require_model->insert($data_insert)){
                                        return json_encode(['validation'=>'Ошибка!']);
                                    }
                                }
                            }
                            return json_encode(true);
                        }
                        
                    }
                }
            }
            else{
                if($old_offer['id']==$id){
                    $validation =  \Config\Services::validation();
                    $old_require_exist=true;
                    $new_require_exist=true;
                    $old_array=[];
                    $new_array=[];
                    $rules = [
                        'offer_name' => [
                            'rules'  => 'required|min_length[5]|max_length[200]',
                            'errors' => [
                                'required' => 'Укажите вакансию! (наименование предложения)',
                                'min_length' => 'Минимальная длина наименования вакансии - 5 символов!',
                                'max_length' => 'Максимальная длина наименования вакансии - 200 символов!'
                            ]
                        ],
                        'salary'    => [
                            'rules'  => 'is_salary_Validation[salary]',
                            'errors' => [
                                'is_salary_Validation' => 'Введите правильное значение зарплаты'
                            ]
                        ],
                        'offer_description'    => [
                            'rules'  => 'max_length[255]',
                            'errors' => [
                                'max_length' => 'Описание может составлять максимум 255 символов.'
                            ]
                        ],
                    ];
                    if($this->request->getVar('require')){
                        foreach($this->request->getVar('require') as $key => $value){
                            $rules[ 'require.' . $key ] = [
                                'rules'  => 'min_length[5]|max_length[150]',
                                'errors' => [
                                    'min_length' => 'Минимальная длина поля требования 5 символов.',
                                    'max_length' => 'Максимальная длина поля требования - 150 символов.'
                                ]
                            ];
                        }
                        $new_require_exist=true;
                        $new_array=$this->request->getVar('require');
                    }
                    else{
                        $new_require_exist=false;
                    }
        
                    if($this->request->getVar('old_require')){
                        foreach($this->request->getVar('old_require') as $key => $value){
                            $rules[ 'old_require.' . $key ] = [
                                'rules'  => 'min_length[5]|max_length[150]',
                                'errors' => [
                                    'min_length' => 'Минимальная длина поля требования 5 символов.',
                                    'max_length' => 'Максимальная длина поля требования - 150 символов.'
                                ]
                            ];
                        }
                        $old_require_exist=true;
                        $old_array=$this->request->getVar('old_require');
                    }
                    else{
                        $old_require_exist=false;
                        $this->job_offer_require_model->where($id)->delete();
                    }
            
                    if(!$this->validate($rules)){
                        $errors = $validation->listErrors();
                        $data['validation'] = $errors;
                        return json_encode($data);
                    }
                    else{
                        if(!$this->request->getVar('require')&&!$this->request->getVar('old_require')){
                            $data['validation'] = '<ul><li>Укажите хотя бы одно требование к практиканту</li></ul>';
                            return json_encode($data);
                        }
                        else{
                            $offer_data=[
                                'org_id' => session('org_id'),
                                'employment' => $this->request->getVar('employment'),
                                'offer_name' => $this->request->getVar('offer_name'),
                                'salary' => $this->request->getVar('salary'),
                                'offer_description' => $this->request->getVar('offer_description'),
                            ];
                            if($new_require_exist&&$old_require_exist){
                                foreach ($new_array as $key1 => $value_new) {
                                    foreach ($old_array as $key2 => $value_old) {
                                        if($value_new==$value_old){
                                            $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                            return json_encode($data);
                                        }
                                    }
                                }
                                if(!$this->check_array($new_array,$new_array)){
                                    $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                    return json_encode($data);
                                }
                                else if(!$this->check_array($old_array,$old_array)){
                                    $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                    return json_encode($data);
                                }
                            }
                            else if(!$old_require_exist&&$new_require_exist){
                                if(!$this->check_array($new_array,$new_array)){
                                    $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                    return json_encode($data);
                                }
                            }
                            else if($old_require_exist&&!$new_require_exist){
                                if(!$this->check_array($old_array,$old_array)){
                                    $data['validation'] ='<ul><li>Не дублируйте требования к практиканту!</li></ul>';
                                    return json_encode($data);
                                }
                            }
                            if($this->job_offer_model->update($id,$offer_data)){
                                if($old_require_exist){
                                    $old=[];
                                    foreach ($old_array as $key => $value) {
                                        $where_data=[
                                            'id' => $key,
                                            'offer_id' => $id,
                                        ];
                                        $data=[
                                            'name'=>$value
                                        ];
                                        if($this->job_offer_require_model->where($where_data)->first()){
                                            if(!$this->job_offer_require_model->update($key,$data)){
                                                return json_encode(['validation'=>'Ошибка!']);
                                            }
                                        }
                                        $row_data=[
                                            'id' => $key,
                                            'offer_id' => $id,
                                            'name' => $value
                                        ];
                                        array_push($old,$row_data);
                                    }
                                    $old_all_data=$this->job_offer_require_model->where('offer_id',$id)->findAll();
                                    foreach ($old_all_data as $key1 => $value1) {
                                        foreach ($old as $key2 => $value2) {
                                            if($value2['name'] != $value1['name']&&$value1['id']!=$value2['id']){
                                                $this->job_offer_require_model->where('id',$value1['id'])->delete();
                                            }
                                        }
                                    }
                                }
                                else{
                                    $this->job_offer_require_model->where('offer_id',$id)->delete();
                                }
                                if($new_require_exist){
                                    foreach ($new_array as $key => $value) {
                                        $data_insert=[
                                            'offer_id' => $id,
                                            'name' => $value
                                        ];
                                        if(!$this->job_offer_require_model->insert($data_insert)){
                                            return json_encode(['validation'=>'Ошибка!']);
                                        }
                                    }
                                }
                                return json_encode(true);
                            }
                            
                        }
                    }
                }
                else{
                    return json_encode(['validation'=>'Предложение (вакансия) с таким наименованием уже есть!']);
                }
            }
            
            
        }
    }

    public function delete()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Работодатель'){
                $data_where=[
                    'org_id' => session('org_id'),
                    'id' => $this->request->getVar('id')
                ];
                if($this->job_offer_model->where($data_where)->first()){
                    if($this->job_offer_model->where($data_where)->delete()){
                        return json_encode(true);
                    }
                    else{
                        return json_encode(true);
                    }
                }
            }
        }  
    }

    public function add_favorite()
    {
        if($this->request->getMethod()=="post" &&session('role')=="Студент"){
            $offer_id = $this->request->getVar('id');
            $data_where=[
                'offer_id'=>$offer_id,
                'user_id'=>session('id')
            ];
            if($this->student_offer_model->where($data_where)->first()){
                $info=$this->student_offer_model->where($data_where)->first();
                $is_favorite=false;
                if($info['is_favorite']){
                    $is_favorite=false;
                }
                else{
                    $is_favorite=true;
                }
                $data=[
                    'user_id' => session('id'),
                    'is_favorite'=>$is_favorite,
                    'offer_id' => $offer_id
                ];
                $this->student_offer_model->update($info['id'],$data);
                return json_encode(true);
            }
            else{
                $data=[
                    'user_id' => session('id'),
                    'is_favorite'=>true,
                    'offer_id' => $offer_id
                ];
                $this->student_offer_model->insert($data);
                return json_encode(true);
            }
        }
    }

    public function add_respond()
    {
        if($this->request->getMethod()=="post" &&session('role')=="Студент"){
            $in_org_where=[
                'user_id'=>session('id'),
                'status'=>'Закреплён'
            ];
            if($this->student_organization_model->where($in_org_where)->first()){
                $in_org_data=$this->student_organization_model->where($in_org_where)->first();
                $org_data=$this->organization_model->where('id',$in_org_data['organization_id'])->first();
                $error=[
                    'error'=>'Вы уже привязаны к организации "'.$org_data['org_name'].'"'
                ];
                return json_encode($error);
            }
            $offer_id = $this->request->getVar('id');
            $data_where=[
                'offer_id'=>$offer_id,
                'user_id'=>session('id')
            ];
            if($this->student_offer_model->where($data_where)->first()){
                $info=$this->student_offer_model->where($data_where)->first();
                $is_favorite=false;
                if($info['is_respond']){
                    $is_respond=false;
                }
                else{
                    $is_respond=true;
                }
                $data=[
                    'user_id' => session('id'),
                    'is_respond'=>$is_respond,
                    'offer_id' => $offer_id
                ];
                $this->student_offer_model->update($info['id'],$data);
                return json_encode(true);
            }
            else{
                $data=[
                    'user_id' => session('id'),
                    'is_respond'=>true,
                    'offer_id' => $offer_id
                ];
                $this->student_offer_model->insert($data);
                return json_encode(true);
            }
        }
    }

    public function check_array($array1,$array2){
        $data=true;
        foreach ($array1 as $key1 => $value1) {
            foreach ($array2 as $key2 => $value2) {
                if(($value1==$value2)&&($key1!=$key2)){
                    $data = false;
                }
            }
        }
        return $data;
    }
    
}