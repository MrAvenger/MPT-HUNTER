<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','email','role','password','active','is_root','verification_code'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected function beforeInsert(array $data){
        $data = $this->passwordHash($data);
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function beforeUpdate(array $data){
        if(!empty($data['data']['password'])){
            $data = $this->passwordHash($data);
        }
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function passwordHash(array $data){
        if(isset($data['data']['password']))
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
}