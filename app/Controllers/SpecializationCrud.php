<?php
namespace App\Controllers;
use App\Models\SpecializationModel;

class SpecializationCrud extends BaseController
{
    protected $specialization_model;
    public function __construct()
    {
        $this->specialization_model=new SpecializationModel();
        helper('form', 'url','array'); // Подгрузка хелперов
    }

    public function get_all()
    {
        if(session('role')=='Администратор'){
            $data=[];
            $all_data=$this->specialization_model->findAll();
            if($all_data){
                foreach ($all_data as $key => $value) {
                    $data[]=array(
                        $value['id'],
                        htmlspecialchars($value['name'])
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
    }

    public function get()
    {
        if(session('role')=='Администратор'){
            if($this->request->getMethod()=='post'){
                $all_data=$this->specialization_model->findAll();
                return json_encode($all_data);
            }
        }

    }

    public function add()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'){
                $validation =  \Config\Services::validation();
                if($this->request->getVar('specialization_name')){
                    $groups=$this->request->getVar('specialization_name');
                }
                else{
                    $data['validation']='<ul><li>Укажите наименование хотя бы одной специальностей!</li></ul>';
                    return json_encode($data);
                }
                $rules=[];
                foreach($groups as $key => $value){
                    $rules[ 'specialization_name.' . $key ] = [
                        'rules'  => 'required|min_length[3]|max_length[255]|is_unique[groups.name]',
                        'errors' => [
                            'is_unique' => 'В системе уже имеется информация о данной(ых) специальностейе(ах)',
                            'required' => 'Заполните все поля наименований для специальностей',
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
                        $data['validation'] ='<ul><li>Не дублируйте специальности!</li></ul>';
                        return json_encode($data);
                    }
                    foreach ($groups as $key => $value) {
                        $data=[
                            'name' => $value
                        ];
                        $this->specialization_model->insert($data);
                    }
                    return json_encode(true);
                }
            }
        }
    }

    public function edit()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'){
                $old_ex=false;
                $new_ex=false;
                $new_data=null;
                $old_data=null;
                $validation =  \Config\Services::validation();
                if($this->request->getVar('old_specialization_name')){
                    $old_data=$this->request->getVar('old_specialization_name');
                    $old_ex=true;
                }
                if($this->request->getVar('specialization_name')){
                    $new_data=$this->request->getVar('specialization_name');
                    $new_ex=true;
                }
                $rules=[];
                if($old_data){
                    foreach($old_data as $key => $value){
                        $rules[ 'old_specialization_name.' . $key ] = [
                            'rules'  => 'min_length[8]|max_length[255]',
                            'errors' => [
                                'min_length' => 'Минимальная длина поля - 8 символов.',
                                'max_length' => 'Максимальная длина поля - 255 символов.'
                            ]
                        ];
        
                    }
                }
                if($new_data){
                    foreach($new_data as $key => $value){
                        $rules[ 'specialization_name.' . $key ] = [
                            'rules'  => 'min_length[8]|max_length[255]',
                            'errors' => [
                                'min_length' => 'Минимальная длина поля - 8 символов.',
                                'max_length' => 'Максимальная длина поля - 255 символов.'
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
                        if($new_data&&!$this->check_array($new_data,$new_data)){
                            $data['validation'] ='<ul><li>Не дублируйте наименования специальностей!</li></ul>';
                            return json_encode($data);
                        }
                        else if(!$this->check_array($old_data,$old_data)){
                            $data['validation'] ='<ul><li>Не дублируйте наименования специальностей!</li></ul>';
                            return json_encode($data);
                        }
                        else if(($old_data&&$new_data)&&!$this->check_array($old_data,$new_data)){
                            $data['validation'] ='<ul><li>Не дублируйте наименования специальностей!</li></ul>';
                            return json_encode($data);
                        }
                        else{
                            if($old_data){
                                $array=$this->specialization_model->findAll();
                                $count_old=count($old_data);
                                foreach ($old_data as $key => $value) {
                                    if($this->specialization_model->where(['name'=>$value])->first()){
                                        $first=$this->specialization_model->where(['name'=>$value])->first();
                                        if($first['id']!=$key){
                                            $data['validation']='Запись с наименованием: "'.$value.'" уже есть!';
                                            return json_encode($data);
                                        }
                                    }
                                }
                                foreach ($old_data as $key => $value) {
                                    if($this->specialization_model->where(['id'=>$key])->first()){
                                        $first=$this->specialization_model->where(['id'=>$key])->first();
                                        $this->specialization_model->update($first['id'],['name'=>$value]);
                                    }
                                }
                                foreach ($array as $key => $item) {
                                    $not_ex_count=0;
                                    foreach ($old_data as $key => $value) {
                                        if($item['id']!=$key){
                                            $not_ex_count=$not_ex_count+1;
                                        }
                                        if($not_ex_count==$count_old){
                                            $this->specialization_model->where(['id'=>$item['id']])->delete();
                                        }
                                    } 
                                }
                            }
                            if($new_data){
                                foreach ($new_data as $key => $value) {
                                    if($this->specialization_model->where(['name'=>$value])->first()){
                                        $data['validation']='Запись с наименованием: "'.$value.'" уже есть!';
                                        return json_encode($data);
                                    }
                                }
                                foreach ($new_data as $key => $value) {
                                    $this->specialization_model->insert(['name'=>$value]);
                                }
                            }
                        }
                        return json_encode(true);
                    }
                }
                else{
                    $all_data=$this->specialization_model->findAll();
                    foreach ($all_data as $key => $value) {
                        $this->specialization_model->where(['name'=>$value['name']])->delete();
                    }
                    return json_encode(true);
                }
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