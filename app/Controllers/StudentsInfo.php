<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\PersonalDataModel;
use App\Models\GroupsSpecializationsModel;
use App\Models\GroupModel;
use App\Models\ResumeModel;
use App\Models\PortfolioModel;
use App\Models\SkillModel;
use App\Models\ResumeSkillModel;
use App\Models\SpecializationModel;
use App\Models\StudentOfferModel;
use App\Models\JobOfferModel;
use App\Models\OrganizationModel;
use App\Models\FavoriteResumeModel;
use App\Models\StudentOrganizationModel;

class StudentsInfo extends BaseController
{
    protected $user_model;
    protected $personal_data_model;
    protected $groups_specializations_model;
    protected $group_model;
    protected $resume_model;
    protected $portfolio_model;
    protected $skill_model;
    protected $resume_skill_model;
    protected $specialization_model;
    protected $student_offer_model;
    protected $job_offer_model;
    protected $organization_model;
    protected $student_organization_model;
    protected $favorite_resume_model;


    public function __construct()
    {
        $this->user_model=new UserModel();//Экземпляр модели
        $this->personal_data_model=new PersonalDataModel(); //Экземпляр модели
        $this->groups_specializations_model= new GroupsSpecializationsModel(); //Экземпляр модели
        $this->group_model= new GroupModel(); //Экземпляр модели
        $this->resume_model= new ResumeModel(); //Экземпляр модели
        $this->portfolio_model= new PortfolioModel(); //Экземпляр модели
        $this->skill_model=new SkillModel();
        $this->resume_skill_model= new ResumeSkillModel();
        $this->specialization_model=new SpecializationModel();
        $this->student_offer_model=new StudentOfferModel();
        $this->job_offer_model=new JobOfferModel();
        $this->organization_model=new OrganizationModel();
        $this->student_organization_model=new StudentOrganizationModel();
        $this->favorite_resume_model=new FavoriteResumeModel();

        helper('form', 'url','array'); // Подгрузка хелперов
    }

    public function get_all_info()
    {
        $users=$this->user_model->where('role','Студент')->findAll();
        $groups=[];
        $users_data=[];
        $groups_data=[];
        switch(session('role')){
            case 'Куратор':{
                $groups=$this->groups_specializations_model->where('specialization_id',session('specialization_id'))->findAll();
                foreach ($users as $key => $user) {
                    $item=[];
                    $pers_data=$this->personal_data_model->where('user_id',$user['id'])->first();
                    $resume_info=$this->resume_model->where('user_id',$user['id'])->first();
                    $item=array_merge($user,$pers_data);
                    if($resume_info){
                        $item['average_score']=$resume_info['average_score'];
                    }
                    else{
                        $item['average_score']=null;
                    }
                    $unset_keys=[
                        'user_id',
                        'password',
                        'created_at',
                        'updated_at',
                        'verification_code',
                        'active'
                    ];
                    if($item['photo']){
                        $item['photo']=base_url().'/writable/uploads/profile/'.$item['id'].'/'.$item['photo'];
                    }
                    else{
                        $item['photo']=base_url().'/assets/img/design/default.jpg';
                    }
                    foreach ($unset_keys as $key => $value) {
                        unset($item[$value]);
                    }

                    array_push($users_data,$item);
                }
                foreach ($groups as $key => $group) {
                    $group_info=$this->group_model->where('id',$group['group_id'])->first();
                    array_push($groups_data,$group_info);
                }
                if(session('role')=="Куратор"){
                    $result=[
                        'groups'=>$groups_data,
                        'students'=>$users_data
                    ];
                }
                return json_encode($result);
            }break;
            case 'Работодатель':{
                $specializations=$this->specialization_model->findAll();
                $result_specializations_data=[];
                foreach ($users as $key => $user) {
                    $good_resume=false;
                    $item=[];
                    $pers_data=$this->personal_data_model->where('user_id',$user['id'])->first();
                    $resume_info=$this->resume_model->where('user_id',$user['id'])->first();
                    $item=array_merge($user,$pers_data);
                    if($resume_info){
                        $item['average_score']=$resume_info['average_score'];
                    }
                    else{
                        $item['average_score']=null;
                    }
                    $unset_keys=[
                        'user_id',
                        'password',
                        'created_at',
                        'updated_at',
                        'verification_code',
                        'active'
                    ];
                    if($item['photo']){
                        $item['photo']=base_url().'/writable/uploads/profile/'.$item['id'].'/'.$item['photo'];
                    }
                    else{
                        $item['photo']=base_url().'/assets/img/design/default.jpg';
                    }
                    $emploer=$this->student_organization_model->where('user_id',$item['id'])->findAll();
                    if($emploer){
                        $item['emploer']=$emploer;
                    }
                    else{
                        $item['emploer']=[];
                    }
                    if($this->resume_model->where('user_id',$item['id'])->first()){
                        $resume=$this->resume_model->where('user_id',$item['id'])->first();
                        $item['resume_id']=$resume['id'];
                        if($resume['about_me']&&$resume['education']){
                            $good_resume=true;
                            $data_where=[
                                'user_id'=>session('id'),
                                'resume_id'=>$resume['id']
                            ];
                            if($this->favorite_resume_model->where($data_where)->first()){
                                $item['favorite_resume']=true;
                            }
                            else{
                                $item['favorite_resume']=false;
                            }

                        }
                    }
                    else{
                        $item['resume_id']=null;
                    }
                    foreach ($unset_keys as $key => $value) {
                        unset($item[$value]);
                    }
                    if($good_resume){
                        array_push($users_data,$item);
                        
                    }
                }
                foreach ($specializations as $key => $specialization) {
                    $favorite_exists=false;
                    $invitation_exists=false;
                    $pers_with_spec=$this->personal_data_model->where('specialization_id',$specialization['id'])->findAll();
                    foreach ($pers_with_spec as $key => $pers) {
                        $resume_cur=$this->resume_model->where('user_id',$pers['user_id'])->first();
                        if($resume_cur){
                            $data_favorite=[
                                'resume_id' =>$resume_cur['id'],
                                'user_id'=>session('id')
                            ];
                            $data_invite=[
                                'organization_id'=>session('org_id'),
                                'status'=>'Приглашён'
                            ];
                            $data_adder_to_org=[
                                'organization_id'=>session('org_id'),
                                'status'=>'Закреплён'
                            ];
                            if($this->favorite_resume_model->where($data_favorite)->first()){
                                $favorite_exists=true;
                            }
                            if($this->student_organization_model->where($data_invite)->first()){
                                $invite=$this->student_organization_model->where($data_invite)->first();
                                $invitation_exists=true;
                            }
                            else if($this->student_organization_model->where($data_adder_to_org)->first()){
                                $invitation_exists=true;
                            }
                        }
                    }
                    $specialization['favorite_exists']=$favorite_exists;
                    $specialization['invitation_exists']=$invitation_exists;
                    array_push($result_specializations_data,$specialization);
                }
                $result=[
                    'specializations'=>$result_specializations_data,
                    'students'=>$users_data
                ];
                return json_encode($result);
            }break;
        }
    }

    public function get_portfolio()
    {
        $switch=$this->request->getVar('switch');
        $user_id=$this->request->getVar('user_id');
        switch(session('role')){
            case 'Куратор':{
                $groups=$this->request->getVar('groups');
                $portfolio=[];
                if($this->portfolio_model->where('user_id',$user_id)->first()){
                    $data=$this->portfolio_model->where('user_id',$user_id)->findAll();
                    foreach ($data as $key => $item) {
                        $pers=$this->personal_data_model->where('user_id',$item['user_id'])->first();
                        foreach ($groups as $key => $group) {
                            if($pers['group_id']==$group['id']){
                                $item['url']=base_url().'/PortfolioCrud/download/'.$item['id'];
                                array_push($portfolio,$item);
                            }
                        }
                    }
                    return json_encode($portfolio);
                }
                else{
                    return json_encode($portfolio);
                }
            }break;
            case 'Работодатель':{
                $portfolio=[];
                if($this->portfolio_model->where('user_id',$user_id)->first()){
                    $data=$this->portfolio_model->where('user_id',$user_id)->findAll();
                    foreach ($data as $key => $item) {
                        $pers=$this->personal_data_model->where('user_id',$item['user_id'])->first();
                        $item['url']=base_url().'/PortfolioCrud/download/'.$item['id'];
                        array_push($portfolio,$item);
                    }
                    return json_encode($portfolio);
                }
                else{
                    return json_encode($portfolio);
                }
            }break;
            case 'Администратор':{
                $portfolio=[];
                if($this->portfolio_model->where('user_id',$user_id)->first()){
                    $data=$this->portfolio_model->where('user_id',$user_id)->findAll();
                    foreach ($data as $key => $item) {
                        $pers=$this->personal_data_model->where('user_id',$item['user_id'])->first();
                        $item['url']=base_url().'/PortfolioCrud/download/'.$item['id'];
                        array_push($portfolio,$item);
                    }
                    return json_encode($portfolio);
                }
                else{
                    return json_encode($portfolio);
                }
            }break;
        }
    }

    public function get_resume()
    {
        
        $switch=$this->request->getVar('switch');
        $user_id=$this->request->getVar('user_id');
        $groups=$this->request->getVar('groups');
        switch(session('role')){
            case 'Куратор':{
                $resume=[];
                if($this->resume_model->where('user_id',$user_id)->first()){
                    $data=$this->resume_model->where('user_id',$user_id)->first();
                    $pers=$this->personal_data_model->where('user_id',$data['user_id'])->first();
                    foreach ($groups as $key => $group) {
                        if($pers['group_id']==$group['id']){
                            $skills_list=$this->resume_skill_model->where('resume_id',$data['id'])->findAll();
                            $skills=[];
                            foreach ($skills_list as $key => $skill) {
                                $item=$this->skill_model->where('id',$skill['skill_id'])->first();
                                array_push($skills,$item);
                            }
                            $data['skills']=$skills;
                            array_push($resume,$data);
                        }
                    }
                    return json_encode($resume);
                }
                else{
                    return json_encode($resume);
                }
            }break;
            case 'Работодатель':{
                $resume=[];
                if($this->resume_model->where('user_id',$user_id)->first()){
                    $data=$this->resume_model->where('user_id',$user_id)->first();
                    $pers=$this->personal_data_model->where('user_id',$data['user_id'])->first();
                    $skills_list=$this->resume_skill_model->where('resume_id',$data['id'])->findAll();
                    $skills=[];
                    foreach ($skills_list as $key => $skill) {
                        $item=$this->skill_model->where('id',$skill['skill_id'])->first();
                        array_push($skills,$item);
                    }
                    $where_request=[
                        'user_id'=>$user_id,
                        'organization_id'=>session('org_id')
                    ];
                    $emploer=$this->student_organization_model->where('user_id',$user_id)->findAll();
                    if($emploer){
                        $data['emploer']=$emploer;
                    }
                    else{
                        $data['emploer']=null;
                    }
                    $where_favorite=[
                        'user_id'=>session('id'),
                        'resume_id'=>$data['id']
                    ];
                    if($this->favorite_resume_model->where($where_favorite)->first()){
                        $data['is_favorite']=true;
                    }
                    else{
                        $data['is_favorite']=false;
                    }
                    $data['skills']=$skills;
                    array_push($resume,$data);
                    return json_encode($resume);
                }
                else{
                    return json_encode($resume);
                }
            }break;
            case 'Администратор':{

            }break;
        }
    }

    public function get_offers_responds()
    {
        if(session('role')=='Работодатель'){
            $offers=$this->job_offer_model->where('org_id',session('org_id'))->findAll();
            $all_offers=[];
            $all_data=[];
            $students=[];
            foreach ($offers as $key => $offer) {            
                $data_where=[
                    'offer_id'=>$offer['id'],
                    'is_respond'=>1
                ];
                $responds=$this->student_offer_model->where($data_where)->findAll();
                if($responds){
                    foreach ($responds as $key => $respond) {
                        $user=$this->user_model->where('id',$respond['user_id'])->first();
                        $pers=$this->personal_data_model->where('user_id',$user['id'])->first();
                        unset($pers['user_id']);                    
                        $resume=$this->resume_model->where('user_id',$user['id'])->first();
                        if($resume&&$resume['about_me']&&$resume['education']&&$resume['average_score']){
                            if($pers['photo']){
                                $pers['photo']=base_url().'/writable/uploads/profile/'.$user['id'].'/'.$pers['photo'];                        
                            }
                            else{
                                $pers['photo']=base_url().'/assets/img/design/default.jpg';                       
                            }
                            unset($resume['id'],$resume['user_id']);
                            $student=array_merge($user,$pers);
                            $student=array_merge($student,$resume);
                            $student['offer_id']=$respond['offer_id'];
                            array_push($students,$student);
                        }
                        
                    }
                    $offer=$this->job_offer_model->where('id',$offer['id'])->first();
                    $data=[
                        'offer'=>$offer,
                        'students'=>$students
                    ];
                    array_push($all_offers,$offer);
                }

            }
            $all_data=[
                'offers'=>$offers,
                'students'=>$students
            ];
            return json_encode($all_data);
        }
    }

    public function update_score()
    {
        if($this->request->getMethod()=="post"){
            $validation =  \Config\Services::validation();
            $score_list=[];
            if(session('role')=='Куратор'||session('role')=='Администратор'){
                if($this->request->getVar('score')){
                    foreach($this->request->getVar('score') as $key => $item){
                        $score_list=$this->request->getVar('score');
                        $rules[ 'score.' . $key ] = [
                            'rules'  => 'required|less_than[5.01]|greater_than[2.99]',
                            'errors' => [
                                'required' => 'Заполните все поля средних баллов для студентов данной группы!',
                                'less_than' => 'Максимальная средний бал для учащегося - 5',
                                'greater_than' => 'Минимальный средний бал для учащегося - 3'
                            ]
                        ];
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
                        foreach ($score_list as $key => $item) {
                            $old=$this->resume_model->where('user_id',$key)->first();
                            if($old){
                                $new_data=[
                                    'average_score'=>$item
                                ];
                                $this->resume_model->update($old['id'],$new_data);
                            }
                            else{
                                $new_data=[
                                    'about_me'=>'',
                                    'average_score'=>$item,
                                    'education'=>'',
                                    'work_experience'=>'',
                                    'additionally'=>'',
                                    'nearest_metro'=>'',
                                    'user_id'=>$key
                                ];
                                $this->resume_model->insert($new_data);
                            }
                        }
                        return json_encode(true);
                    }
                }
            }
            else{
                return json_encode(false);
            }
        }
    }

    public function get_students()
    {
        if(session('role')=='Куратор'||session('role')=='Администратор'||session('role')=='Работодатель'){
            $result=[];
            $users=$this->user_model->where('role','Студент')->findAll();
            foreach ($users as $key => $item) {
                $group=[];
                $info=$this->personal_data_model->where('user_id',$item['id'])->first();
                unset($info['user_id'],$info['post'],$info['date_birth'],$info['number_phone'],$info['photo'],$info['sex']);
                unset($item['password'],$item['verification_code'],$item['active'],$item['created_at'],$item['updated_at'],$item['deleted_at'],$item['role']);
                if($info['group_id']){
                    $group=$this->group_model->where('id',$info['group_id'])->first();
                    $group['group_name']=$group['name'];
                    unset($group['id'],$group['name']);
                }
                $row=array_merge($item,$info);
                $row=array_merge($row,$group);
                array_push($result,$row);
            }
            return json_encode($result);
        }
    }

    public function transfer_students()
    {
        $validation =  \Config\Services::validation();
        if(session('role')=="Куратор"||session('role')=="Администратор"){
            $rules = [
                'students' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'Укажите студента/ов!'
                    ]
                ],
                'specialization' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'Укажите специальность!'
                    ]
                ],
                'groups' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'Укажите группу'
                    ]
                ],
            ];
            if (!$this->validate($rules)){
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
                $exists=[];
                $students=$this->request->getVar('students');
                foreach ($students as $key => $value) {
                    $old=$this->personal_data_model->where('user_id',$value)->first();
                    if($old['specialization_id']&&($old['specialization_id']!=session('specialization_id'))){
                        return json_encode(['validation'=>'<ul><li>Вы не можете перенаправлять студентов другой специальности!</li></ul>']);
                    }
                    if($old['group_id']==$this->request->getVar('groups')){
                        $err='<ul><li>Студент "'.$old['last_name'].' '.$old['first_name'].' '.$old['middle_name'].'" уже в данной группе!</li></ul>';
                        array_push($exists,$err);
                    }
                }  
                if($exists){
                    return json_encode(['validation'=>$exists]);
                }
                foreach ($students as $key => $value) {
                    $old=$this->personal_data_model->where('user_id',$value)->first();
                    if(($old['specialization_id']||!$old['specialization_id'])&&($old['group_id']||!$old['group_id'])){
                        $data=[
                            'specialization_id'=>$this->request->getVar('specialization'),
                            'group_id'=>$this->request->getVar('groups')
                        ];
                        $this->personal_data_model->update($value,$data);
                    }
                } 
                return json_encode(true);
            }
        }
    }

    public function student_org_set_status()
    {
        if(session('role')=='Работодатель'){
            $data_where=[
                'user_id'=>$this->request->getVar('id'),
                'organization_id'=>session('org_id')
            ];
            $status_info=$this->student_organization_model->where($data_where)->first();
            if($status_info){
                switch($status_info['status']){
                    case 'Приглашён':{
                        $in_org_where=[
                            'user_id'=>$this->request->getVar('id'),
                            'status'=>'Закреплён'
                        ];
                        if($this->student_organization_model->where($in_org_where)->first()){
                            $error=[
                                'error'=>'Соискатель уже прикреплён к другой организации!'
                            ];
                            return json_encode($error);
                        }
                        else{
                            $new_data=[
                                'status'=>'Закреплён'
                            ];
                            $old_data=[
                                'status'=>'Приглашён',
                                'user_id'=>$this->request->getVar('id')
                            ];
                            $f_resume=$this->resume_model->where('user_id',$status_info['user_id'])->first();
                            $this->favorite_resume_model->where('resume_id',$f_resume['id'])->delete();
                            $this->student_organization_model->update($status_info['id'],$new_data);
                            $this->student_organization_model->where($old_data)->delete();
                            $this->student_offer_model->where('user_id',$this->request->getVar('id'))->delete();
                            return json_encode(true);
                        }
                    }break;
                    case 'Закреплён':{
                        $data=[
                            'organization_id'=>session('org_id'),
                            'user_id'=>$this->request->getVar('id'),
                        ];
                        $this->student_organization_model->where($data)->delete();
                        return json_encode(true);
    
                    }break;
                    // default:{
                    //     json_encode('true');
                    // }break
                }
            }
            else{
                //$data_where
                $offers_student=$this->student_offer_model->where('user_id',$this->request->getVar('id'))->findAll();
                if($offers_student){
                    foreach ($offers_student as $key => $offer) {
                        $offer_info=$this->job_offer_model->where('id',$offer['offer_id'])->first();
                        if($offer_info['org_id']==session('org_id')){
                            $delete=[
                                'user_id'=>$this->request->getVar('id'),
                                'offer_id'=>$offer_info['id'],
                                'is_respond'=>1,
                            ];
                            $offers_student=$this->student_offer_model->where($delete)->delete();
                        }
                    }
                }
                $f_resume=$this->resume_model->where('user_id',$this->request->getVar('id'))->first();
                if($f_resume){
                    $this->favorite_resume_model->where('resume_id',$f_resume['id'])->delete();
                }                
                $new_data=[
                    'organization_id'=>session('org_id'),
                    'user_id'=>$this->request->getVar('id'),
                    'status'=>'Приглашён'
                ];
                $this->student_organization_model->insert($new_data);
                return json_encode(true);
            }
        }
    }

    public function add_favorite_resume()
    {
        if(session('role')=='Работодатель'){
            $resume=$this->resume_model->where('user_id',$this->request->getVar('id'))->first();
            if($resume){
                $data_where=[
                    'user_id'=>session('id'),
                    'resume_id'=>$resume['id']
                ];
                $info=$this->favorite_resume_model->where($data_where)->first();
                if($info){
                    $this->favorite_resume_model->where($data_where)->delete();
                    return json_encode(true);
                }
                else{
                    $this->favorite_resume_model->insert($data_where);
                    return json_encode(true);
                }
            }
        }
    }

    public function delete_invite()
    {
        if(session('role')=='Работодатель'){
            $delete=[
                'user_id'=>$this->request->getVar('id'),
                'organization_id'=>session('org_id')
            ];
            $this->student_organization_model->where($delete)->delete();
            return json_encode(true);
        }
    }

    public function student_expulsions()
    {
        if(session('role')=='Куратор'){
            $students=$this->request->getVar('students_delete');
            if($students){
                foreach ($students as $key => $value) {
                    $where=[
                        'user_id'=>$value,
                        'specialization_id'=>session('specialization_id')
                    ];
                    if($this->personal_data_model->where($where)->first()){
                        $update=[
                            'specialization_id'=>null,
                            'group_id'=>null
                        ];
                        $this->personal_data_model->update($value,$update);
                    }
                }
                return json_encode(true);
            }
            else{
                return json_encode(['validation'=>'<ul><li>Укажите студентов!</li></ul>']);
            } 
        }
        
    }

    public function invited_students()
    {
        if(session('role')=='Куратор'){
            $all_data=[];
            $org_id=$this->request->getVar('id');
            $where=[
                'organization_id'=>$org_id
            ];
            $students=$this->student_organization_model->where($where)->findAll();
            if($students){
                foreach ($students as $key => $student) {
                    $info=$this->personal_data_model->where('user_id',$student['user_id'])->first();
                    if($info['specialization_id']==session('specialization_id')){
                        $row=$info;
                        $group=$this->group_model->where('id',$info['group_id'])->first();
                        $group['group_name']=$group['name'];
                        unset($group['id'],$group['name']);
                        $row=array_merge($row,$group);
                        array_push($all_data,$row);
                    }
                }
            }
            return json_encode($all_data);
        }
    }

    public function get_invitions()
    {        
        if(session('role')=='Студент'){
            $all_data=[];
            $orgs=$this->student_organization_model->where('user_id',session('id'))->findAll();
            foreach ($orgs as $key => $org) {
                $item=null;
                if($this->request->getVar('search')){
                    $search=$this->request->getVar('search');
                    $item=$this->organization_model->where("org_name LIKE '%".$search."%' and id=".$org['organization_id'])->first();
                }
                else{
                    $item=$this->organization_model->where('id',$org['organization_id'])->first();
                }
                if($item){
                    unset($org['id'],$org['organization_id']);
                    $row=array_merge($org,$item);
                    $row['org_photo']=base_url().'/writable/uploads/organizations/'.$row['user_id'].'/'.$row['org_photo'];
                    array_push($all_data,$row);
                }
            }
            return json_encode($all_data);
        }
    }
}