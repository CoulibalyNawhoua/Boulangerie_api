<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='customers';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'first_name',
        'phone',
        'last_name',
        'address',
        'status',
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
        'deleted_by',
        'delete_ip',
        'uuid'
    ];


    public function bakehouse()
    {
        return $this->belongsTo(Bakehouse::class, 'bakehouse_id', 'id');
    }
    public function transactions(){
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
