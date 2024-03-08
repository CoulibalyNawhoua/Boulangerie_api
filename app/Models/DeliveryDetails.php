<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryDetails extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='order_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'product_id',
        'quantity',
        'price',
        'unit_id',
        'created_at',
        'updated_at',
    ];
}
