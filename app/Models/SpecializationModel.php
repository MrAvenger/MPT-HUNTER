<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecializationModel extends Model
{
    protected $table = 'specializations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','name','user_id','description'];
}