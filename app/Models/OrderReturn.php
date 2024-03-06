<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderReturn extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;


    protected $table='order_returns';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'comment',
        'order_id',
        'bakehouse_id',
        'created_at',
        'updated_at',
        'add_date',
        'added_by',
        'add_ip',
        'edited_by',
        'edit_date',
        'edit_ip',
        'is_deleted',
        'delete_ip',
        'delete_date',
        'uuid'
    ];

    public function order_return_details()
    {
        return $this->hasMany(OrderReturnDetail::class, 'order_return_id', 'id');
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'delivery_person_id', 'id');
    }
}
