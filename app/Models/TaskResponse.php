<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResponse extends Model
{
    use HasFactory;
    protected $table = 'task_responses';
    protected $primaryKey = "id";
    public $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
        'task_id',
        'user_id',
        'response',
        'attachment_path'
    ];

   
   protected $guarded = [];

}
