<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockProduction extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='productions_stock';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'created_at',
        'updated_at',
        'quantity',
        'unit_id',
        'bakehouse_id',
        'product_id',
        'price'
    ];
}
