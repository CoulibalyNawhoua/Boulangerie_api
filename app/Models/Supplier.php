<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='suppliers';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'first_name',
        'phone',
        'last_name',
        'email',
        'company',
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
        'uuid',
        'famille_id'
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }

    public function famille()
    {
        return $this->belongsTo(Famille::class,'famille_id','id');
    }
}
