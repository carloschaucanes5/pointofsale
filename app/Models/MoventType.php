<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;
 use HasFactory;

    protected $primaryKey = "id";
    public  $timesstamp = false;
    public $timestamps = false;

    protected $table = 'movement_types';

    protected $fillable = [
        'code',
        'name',
        'type',
        'affects_cash',
    ];

  protected $guarded = [];
}