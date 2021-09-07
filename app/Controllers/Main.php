<?php

namespace App\Controllers;
use App\Models\PersonalDataModel;
use App\Models\OrganizationModel;
use App\Models\UserModel;
use App\Models\ResumeModel;
use App\Models\ResumeSkillModel;
use App\Models\SkillModel;
use App\Models\SpecializationModel;
use App\Models\GroupsSpecializationsModel;
use App\Models\FavoriteResumeModel;
use App\Models\StudentOrganizationModel;

class Main extends BaseController
{
    protected $personal_data_model;
    protected $organization_model;
    protected $user_model;
    protected $resume_model;
    protected $resume_skill_model;
    protected $skill_model;
    protected $specialization_model;
    protected $groups_specializations_model;
    protected $favorite_resume_model;
    protected $student_organization_model;
	//Создаём функцию конструктора, в которой подключим все необходимые библиотеки, хелперы.
	public function __construct()
    {
		$this->personal_data_model=new PersonalDataModel();
        $this->organization_model=new OrganizationModel();
        $this->user_model=new UserModel();
        $this->resume_model=new ResumeModel();
        $this->resume_skill_model=new ResumeSkillModel();
        $this->skill_model=new SkillModel();
        $this->groups_specializations_model= new GroupsSpecializationsModel();
        $this->specialization_model=new SpecializationModel();
        $this->favorite_resume_model=new FavoriteResumeModel();
        $this->student_organization_model=new StudentOrganizationModel();
        helper('form', 'url','array','filesystem'); // Подгрузка хелперов
    }

    public function index()
    {
        echo view("default/header",['title'=>'Главная']);
        if(session('role')=='Работодатель'){
            if($this->organization_model->where('user_id',session('id'))->first()){
                echo view("main/modal/edit_add");
                echo view("main/modal/delete");
            }            
        }
        else{
            echo view("main/modal/info_offer");
        }
        
        echo view("main/body");
        echo view("default/footer");
    }

    public function profile()
    {
        echo view('default/header',['title'=>'Профиль']);
        echo view('profile/body');
        echo view('default/footer');
    }

    public function favorite(){
        switch(session('role')){
            case 'Студент':{
                echo view('default/header',['title'=>'Избранные вакансии/предложения']);
                echo view('favorite/body');
                echo view("main/modal/info_offer");
                echo view('default/footer');
            }break;
            case 'Работодатель':{
                echo view('default/header',['title'=>'Избранные резюме']);
                $data['emp_favorite']=$this->favorite_resume_model->where('user_id',session('id'))->findAll();
                echo view('favorite/body',$data); 
                echo view('default/footer');  
                echo view('students/modal/portfolio_model');
                echo view('students/modal/resume_modal');
            }break;
        }
    }

    public function responds(){
        echo view('default/header',['title'=>'Отклики на вакансии']);
        switch(session('role')){
            case 'Студент':{
                echo view('responds/body');
                echo view('default/footer');
                echo view("main/modal/info_offer");
            }break;
            case 'Работодатель':{
                echo view('responds/body');
                echo view('default/footer');
                echo view('students/modal/portfolio_model');
                echo view('students/modal/resume_modal');
            }break;
        }
        
    }

    public function resume(){
        $data=[];
        if(session('resume_id')){
            $resume=$this->resume_model->where('id',session('resume_id'))->first();
            $resume_skills=$this->resume_skill_model->where('resume_id',session('resume_id'))->findAll();
            $data=[
                'resume' =>$resume,
                'skills' =>$resume_skills
            ];
        }
        echo view('default/header');
        echo view('resume/body',$data);
        echo view('default/footer');
    }

    public function portfolio(){
        echo view('default/header',['title'=>'Портфолио']);
        echo view('portfolio/body');
        echo view('default/footer');
    }

    public function students(){
        switch(session('role')){
            case 'Куратор':{
                echo view('default/header',['title'=>'Мои студенты']);
                echo view('students/body'); 
                echo view('default/footer');
                echo view('students/modal/portfolio_model');
                echo view('students/modal/resume_modal');
                echo view('students/modal/modal_send_students');  
                echo view('students/modal/delete_my_students');
                echo view('admin/spec_group/modal/group/cru_modal');
            }break;
            case 'Работодатель':{
                echo view('default/header',['title'=>'Соискатели']);
                $data['emp_favorite']=$this->favorite_resume_model->where('user_id',session('id'))->findAll();
                echo view('students/body',$data); 
                echo view('default/footer');
                echo view('students/modal/portfolio_model');
                echo view('students/modal/resume_modal');
            }break;
        }           
    }

    public function organizations(){
        echo view('default/header',['title'=>'Организации']);
        echo view('organizations/body');        
        echo view('default/footer');
        echo view('organizations/modal/info_org');
        echo view('organizations/modal/info_invited_modal');
    }

    public function specialization(){
        $skills=[];
        $groups=[];
        $spec=[];
        if(session('specialization_id')){
            $skills=$this->skill_model->where('specialization_id',session('specialization_id'))->findAll();
            $groups=$this->groups_specializations_model->where('specialization_id',session('specialization_id'))->findAll();
            $spec=$this->specialization_model->where('id',session('specialization_id'))->first();
            if($spec){
                $spec['name']=htmlspecialchars($spec['name']);
            }
        }
        $result=[
            'skills'=>$skills,
            'groups'=>$groups,
            'specialization'=>$spec
        ];
        echo view('default/header',['title'=>'Информация о специальности']);
        echo view('specialization/body',$result);
        echo view('default/footer');
    }

    public function invitations(){
        switch(session('role')){
            case 'Студент':{
                echo view('default/header',['title'=>'Приглашения']);
                echo view('invitations/body');
                echo view('default/footer');
                echo view('organizations/modal/info_org');
            }break;
            case 'Работодатель':{
                echo view('default/header',['title'=>'Приглашения и прикреплённые студенты']);
                echo view('invitations/body');
                echo view('default/footer');
                echo view('students/modal/portfolio_model');
                echo view('students/modal/resume_modal');
            }
        }
    }

    public function my_org(){
        $data=[];
        if(session('role')=='Студент'&&session('in_org')){
            $in_org=[
                'user_id'=>session('id'),
                'status'=>'Закреплён'
            ];
            if($this->student_organization_model->where($in_org)->first()){
                $org=$this->student_organization_model->where($in_org)->first();
                $organization_info=$this->organization_model->where('id',$org['organization_id'])->first();
                $user=$this->user_model->where('id',$organization_info['user_id'])->first();
                $pers=$this->personal_data_model->where('user_id',$organization_info['user_id'])->first();
                
                $row=array_merge($organization_info,$user,$pers);
                $row['org_photo']=base_url().'/writable/uploads/organizations/'.$user['id'].'/'.$organization_info['org_photo'];
                $data=$row;
            }
        }
        echo view('default/header',['title'=>'Закреплённая организация']);
        echo view('my_org/body',$data);
        echo view('default/footer');
    }

    public function admin(){
        echo view('default/header');
        echo view('admin/body');
        echo view('default/footer');
    }

    public function admin_users(){
        echo view('default/header',['title'=>'Управление пользователями']);
        echo view('admin/users/body');
        echo view('default/footer');
        echo view('admin/users/modal/modal_user_add_edit');
        echo view('admin/users/modal/modal_users_delete');
        echo view('admin/users/modal/modal_users_mass');
    }

    public function admin_specializations_and_groups(){
        echo view('default/header',['title'=>'Управление специальностями и учебными группами']);
        echo view('admin/spec_group/body');
        echo view('default/footer');
        echo view('admin/spec_group/modal/modal_list_main');
        echo view('admin/spec_group/modal/group/cru_modal');
        echo view('admin/spec_group/modal/specialization/cru_modal');
        echo view('admin/spec_group/modal/main/cru_modal');
        echo view('admin/spec_group/modal/main/delete_modal');
    }

    public function admin_orgs(){
        echo view('default/header',['title'=>'Управление организациями']);
        echo view('admin/organizations/body');
        echo view('default/footer');
        echo view('admin/organizations/modal/modal_org_add_edit');
        echo view('admin/organizations/modal/modal_org_delete');
        echo view('organizations/modal/info_org');
    }
}