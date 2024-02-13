<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='products_stock';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'created_at',
        'updated_at',
        'purchase_price',
        'quantity',
        'low_quantity',
        'stock_alert',
        'product_id',
        'bakehouse_id',
        'unit_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
}
