<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bakehouse extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='bakehouses';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'name',
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
        'address',
        'phone',
        'responsable',
        'nb_delivery_person',
        'uuid'
    ];


    public function auteur()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }
}
