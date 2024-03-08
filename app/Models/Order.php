<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='orders';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'customer_id',
        'bakehouse_id',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }
}
