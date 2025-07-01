<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $table = "sale_detail";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
    'quantity',
    'sale_price',
    'discount',
    'sale_id',
    'income_detail_id'
    ];
}
