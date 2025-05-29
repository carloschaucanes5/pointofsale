<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = "person";
    protected $primaryKey = "id";
    public  $timesstamp = false;
    public $timestamps = false;
    protected $fillable = [
    'person_type',
    'name',
    'document_type',
    'document_number',
    'address',
    'phone',
    'email',
    'status'
    ];

    protected $guarded = [];
}
