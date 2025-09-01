<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsPayable extends Model
{
    use HasFactory;
    protected $table = "accounts_payable";
    protected $primaryKey = "id";
    public  $timesstamp = true;
    public $timestamps = true;
    protected $fillable = [
    'supplier_id',
    'invoice_id',
    'issue_date',
    'due_date',
    'amount',
    'balance',
    'status',
    'notes'
    ];

    protected $guarded = [];
}
