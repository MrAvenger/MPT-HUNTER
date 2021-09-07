<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;
use App\Models\PersonalDataModel;
use App\Models\OrganizationModel;
use App\Models\ResumeModel;
use App\Models\SpecializationModel;
use App\Models\StudentOrganizationModel;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user_model=new UserModel();
        $pers_model=new PersonalDataModel();
        $org_model=new OrganizationModel();
        $resume_model=new ResumeModel();
        $specialization_model=new SpecializationModel();
        $student_organization_model=new StudentOrganizationModel();
        $unset_stud=['org_name', 'org_adress', 'org_description', 'org_photo','org_id','post','user_id','password','verification_code','created_at','updated_at','deleted_at'];
        $unset_kurator=['org_name', 'org_adress', 'org_description', 'org_photo','org_id','specialization_id','resume_id','group_id','post'];
        $unset_employer=['resume_id', 'group_id', 'specialization_id','user_id','password','verification_code','created_at','updated_at','deleted_at'];
        $unset_admin=['org_name', 'org_adress', 'org_description', 'org_photo','org_id','specialization_id','resume_id','group_id','post','user_id','password','verification_code','created_at','updated_at','deleted_at'];
        $user_info=$user_model->where('id',session('id'))->first();
        $uri = $request->uri;
        if($user_info){
            $pers_info=$pers_model->where('user_id',$user_info['id'])->first();
            if($pers_info['date_birth']){
                $pers_info['date_birth']=date('d.m.Y',strtotime($pers_info['date_birth']));
            }
            switch($user_info['role']){
                case 'Студент':{
                    if($uri->getSegment(1)=='organization'||$uri->getSegment(1)=='admin'||$uri->getSegment(1)=='students'||$uri->getSegment(1)=='specialization'||$uri->getSegment(1)=='organizations'){
                        return redirect()->to(site_url('profile'));
                    }
                    $result=array_merge($user_info,$pers_info);
                    session()->remove($unset_stud);
                    foreach ($unset_stud as $key => $value) {
                        unset($result[$value]);
                    }
                    $in_org=[
                        'user_id'=>session('id'),
                        'status'=>'Закреплён'
                    ];
                    if($resume_model->where('user_id',$result['id'])->first()){
                        $resume=$resume_model->where('user_id',$result['id'])->first();
                        $result['resume_id']=$resume['id'];
                    }
                    if($student_organization_model->where($in_org)->first()){
                        $result['in_org']=true;
                    }
                    else{
                        $result['in_org']=false;
                    }
                    session()->set($result);

                }break;
                case 'Работодатель':{
                    if($uri->getSegment(1)=='resume'||$uri->getSegment(1)=='admin'||$uri->getSegment(1)=='portfolio'||$uri->getSegment(1)=='specialization'||$uri->getSegment(1)=='organizations'){
                        return redirect()->to(site_url('profile'));
                    }
                    $result=array_merge($user_info,$pers_info);
                    session()->remove($unset_employer);
                    if($org_model->where('user_id',$user_info['id'])->first()){
                        $org_data=$org_model->where('user_id',$user_info['id'])->first();
                        $org_data['org_id']=$org_data['id'];
                        unset($org_data['id']);
                        $result=array_merge($result,$org_data);
                    }
                    foreach ($unset_employer as $key => $value) {
                        unset($result[$value]);
                    }
                    session()->set($result);
                }break;
                case 'Куратор':{
                    if($uri->getSegment(1)=='resume'||$uri->getSegment(1)=='admin'||$uri->getSegment(1)=='portfolio'||$uri->getSegment(1)=='organization'||$uri->getSegment(1)=='main'||$uri->getSegment(1)=='favorite'||$uri->getSegment(1)=='responds'){
                        return redirect()->to(site_url('profile'));
                    }
                    $result=array_merge($user_info,$pers_info);
                    foreach ($unset_kurator as $key => $value) {
                        unset($result[$value]);
                    }
                    session()->remove($unset_kurator);
                    if($specialization_model->where('user_id',$user_info['id'])->first()){
                        $specialization_data=$specialization_model->where('user_id',$user_info['id'])->first();
                        $specialization_data['specialization_id']=$specialization_data['id'];
                        unset($specialization_data['user_id'],$specialization_data['description'],$specialization_data['id']);
                        $result=array_merge($result,$specialization_data);
                    }
                    session()->set($result);
                }break;
                case 'Администратор':{
                    if($uri->getSegment(1)=='resume'||$uri->getSegment(1)=='portfolio'||$uri->getSegment(1)=='organization'||$uri->getSegment(1)=='main'||$uri->getSegment(1)=='favorite'||$uri->getSegment(1)=='responds'||$uri->getSegment(1)=='specialization'||$uri->getSegment(1)=='students'||$uri->getSegment(1)=='organizations'){
                        return redirect()->to(site_url('admin'));
                    }
                    $result=array_merge($user_info,$pers_info);
                    foreach ($unset_admin as $key => $value) {
                        unset($result[$value]);
                    }
                    session()->remove($unset_admin);
                    session()->set($result);
                }break;
            }
        }
        else{
            return redirect()->to(site_url('/logout'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}