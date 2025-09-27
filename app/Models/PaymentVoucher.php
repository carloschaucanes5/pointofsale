<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentVoucher extends Model
{
    use HasFactory;
    protected $table = "payment_voucher";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
    'voucher_id',
    'method',
    'value',
    'status',
    'cash_id'
    ];

    protected $guarded = [];
}
