<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOpening extends Model
{
    use HasFactory;
    protected $table = "cash_opening";
    protected $primaryKey = "id";
    public  $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
        'users_id',
        'start_amount',
        'opened_at',
        'cashbox_name',
        'location',
        'observations',
        'status'
    ];

    protected $guarded = [];
}
