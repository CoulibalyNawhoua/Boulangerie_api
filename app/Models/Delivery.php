<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='deliveries';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'delivery_person_id',
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

    public function delivery_details() {

        return $this->hasMany(DeliveryDetails::class, 'delivery_id', 'id');
    }


    public function delivery_person()
    {
        return $this->belongsTo(User::class, 'delivery_person_id', 'id');
    }
}
