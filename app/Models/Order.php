<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='orders';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'customer_id',
        'bakehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_date',
        'status',
        'payment_status',
        'payment_method',
        'shipping_status',
        'document',
        'uuid',
        'note',
        'add_date',
        'added_by',
        'add_ip',
        'edited_by',
        'edit_date',
        'edit_ip',
        'is_deleted',
        'delete_ip',
        'delete_date',
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function auteur()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrdeDetails::class, 'order_id', 'id');
    }
}
