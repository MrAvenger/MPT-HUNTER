<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupsSpecializationsModel extends Model
{
    protected $table = 'groups_specializations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','specialization_id','group_id'];
}