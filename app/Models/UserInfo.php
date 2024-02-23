<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserInfo extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='user_infos';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'user_id',
        'avatar',
        'adresse',
        'telephone',
        'num_piece',
        'telephone2',
        'civility_id',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
