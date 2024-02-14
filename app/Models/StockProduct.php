<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockProduct extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='stock_productions';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'created_at',
        'updated_at',
        'low_quantity',
        'quantity',
        'stock_alert',
        'unit_id',
        'bakehouse_id',
        'product_id',
        'sell_price'
    ];
}
