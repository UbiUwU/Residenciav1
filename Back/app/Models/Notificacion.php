<?php

// app/Models/Notificacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $primaryKey = 'id_Notificaciones';

    public $timestamps = false;

    protected $fillable = [
        'Titulo_Notif',
        'Mensaje',
        'Tipo_Notif_ID',
        'Fecha_Hora_Notif',
        'Fecha_Creacion',
    ];

    protected $dates = [
        'Fecha_Hora_Notif',
        'Fecha_Creacion',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoNotificacion::class, 'Tipo_Notif_ID');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'notificaciones_usuario', 'Notification_id', 'Usuario_id')
            ->withPivot('leida');
    }
}
