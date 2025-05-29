<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "product";
    protected $primaryKey = "id";
    public  $timesstamp = false;
    public $timestamps = false;
    protected $fillable = [
    'category_id',
    'description',
    'status',
    'stock',
    'name',
    'code',
    'image',
    'concentration',
    'presentation',
    'laboratory'
    ];

    protected $guarded = [];
}
