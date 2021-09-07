<?php

namespace App\Models;

use CodeIgniter\Model;

class ResumeSkillModel extends Model
{
    protected $table = 'resume_skills';
    protected $allowedFields = ['resume_id','skill_id'];
}