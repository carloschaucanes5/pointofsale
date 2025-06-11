<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = "voucher";
    protected $primaryKey = "id";
    public  $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
    'voucher_number',
    'description',
    'total',
    'status'
    ];

    protected $guarded = [];
}
