<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\PortfolioModel;

 class PortfolioCrud extends BaseController
{
    protected $user_model;
    protected $portfolio_model;

	public function __construct()
    {  
       $this->user_model=new UserModel(); 
       $this->portfolio_model=new PortfolioModel();
        helper('form', 'url','array','filesystem'); // Подгрузка хелперов
    }

    public function get_all()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'||session('role')=='Студент'||session('role')=='Куратор'||session('role')=='Работодатель'){
                $user_id=null;
                if(session('role')=='Студент'){
                    $user_id=session('id');           
                }
                else{
                    $user_id=$this->request->getVar('user_id');   
                }
                $data=$this->portfolio_model->where('user_id',$user_id)->findAll();
                $result=[];
                foreach ($data as $key => $item) {
                    $user=$this->user_model->where('id',$user_id)->first();
                    $new_item=[
                        'url'=>base_url().'/PortfolioCrud/download/'.$item['id'],
                        'filename'=>$item['filename'],
                        'description'=>$item['description'],
                        'id'=>$item['id']
                    ];
                    array_push($result,$new_item);
                }
                return json_encode($result);
            }
        }
    }

    public function add()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'||session('role')=='Студент'||session('role')=='Куратор'){
                $one=false;
                $multy=false;
                $validation =  \Config\Services::validation();
                $user_id=null;
                if(session('role')=='Студент'){
                    $user_id=session('id');           
                }
                else{
                    $user_id=$this->request->getVar('user_id');   
                }
                if($this->request->getVar('description')){
                    $descriptions=$this->request->getVar('description');
                }
                else{
                    $data['validation']='<ul><li>Укажите наименование хотя бы одной группы!</li></ul>';
                    return json_encode($data);
                }
                $rules=[];
                foreach($descriptions as $key => $description){
                    $rules[ 'description.' . $key ] = [
                        'rules'  => 'required|min_length[10]|max_length[255]',
                        'errors' => [
                            'required' => 'Заполните все поля описаний',
                            'min_length' => 'Минимальная длина поля описания - 10 символов.',
                            'max_length' => 'Максимальная длина поля описания - 255 символов.'
                        ]
                    ];
        
                }
                if($files = $this->request->getFiles()){
                    foreach($files['files'] as $key => $file){
                        $rules[ 'files.' . $key ] = [
                            'rules'  => 'uploaded[files]|ext_in[files,png,jpg,jpeg,rar,zip,pdf,docx,doc]|max_size[files,307200]',
                            'errors' => [
                                'uploaded' => 'Загрузите файлы во все поля или удалите лишние!',
                                'ext_in' =>'Допустимые расширения файлов: "png,jpg,jpeg,rar,zip,pdf,docx,doc"',
                                'max_size' =>'Максимальный размер одного файла - 300 мб'
                            ]
                        ];
            
                    }
                }
                else{
                    $data['validation'] = '<ul><li>Загрузите хотя бы один файл</li></ul>';
                    return json_encode($data);
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
                    $user=$this->user_model->where('id',$user_id)->first();
                    if($files = $this->request->getFiles())
                    {
                        $val=0;
                        $files_names=array();
                        foreach($files['files'] as $file)
                        {
                            if ($file->isValid() && ! $file->hasMoved())
                            {
                                $newName = $file->getRandomName();
                                array_push($files_names,$newName);
                                $file->move(WRITEPATH.'uploads/portfolio/'.$user['id'], $newName);
                            }
                        }
                        foreach ($files['files'] as $key => $file) {
                            $data=[
                                'filename'=>$files_names[$val],
                                'description'=>$descriptions[$val],
                                'user_id'=>$user_id
                            ];
                            $val=$val+1;
                            $this->portfolio_model->insert($data);
                        }
                        return json_encode(true);
                    }
        
                }
            }
        }
        
    }

    public function delete()
    {
        if($this->request->getMethod()=='post'){
            if(session('role')=='Администратор'||session('role')=='Студент'){
                $user_id=null;
                $file_id=$this->request->getVar('file_id');
                if(session('role')=='Студент'){
                    $user_id=session('id');  
                }
                else{
                    $user_id=$this->request->getVar('user_id');   
                }
                $user=$this->user_model->where('id',$user_id)->first();
                $data=[
                    'id'=>$file_id,
                    'user_id'=>$user_id
                ];
                $portfolio=$this->portfolio_model->where($data)->first();
                $this->portfolio_model->where($data)->delete();
                unlink(WRITEPATH.'uploads/portfolio/'.$user['id'].'/'.$portfolio['filename']);  
            }
        }

    }

    function download($id) {
        // load download helper
        helper('download');
        if(session('role')=='Администратор'||session('role')=='Студент'||session('role')=='Куратор'||session('role')=='Работодатель'){
            $portfolio=$this->portfolio_model->where('id',$id)->first();
            $data = WRITEPATH.'uploads/portfolio/'.$portfolio['user_id'].'/'.$portfolio['filename'];
            return $this->response->download($data, null);
        }

    }

}