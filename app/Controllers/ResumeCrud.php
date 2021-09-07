<?php
namespace App\Controllers;
use App\Models\SkillModel;
use App\Models\ResumeModel;
use App\Models\ResumeSkillModel;

 class ResumeCrud extends BaseController
{
    protected $skill_model;
    protected $resume_model;
    protected $resume_skill_model;

	public function __construct()
    {   
       $this->skill_model=new SkillModel();
       $this->resume_model=new ResumeModel();
       $this->resume_skill_model=new ResumeSkillModel();
        helper('form', 'url','array'); // Подгрузка хелперов
    }
    
    public function get_skill_list()
    {
        $spec_id=null;
        switch(session('role')){
            case 'Студент':{
                $spec_id=session('specialization_id');
            }break;
            
        }
        return json_encode($this->skill_model->where('specialization_id',$spec_id)->findAll());
    }

    public function add()
    {
        $validation =  \Config\Services::validation();
        if($this->request->getMethod()=="post"&&(session('role')=='Студент'||session('role')=='Куратор'||session('role')=='Администратор')){
            $user_id=null;
            if(session('role')=='Студент'){
                $user_id=session('id');           
            }
            else{
                $user_id=$this->request->getVar('user_id');   
            }
            $rules = [
                'about_me' => [
                    'rules'  => 'required|min_length[50]|max_length[500]',
                    'errors' => [
                        'required' => '"О себе" - обязательное поле!',
                        'min_length' => 'Поле "О себе" должно содержать не менее 50 символов!',
                        'max_length' => 'Поле "О себе" должно содержать не более 500 символов!'
                    ]
                ],
                'education' => [
                    'rules'  => 'required|min_length[3]|max_length[50]',
                    'errors' => [
                        'required' => '"Образование" - обязательное поле!',
                        'min_length' => 'Поле "Образование" должно содержать не менее 3 символов!',
                        'max_length' => 'Поле "Образование" должно содержать не более 50 символов!'
                    ]
                ],
                'work experience' => [
                    'rules'  => 'max_length[255]',
                    'errors' => [
                        'max_length' => 'Поле "Опыт работы" должно содержать не более 255 символов!'
                    ]
                ],
                'additionally'    => [
                    'rules'  => 'max_length[500]',
                    'errors' => [
                        'max_length' => 'Поле "Дополнительно" должно содержать не более 500 символов!',
                    ]
                ],
                'nearest_metro' => [
                    'rules'  => 'max_length[100]',
                    'errors' => [
                        'max_length' => 'Поле "Ближайжее метро" должно содержать не более 100 символов!',
                    ]
                ],
            ];
            //Если валидация не прошла
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
                if($this->request->getVar('skills')){
                    $skills=$this->request->getVar('skills');
                    $data=[
                        'about_me' =>$this->request->getVar('about_me'),
                        'education' =>$this->request->getVar('education'),
                        'work_experience' =>$this->request->getVar('work_experience'),
                        'additionally' =>$this->request->getVar('additionally'),
                        'nearest_metro' =>$this->request->getVar('nearest_metro'),
                        'user_id'=>$user_id
                    ];
                    if($this->resume_model->insert($data)){
                        $resume=$this->resume_model->where('user_id',$user_id)->first();
                        foreach ($skills as $key => $value) {
                            $skill=[
                                'resume_id'=>$resume['id'],
                                'skill_id' => $value
                            ];
                            $this->resume_skill_model->insert($skill);
                        }
                        return json_encode(true);
                    }
                }
                else{
                    $data['validation'] = '<ul><li>Укажите хотя бы один навык/умение!</li></ul>';
                    return json_encode($data);
                }
            }
        }
    }

    public function edit()
    {
        $validation =  \Config\Services::validation();
        if($this->request->getMethod()=="post"&&(session('role')=='Студент'||session('role')=='Куратор'||session('role')=='Администратор')){
            $resume_id=null;
            $user_id=null;
            if(session('role')=='Студент'){
                $resume_id=session('resume_id');        
                $user_id=session('id');     
            }
            else{
                $resume_id=$this->request->getVar('resume_id');   
                $user_id=$this->request->getVar('user_id');   
            }
            $rules = [
                'about_me' => [
                    'rules'  => 'required|min_length[50]|max_length[500]',
                    'errors' => [
                        'required' => '"О себе" - обязательное поле!',
                        'min_length' => 'Поле "О себе" должно содержать не менее 50 символов!',
                        'max_length' => 'Поле "О себе" должно содержать не более 500 символов!'
                    ]
                ],
                'education' => [
                    'rules'  => 'required|min_length[3]|max_length[50]',
                    'errors' => [
                        'required' => '"Образование" - обязательное поле!',
                        'min_length' => 'Поле "Образование" должно содержать не менее 3 символов!',
                        'max_length' => 'Поле "Образование" должно содержать не более 50 символов!'
                    ]
                ],
                'work experience' => [
                    'rules'  => 'max_length[255]',
                    'errors' => [
                        'max_length' => 'Поле "Опыт работы" должно содержать не более 255 символов!'
                    ]
                ],
                'additionally'    => [
                    'rules'  => 'max_length[500]',
                    'errors' => [
                        'max_length' => 'Поле "Дополнительно" должно содержать не более 500 символов!',
                    ]
                ],
                'nearest_metro' => [
                    'rules'  => 'max_length[100]',
                    'errors' => [
                        'max_length' => 'Поле "Ближайжее метро" должно содержать не более 100 символов!',
                    ]
                ],
            ];
            //Если валидация не прошла
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
                if($this->request->getVar('skills')){
                    $skills=$this->request->getVar('skills');
                    $data=[
                        'about_me' =>$this->request->getVar('about_me'),
                        'education' =>$this->request->getVar('education'),
                        'work_experience' =>$this->request->getVar('work_experience'),
                        'additionally' =>$this->request->getVar('additionally'),
                        'nearest_metro' =>$this->request->getVar('nearest_metro'),
                        'user_id'=>$user_id
                    ];
                    if($this->resume_model->update($resume_id,$data)){
                        $this->resume_skill_model->where('resume_id',$resume_id)->delete();
                        foreach ($skills as $key => $value) {
                            $skill=[
                                'resume_id'=>$resume_id,
                                'skill_id' => $value
                            ];
                            $this->resume_skill_model->insert($skill);
                        }
                        return json_encode(true);
                    }
                }
                else{
                    $data['validation'] = '<ul><li>Укажите хотя бы один навык/умение!</li></ul>';
                    return json_encode($data);
                }
            }
        }
        
    }

    public function delete()
    {
        if($this->request->getMethod()=="post"&&(session('role')=='Студент'||session('role')=='Куратор'||session('role')=='Администратор')){
            $resume_id=null;
            if(session('role')=='Студент'){
                $resume_id=session('resume_id');           
            }
            else{
                $resume_id=$this->request->getVar('resume_id');   
            }
            if($this->resume_model->where('id',$resume_id)->delete()&&$this->resume_skill_model->where('resume_id',$resume_id)->delete()){
                return json_encode(true);
            }
        }
    }
}