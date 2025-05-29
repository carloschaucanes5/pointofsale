<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = "sale";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
    'voucher_type',
    'voucher_number',
    'tax',
    'status',
    'customer_id',
    'sale_total'
    ];

    protected $guarded = [];
}
