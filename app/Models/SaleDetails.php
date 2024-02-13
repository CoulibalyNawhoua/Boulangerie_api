<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetails extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='sale_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'sale_id',
        'product_id',
        'bakehouse_id',
        'name',
        'code ',
        'quantity',
        'price',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
        'unit_id',
        'created_at',
        'updated_at',
    ];
}
