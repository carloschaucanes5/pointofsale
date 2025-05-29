<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $table = "income";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
    'voucher_type',
    'voucher_number',
    'tax',
    'status',
    'supplier_id'
    ];

    protected $guarded = [];
}
