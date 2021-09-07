<?php
namespace App\Models;
use CodeIgniter\Model;
class JobOfferRequireModel extends Model
{
    protected $table = 'offer_requirements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','offer_id','name'];
}