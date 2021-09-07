<?php 
namespace App\Models;
use CodeIgniter\Model;
class PersonalDataModel extends Model
{
    protected $table = 'personal_data';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id','first_name','last_name','middle_name','specialization_id','group_id','sex','date_birth','number_phone','photo','post'];
}