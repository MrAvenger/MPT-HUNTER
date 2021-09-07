<?php

namespace App\Models;

use CodeIgniter\Model;

class ResumeModel extends Model
{
    protected $table = 'resume';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','about_me','education','work_experience','additionally','nearest_metro','user_id','average_score'];
}