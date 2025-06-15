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
    'users_id',
    'voucher_id',
    'tax',
    'status',
    ];

    protected $guarded = [];
}
