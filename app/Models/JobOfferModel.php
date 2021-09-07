<?php
namespace App\Models;
use CodeIgniter\Model;
class JobOfferModel extends Model
{
    protected $table = 'job_offers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','org_id','employment','offer_name','salary','offer_description'];
}