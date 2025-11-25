<?php

// app/Models/TipoNotificacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoNotificacion extends Model
{
    protected $table = 'tipo_notificaciones';

    protected $primaryKey = 'id_Tipo_Notif';

    public $timestamps = false;

    protected $fillable = [
        'Tipo_Notif',
        'Description_Notif',
    ];

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'Tipo_Notif_ID');
    }
}
