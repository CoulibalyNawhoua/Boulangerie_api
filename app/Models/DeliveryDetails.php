<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryDetails extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='delivery_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'product_id',
        'quantity',
        'price',
        'unit_id',
        'delivery_id',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }
}
