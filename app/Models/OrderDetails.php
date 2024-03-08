<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class OrderDetails extends Model
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
        'order_id',
        'unit_id',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

    public function product_unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
