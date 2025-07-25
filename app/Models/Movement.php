<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;
    protected $table = "movement";
    protected $primaryKey = "id";
    public  $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
        'users_id',
        'cash_opening_id',
        'type',
        'movement_type_id',
        'description',
        'amount',
        'payment_method',
        'table_identifier'
    ];

    protected $guarded = [];

   public function getSignedAmountAttribute()
    {
        return $this->type === 'income' ? $this->amount : -1 * $this->amount;
    }
}
