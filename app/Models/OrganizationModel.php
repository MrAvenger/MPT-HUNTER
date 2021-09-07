<?php
namespace App\Models;
use CodeIgniter\Model;
class OrganizationModel extends Model
{
    protected $table = 'organizations';
    protected $primaryKey = 'id';
    protected $allowedFields =  ['id','user_id','org_name','org_description','org_photo','org_adress'];
}