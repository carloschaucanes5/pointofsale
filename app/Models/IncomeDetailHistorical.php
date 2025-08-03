<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDetailHistorical extends Model
{
    use HasFactory;
    protected $table = "income_detail_historical";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
    'income_detail_id',
    'quantity',
    'purchase_price',
    'sale_price',
    'income_id',
    'product_id',
    'form_sale',
    'lote',
    'invima'
    ];
}
