<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = "task";
    protected $primaryKey = "id";
    public $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'status',
        'assigner_id',
        'assignee_id',
        'start_at',
        'end_at',
        'detail',
        'origin'
    ];

    protected $guarded = [];
}
