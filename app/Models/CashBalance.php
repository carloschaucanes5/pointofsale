<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBalance extends Model
{
    use HasFactory;
    protected $table = "cash_balance";
    protected $primaryKey = "id";
    public  $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
    'cash_id',
    'method',
    'balance'
    ];

    protected $guarded = [];
}
