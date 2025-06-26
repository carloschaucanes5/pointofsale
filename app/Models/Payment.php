<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = "payment";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
    'sale_id',
    'method',
    'value',
    'status'
    ];

    protected $guarded = [];
}
