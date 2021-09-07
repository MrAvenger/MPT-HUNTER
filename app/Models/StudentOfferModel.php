<?php
namespace App\Models;
use CodeIgniter\Model;
class StudentOfferModel extends Model
{
    protected $table = 'student_offers';
    protected $primaryKey = 'id';
    protected $allowedFields =  ['id','user_id','offer_id','is_favorite','is_respond'];
}