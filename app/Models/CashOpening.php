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
        'end_amount',
        'closed_at',
        'end_amount',
        'closed_at',
        'cashbox_name',
        'location',
        'observations',
        'status',
        'summary',
        'cash_id'
    ];

    protected $guarded = [];
}
