<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;
    protected $table = "laboratory";
    protected $primaryKey = "id";
    public  $timesstamp = false;
    public $timestamps = false;
    protected $fillable = [
    'name',
    'status'
    ];

    protected $guarded = [];
}
