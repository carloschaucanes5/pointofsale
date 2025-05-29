<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "category";
    protected $primaryKey = "id";
    public  $timesstamp = false;
    public $timestamps = false;
    protected $fillable = [
    'category',
    'description',
    'status'
    ];

    protected $guarded = [];
}
