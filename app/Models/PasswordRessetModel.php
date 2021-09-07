<?php
namespace App\Models;
use CodeIgniter\Model;
class PasswordRessetModel extends Model
{
    protected $table = 'password_ressets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','user_id','resset_code'];
}