<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnSale extends Model
{
    use HasFactory;
    protected $table = "return_sale";
    protected $primaryKey = "id";
    public  $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
        'income_detail_id',
        'quanity',
        'status',
        'description',
        'users_id'
    ];

    protected $guarded = [];
}
