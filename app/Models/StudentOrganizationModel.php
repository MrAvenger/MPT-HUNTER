<?php
namespace App\Models;
use CodeIgniter\Model;
class StudentOrganizationModel extends Model
{
    protected $table = 'students_organizations';
    protected $primaryKey = 'id';
    protected $allowedFields =  ['id','user_id','organization_id','status'];
}