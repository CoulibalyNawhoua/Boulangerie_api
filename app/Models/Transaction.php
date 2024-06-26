<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;


    protected $table='transactions';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'total_amount',
        'amount',
        'type_payment',
        'customer_id',
        'bakehouse_id',
        'delivery_person_id',
        'note',
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
        'type_payment',
        'status_paiement'

    ];

    public function reception()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'delivery_person_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
