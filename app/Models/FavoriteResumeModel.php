<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoriteResumeModel extends Model
{
    protected $table = 'favorite_resume';
    protected $primaryKey = 'id';
    protected $allowedFields =  ['id','user_id','resume_id'];
}