<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\PersonalDataModel;
use App\Models\GroupModel;
use App\Models\SpecializationModel;
use App\Models\GroupsSpecializationsModel;
use App\Models\SkillModel;

class GroupsSpecializationsCrud extends BaseController
{
    protected $group_model;
    protected $specialization_model;
    protected $user_model;
    protected $personal_data_model;
    protected $groups_specializations_model;
    protected $skill_model;

    public function __construct()
    {
        $this->user_model=new UserModel();//Экземпляр модели
        $this->personal_data_model=new PersonalDataModel(); //Экземпляр модели
        $this->group_model=new GroupModel();
        $this->specialization_model=new SpecializationModel();
        $this->groups_specializations_model= new GroupsSpecializationsModel();
        $this->skill_model=new SkillModel();
        helper('form', 'url','array'); // Подгрузка хелперов
    }

    public function get_all()
    {
        if(session('role')=='Администратор'){
            $data=[];
            $all_specializations=$this->specialization_model->findAll();
            if($all_specializations){
                foreach ($all_specializations as $key => $specialization) {
                    if($this->groups_specializations_model->where(['specialization_id'=>$specialization['id']])->first()){
                        $curator_info='Не назначен!';
                        $rows_groups=$this->groups_specializations_model->where(['specialization_id'=>$specialization['id']])->findAll();
                        $rows_skills=$this->skill_model->where(['specialization_id'=>$specialization['id']])->findAll();
                        if($specialization['user_id']){
                            $user_info=$this->user_model->where(['id'=>$specialization['user_id']])->first();
                            $personal_data=$this->personal_data_model->where(['user_id'=>$user_info['id']])->first();
                            $curator_info=$personal_data['last_name'].' '.$personal_data['first_name'].' '.$personal_data['middle_name'].' ('.$user_info['email'].')';
                        }
                        $groups='<ul>';
                        $skills='<ul>';
                        foreach ($rows_groups as $key => $row_item) {
                            $group=$this->group_model->where(['id'=>$row_item['group_id']])->first();
                            $groups=$groups.'<li>'.$group['name'].'</li>';
                        }
                        foreach ($rows_skills as $key => $row_item) {
                            $skills=$skills.'<li>'.$row_item['name'].'</li>';
                        }
                        $groups=$groups.'</ul>';
                        $skills=$skills.'</ul>';
                        $data[]=array(
                            $specialization['name'],
                            $curator_info,
                            $groups,
                            $skills,
                            '<button type="button" onclick="edit_open_specialization_main('.$specialization['id'].')" class="btn btn-outline-warning mx-1">Изменить</button>'.
                            '<button type="button" onclick="delete_open_specialization_main('.$specialization['id'].');" class="btn btn-outline-danger mx-1">Удалить</button>'
                        );
                    }
                }
                //print_r($data); ДЛЯ ТЕСТА
            }
            $result = array(
                "draw" => $this->request->getVar('draw'),
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data
            );
            echo json_encode($result);
        }
        
    }

    public function get()
    {
        if(session('role')=='Администратор'){
            $all_data=[];
            if($this->request->getMethod()=='post'){
                $specialization_data=$this->specialization_model->where(['id'=>$this->request->getVar('id')])->first();
                $groups_data=$this->groups_specializations_model->where(['specialization_id'=>$this->request->getVar('id')])->findAll();
                $skills_data=$this->skill_model->where(['specialization_id'=>$this->request->getVar('id')])->findAll();
                $all_data=[
                    'main' => $specialization_data,
                    'groups' => $groups_data,
                    'skills' => $skills_data
                ];
                return json_encode($all_data);
            }
        }

    }

    public function get_specialization_list()
    {
        return json_encode($this->specialization_model->findAll());
    }

    public function get_users_list()
    {
        if(session('role')=='Администратор'){
            $return_data=[];
            $users=$this->user_model->where('role','Куратор')->findAll();
            foreach ($users as $key => $item) {
                $info_user=$this->personal_data_model->where(['user_id'=>$item['id']])->first();
                // if($this->request->getVar('curret')){
                //     $item_new=[
                //         'id' =>$item['id'],
                //         'full_item' =>'('.$item['email'].') '.$info_user['last_name'].' '.$info_user['first_name'].' '.$info_user['middle_name']
                //     ];
                //     array_push($return_data,$item_new);
                // }
                if($this->specialization_model->where(['id'=>$this->request->getVar('curret'),'user_id'=>$info_user['user_id']])->first()){
                    $item_new=[
                        'id' =>$item['id'],
                        'full_item' =>'('.$info_user['user_id'].') '.$info_user['last_name'].' '.$info_user['first_name'].' '.$info_user['middle_name']
                    ];
                    array_push($return_data,$item_new);
                }
                
            }
            return json_encode($return_data);
        }
    }

    public function get_groups_list()
    {
        switch($this->request->getVar('type')){
            case 1:{
                return json_encode($this->group_model->findAll());
            }break;
            case 2:{
                if($this->request->getVar('specialization_id')){
                    $groups=[];
                    $spec=$this->request->getVar('specialization_id');
                    $valid=$this->groups_specializations_model->where('specialization_id',$spec)->findAll();
                    foreach ($valid as $key => $item) {
                        $group=$this->group_model->where('id',$item['group_id'])->first();
                        array_push($groups,$group);
                    }
                    return json_encode($groups);
                }
                else{
                    return json_encode(array());
                }
            }break;
        }
    }

    public function add()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'){
                $validation =  \Config\Services::validation();
                if($this->request->getVar('skills')){
                    $skills=$this->request->getVar('skills');
                }
                else{
                    $data['validation']='<ul><li>Укажите хотя бы один навык, который приобрели студенты обучающиеся на данной специальности!</li></ul>';
                    return json_encode($data);
                }
                if($this->request->getVar('groups')){
                    $groups=$this->request->getVar('groups');
                }
                else{
                    $data['validation']='<ul><li>Укажите хотя бы одну группу!</li></ul>';
                    return json_encode($data);
                }
                $rules=[];
                $rules = [
                    'specialization' => [
                        'rules'  => 'required',
                        'errors' => [
                            'required' => 'Укажите специальность!',
                        ]
                    ],
                    // 'curator'    => [
                    //     'rules'  => 'required',
                    //     'errors' => [
                    //         'required' => 'Установите пользователю роль кураторатора и укажите выбранную специальность!'
                    //     ]
                    // ],
                    'description'    => [
                        'rules'  => 'required|min_length[20]|max_length[500]',
                        'errors' => [
                            'required' => 'Укажите описание (напишите какие ЯП изучались и тд)!',
                            'min_length'=>'Минимальная длина поля описания специальности - 20 символов!',
                            'max_length'=>'Максимальная длина поля описания специальности - 500 символов!'
                        ]
                    ],
                ];
                foreach($skills as $key => $value){
                    $rules[ 'skills.' . $key ] = [
                        'rules'  => 'required|min_length[3]|max_length[255]',
                        'errors' => [
                            // 'is_unique' => 'В системе уже имеется информация о данном навыке обучающихся',
                            'required' => 'Заполните все поля навыков или удалите лишние',
                            'min_length' => 'Минимальная длина поля навыка - 3 символов.',
                            'max_length' => 'Максимальная длина поля навыка - 255 символов.'
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
                    if(!$this->check_array($skills,$skills)){
                        $data['validation'] ='<ul><li>Не дублируйте навыки!</li></ul>';
                        return json_encode($data);
                    }
                    else{
                        if($this->groups_specializations_model->where(['specialization_id'=>$this->request->getVar('specialization')])->first()){
                            $data['validation'] ='<ul><li>Данные связанные с указанной специальностью уже есть!</li></ul>';
                            return json_encode($data);
                        }
                        else{
                            foreach ($groups as $key => $value) {
                                if($this->groups_specializations_model->where(['group_id'=>$value])->first()){
                                    $data['validation'] ='<ul><li>У вас в списке есть группы, которые уже привязаны к другой специальности!</li></ul>';
                                    return json_encode($data);
                                }
                            }
                            foreach ($groups as $key => $value) {
                                $new_data=[
                                    'specialization_id'=>$this->request->getVar('specialization'),
                                    'group_id' =>$value
                                ];
                                $this->groups_specializations_model->insert($new_data);
                            }
                            foreach ($skills as $key => $value) {
                                $skill_data=[
                                    'specialization_id'=>$this->request->getVar('specialization'),
                                    'name' =>$value
                                ];
                                $this->skill_model->insert($skill_data);
                            }
                            $spec_data=[
                                'description'=> $this->request->getVar('description')
                            ];
                            $this->specialization_model->update($this->request->getVar('specialization'),$spec_data);
                            return json_encode(true);
                        }
                    }
                }
            }
            
        }
    }

    public function edit($id)
    {
        if($this->request->getMethod()=='post'){
            $validation =  \Config\Services::validation();
            if(session('role')=='Администратор'||session('role')=='Куратор'){
                $groups=null;
                $skills=null;
                if($this->request->getVar('skills')){
                    $skills=$this->request->getVar('skills');
                }
                else{
                    $data['validation']='<ul><li>Укажите наименование хотя бы один навык, который приобрели студенты обучающиеся на данной специальности!</li></ul>';
                    return json_encode($data);
                }
                if($this->request->getVar('groups')){
                    $groups=$this->request->getVar('groups');
                }
                else{
                    $data['validation']='<ul><li>Укажите хотя бы одну группу!</li></ul>';
                    return json_encode($data);
                }
                $rules=[];
                $rules = [
                    // 'curator'    => [
                    //     'rules'  => 'required',
                    //     'errors' => [
                    //         'required' => 'Установите пользователю роль кураторатора и укажите выбранную специальность!'
                    //     ]
                    // ],
                    'description'    => [
                        'rules'  => 'required|min_length[20]|max_length[500]',
                        'errors' => [
                            'required' => 'Укажите описание (напишите какия ЯП изучались и тд)!',
                            'min_length'=>'Минималная длина поля описания специальности - 20 символов!',
                            'max_length'=>'Максимальная длина поля описания специальности - 500 символов!'
                        ]
                    ],
                ];
                if(session('role')=='Куратор'){
                    unset($rules['curator']);
                    $rules['specialization_name'] = [
                        'rules'  => 'required|min_length[8]|max_length[255]',
                        'errors' => [
                            'required' => 'Укажите наименование специальности!',
                            'min_length' => 'Минимальная длина поля наименования специальности - 8 символов.',
                            'max_length' => 'Максимальная длина поля наименования специальности - 255 символов.',
                        ]
                    ];
                    if($this->specialization_model->where('name',$this->request->getVar('specialization_name'))->first()){
                        $check=$this->specialization_model->where('name',$this->request->getVar('specialization_name'))->first();
                        if($check&&$check['id']!=$id){
                            return json_encode(['validation'=>'Такое наименование уже занято!']);
                        }
                    }
                }
                foreach($skills as $key => $value){
                    $rules[ 'skills.' . $key ] = [
                        'rules'  => 'required|min_length[3]|max_length[255]',
                        'errors' => [
                            // 'is_unique' => 'В системе уже имеется информация о данном навыке обучающихся',
                            'required' => 'Заполните все поля навыков или удалите лишние',
                            'min_length' => 'Минимальная длина поля - 3 символов.',
                            'max_length' => 'Максимальная длина поля - 255 символов.'
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
                    if(!$this->check_array($skills,$skills)){
                        $data['validation'] ='<ul><li>Не дублируйте навыки!</li></ul>';
                        return json_encode($data);
                    }
                    else{
                        $this->groups_specializations_model->where(['specialization_id'=>$id])->delete();
                        $this->skill_model->where(['specialization_id'=>$id])->delete();
                        foreach ($groups as $key => $value) {
                            if($this->groups_specializations_model->where(['group_id'=>$value])->first()){
                                $row=$this->groups_specializations_model->where(['group_id'=>$value])->first();
                                if($row['specialization_id']!=$id){
                                    $data['validation'] ='<ul><li>У вас в списке есть группы, которые уже привязаны к другой специальности!</li></ul>';
                                    return json_encode($data);
                                }
                            }
                        }
                        foreach ($groups as $key => $value) {
                            $new_data=[
                                'specialization_id'=>$id,
                                'group_id' =>$value
                            ];
                            
                            $this->groups_specializations_model->insert($new_data);
                        }
                        foreach ($skills as $key => $value) {
                            $skill_data=[
                                'specialization_id'=>$id,
                                'name' =>$value
                            ];
                            $this->skill_model->insert($skill_data);
                        }
                        $spec_data=[
                            'description'=> $this->request->getVar('description')
                        ];
                        if(session('role')=='Куратор'){
                            unset($spec_data['user_id']);
                            $spec_data['name']=$this->request->getVar('specialization_name');
                        }
                        $this->specialization_model->update($id,$spec_data);
                        return json_encode(true);
                    }
                }
            }
            
        }
    }

    public function delete()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'){
                $id=$this->request->getVar('id');
                $this->groups_specializations_model->where(['specialization_id'=>$id])->delete();
                $this->skill_model->where(['specialization_id'=>$id])->delete();
                $data=[
                    'user_id'=>null
                ];
                if($this->specialization_model->update($id,$data)){
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