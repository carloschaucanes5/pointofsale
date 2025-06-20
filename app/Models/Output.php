<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;
    protected $table = "output";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
    'quantity_out',
    'income_detail_id',
    'description',
    'status'
    ];
}
