<?php
namespace App\Controllers;
use App\Models\GroupModel;
use App\Models\GroupsSpecializationsModel;

class GroupCrud extends BaseController
{
    protected $group_model;
    protected $groups_specializations_model;

    public function __construct()
    {
        $this->group_model=new GroupModel();
        $this->groups_specializations_model= new GroupsSpecializationsModel();
        helper('form', 'url','array'); // Подгрузка хелперов
    }

    public function get_all()
    {
        $data=[];
        $all_data=$this->group_model->findAll();
        if($all_data){
            foreach ($all_data as $key => $value) {
                $data[]=array(
                    $value['id'],
                    $value['name']
                );
            }
        }
        $result = array(
            "draw" => $this->request->getVar('draw'),
            "recordsTotal" => count($all_data),
            "recordsFiltered" => count($all_data),
            "data" => $data
        );
        echo json_encode($result);
    }

    public function get()
    {
        switch(session('role')){
            case 'Куратор':{
                if($this->request->getMethod()=='post'){
                    $all_data=$this->groups_specializations_model->where('specialization_id',session('specialization_id'))->findAll();
                    $list=[];
                    foreach ($all_data as $key => $item) {
                        $group=$this->group_model->where('id',$item['group_id'])->first();
                        array_push($list,$group);
                    }
                    //$this->group_model->findAll();
                    return json_encode($list);
                }
            }break;
            case 'Администратор':{
                if($this->request->getMethod()=='post'){
                    $all_data=$this->group_model->findAll();
                    return json_encode($all_data);
                }
            }break;
        }
    }

    public function add()
    {
        if($this->request->getMethod()=='post'){
            $validation =  \Config\Services::validation();
            if($this->request->getVar('group_name')){
                $groups=$this->request->getVar('group_name');
            }
            else{
                $data['validation']='<ul><li>Укажите наименование хотя бы одной группы!</li></ul>';
                return json_encode($data);
            }
            switch(session('role')){
                case 'Куратор':{
                    $rules=[];
                    foreach($groups as $key => $value){
                        $rules[ 'group_name.' . $key ] = [
                            'rules'  => 'required|min_length[3]|max_length[15]|is_unique[groups.name]',
                            'errors' => [
                                'is_unique' => 'В системе уже имеется информация о данной(ых) группе(ах)',
                                'required' => 'Заполните все поля наименований для групп',
                                'min_length' => 'Минимальная длина поля - 3 символов.',
                                'max_length' => 'Максимальная длина поля - 15 символов.'
                            ]
                        ];
        
                    }
                    if(!$this->validate($rules)){
                        $errors = array_unique($validation->getErrors());
                        //$data['validation'] = $errors;
                        $list_errors='<ul>';
                        foreach ($errors as $key => $value) {
                            $list_errors=$list_errors.'<li>'.$value.'</li>';
                        }
                        $list_errors=$list_errors.'</ul>';
                        $data['validation'] = $list_errors;
                        return json_encode($data);
                    }
                    else{
                        if(!$this->check_array($groups,$groups)){
                            $data['validation'] ='<ul><li>Не дублируйте группы!</li></ul>';
                            return json_encode($data);
                        }
                        foreach ($groups as $key => $value) {
                            $data=[
                                'name' => $value
                            ];
                            if($this->group_model->insert($data)){
                                $my_group=$this->group_model->where('name',$value)->first();
                                $data_where=[
                                    'specialization_id'=>session('specialization_id'),
                                    'group_id'=>$my_group['id']
                                ];
                                $this->groups_specializations_model->insert($data_where);
                            }
                        }
                        return json_encode(true);
                    }                   
                }break;
                case 'Администратор':{
                    $rules=[];
                    foreach($groups as $key => $value){
                        $rules[ 'group_name.' . $key ] = [
                            'rules'  => 'required|min_length[3]|max_length[15]|is_unique[groups.name]',
                            'errors' => [
                                'is_unique' => 'В системе уже имеется информация о данной(ых) группе(ах)',
                                'required' => 'Заполните все поля наименований для групп',
                                'min_length' => 'Минимальная длина поля - 3 символов.',
                                'max_length' => 'Максимальная длина поля - 15 символов.'
                            ]
                        ];
        
                    }
                    if(!$this->validate($rules)){
                        $errors = array_unique($validation->getErrors());
                        //$data['validation'] = $errors;
                        $list_errors='<ul>';
                        foreach ($errors as $key => $value) {
                            $list_errors=$list_errors.'<li>'.$value.'</li>';
                        }
                        $list_errors=$list_errors.'</ul>';
                        $data['validation'] = $list_errors;
                        return json_encode($data);
                    }
                    else{
                        if(!$this->check_array($groups,$groups)){
                            $data['validation'] ='<ul><li>Не дублируйте группы!</li></ul>';
                            return json_encode($data);
                        }
                        foreach ($groups as $key => $value) {
                            $data=[
                                'name' => $value
                            ];
                            $this->group_model->insert($data);
                        }
                        return json_encode(true);
                    }                    
                }break;
            }
        }
    }

    public function edit()
    {
        if($this->request->getMethod()=='post'){
            $old_ex=false;
            $new_ex=false;
            $groups_new=null;
            $groups_old=null;
            $validation =  \Config\Services::validation();
            if($this->request->getVar('old_group_name')){
                $groups_old=$this->request->getVar('old_group_name');
                $old_ex=true;
            }
            if($this->request->getVar('group_name')){
                $groups_new=$this->request->getVar('group_name');
                $new_ex=true;
            }
            $rules=[];
            if($groups_old){
                foreach($groups_old as $key => $value){
                    $rules[ 'old_group_name.' . $key ] = [
                        'rules'  => 'min_length[3]|max_length[15]',
                        'errors' => [
                            'min_length' => 'Минимальная длина поля - 3 символов.',
                            'max_length' => 'Максимальная длина поля - 15 символов.'
                        ]
                    ];
    
                }
            }
            if($groups_new){
                foreach($groups_new as $key => $value){
                    $rules[ 'group_name.' . $key ] = [
                        'rules'  => 'min_length[3]|max_length[15]',
                        'errors' => [
                            'min_length' => 'Минимальная длина поля - 3 символов.',
                            'max_length' => 'Максимальная длина поля - 15 символов.'
                        ]
                    ];
    
                }
            }
            if(($new_ex||$old_ex)){
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
                    $array=[];
                    if($groups_new&&!$this->check_array($groups_new,$groups_new)){
                        $data['validation'] ='<ul><li>Не дублируйте наименования групп!</li></ul>';
                        return json_encode($data);
                    }
                    else if(!$this->check_array($groups_old,$groups_old)){
                        $data['validation'] ='<ul><li>Не дублируйте наименования групп!</li></ul>';
                        return json_encode($data);
                    }
                    else if(($groups_old&&$groups_new)&&!$this->check_array($groups_old,$groups_new)){
                        $data['validation'] ='<ul><li>Не дублируйте наименования групп!</li></ul>';
                        return json_encode($data);
                    }
                    else{
                        switch(session('role')){
                            case 'Куратор':{
                                $groups=$this->groups_specializations_model->where('specialization_id',session('specialization_id'))->findAll();
                                foreach ($groups as $key => $group_item) {
                                    $group=$this->group_model->where('id',$group_item['group_id'])->first();
                                    array_push($array,$group);
                                }
                            }break;
                            case 'Администратор':{
                                $array=$this->group_model->findAll();
                            }break;
                        }
                        if($groups_old){
                            $count_old=count($groups_old);
                            foreach ($groups_old as $key => $value) {
                                if($this->group_model->where(['name'=>$value])->first()){
                                    $first=$this->group_model->where(['name'=>$value])->first();
                                    if($first['id']!=$key){
                                        $data['validation']='Группа с наименованием: "'.$value.'" уже есть!';
                                        return json_encode($data);
                                    }
                                }
                            }
                            foreach ($groups_old as $key => $value) {
                                if($this->group_model->where(['id'=>$key])->first()){
                                    $first=$this->group_model->where(['id'=>$key])->first();
                                    $this->group_model->update($first['id'],['name'=>$value]);
                                }
                            }
                            foreach ($array as $key => $item) {
                                $not_ex_count=0;
                                foreach ($groups_old as $key => $value) {
                                    if($item['id']!=$key){
                                        $not_ex_count=$not_ex_count+1;
                                    }
                                    if($not_ex_count==$count_old){
                                        $this->group_model->where(['id'=>$item['id']])->delete();
                                    }
                                } 
                            }
                        }
                        if($groups_new){
                            foreach ($groups_new as $key => $value) {
                                if($this->group_model->where(['name'=>$value])->first()){
                                    $data['validation']='Группа с наименованием: "'.$value.'" уже есть!';
                                    return json_encode($data);
                                }
                            }
                            foreach ($groups_new as $key => $value) {
                                if($this->group_model->insert(['name'=>$value])){
                                    if(session('role')=='Куратор'){
                                        $my_group=$this->group_model->where('name',$value)->first();
                                        $data_where=[
                                            'specialization_id'=>session('specialization_id'),
                                            'group_id'=>$my_group['id']
                                        ];
                                        $this->groups_specializations_model->insert($data_where);
                                    }
                                }
                            }
                        }
                    }
                    return json_encode(true);
                }
            }
            else{
                $all_data=$this->group_model->findAll();
                foreach ($all_data as $key => $value) {
                    $this->group_model->where(['name'=>$value['name']])->delete();
                }
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