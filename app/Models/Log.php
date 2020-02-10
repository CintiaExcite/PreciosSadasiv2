<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function development()
    {
        return $this->belongsTo(Development::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Estado
     * 1 - Creacion.*
     * 2 - Actualizacion.*
     */
    public static function logState($usuario_id, $accion, $estado_id, $estado_nombre)
    {
        $log = new Log;
        $log->user_id = $usuario_id;
        $log->event = 'estado';
        $log->action = $accion;
        $log->state_id = $estado_id;
        switch ($accion) {
            case '1':
                $log->description = "Creo el Estado " . $estado_nombre;
                break;
            case '2':
                $log->description = "Actualizó el Estado " . $estado_nombre;
                break;
            default:
                break;
        }
        $log->save();
        return true;
    }

    /**
     * Desarrollo
     * 1 - Creacion.*
     * 2 - Actualizacion.*
     * 3 - Actualización Imágen.
     */
    public static function logDevelopment($usuario_id, $accion, $desarrollo_id, $desarrollo_nombre)
    {
        $log = new Log;
        $log->user_id = $usuario_id;
        $log->event = 'desarrollo';
        $log->action = $accion;
        $log->development_id = $desarrollo_id;
        switch ($accion) {
            case '1':
                $log->description = "Creo el Desarrollo " . $desarrollo_nombre;
                break;
            case '2':
                $log->description = "Actualizó el Desarrollo " . $desarrollo_nombre;
                break;
            case '3':
                $log->description = "Actualizó solo la imagen del Desarrollo " . $desarrollo_nombre;
                break;
            default:
                break;
        }
        $log->save();
        return true;
    }

    /**
     * Modelo
     * 1 - Creacion.*
     * 2 - Actualizacion.*
     * 3 - Actualizacion Precio.*
     * 4 - Próximamente.*
     * 5 - Actualización Descuento.*
     * 6 - Actualización Imágen.*
     * 7 - Actualización Ingresos.*
     * 8 - Actualización Mensualidades.*
     * 9 - Actualización Textos Precios.*
     * 10 - Actualización Mostrar/Ocultar Descuento.*
     * 11 - Actualización Mostrar/Ocultar Ingresos.*
     * 12 - Actualización Mostrar/Ocultar Mensualidades.*
     */
    public static function logProduct($usuario_id, $accion, $modelo_id, $modelo_nombre)
    {
        $log = new Log;
        $log->user_id = $usuario_id;
        $log->event = 'modelo';
        $log->action = $accion;
        $log->product_id = $modelo_id;
        switch ($accion) {
            case '1':
                $log->description = "Creo el Modelo " . $modelo_nombre;
                break;
            case '2':
                $log->description = "Actualizó el Modelo " . $modelo_nombre;
                break;
            case '3':
                $log->description = "Actualizó solo el precio del Modelo " . $modelo_nombre;
                break;
            case '4':
                $log->description = "Colocó el Modelo " . $modelo_nombre . " en Próximamente";
                break;
            case '5':
                $log->description = "Actualizó solo el descuento del Modelo " . $modelo_nombre;
                break;
            case '6':
                $log->description = "Actualizó solo la imagen del Modelo " . $modelo_nombre;
                break;
            case '7':
                $log->description = "Actualizó solo los ingresos desde del Modelo " . $modelo_nombre;
                break;
            case '8':
                $log->description = "Actualizó solo las mensualidades desde del Modelo " . $modelo_nombre;
                break;
            case '9':
                $log->description = "Actualizó solo los textos del precio del Modelo " . $modelo_nombre;
                break;
            case '10':
                $log->description = "Actualizó mostrar/ocultar descuento del Modelo " . $modelo_nombre;
                break;
            case '11':
                $log->description = "Actualizó mostrar/ocultar ingresos desde del Modelo " . $modelo_nombre;
                break;
            case '12':
                $log->description = "Actualizó mostrar/ocultar mensualidades desde del Modelo " . $modelo_nombre;
                break;
            default:
                break;
        }
        $log->save();
        return true;
    }

    /**
     * Usuario
     * 1 - Creacion.*
     * 2 - Actualizacion.*
     * 100 - Login*
     * 101 - Logout*
     * 3 - Acciones de Impresión, Captura de Pantalla, etc
     */
    public static function logUser($usuario_id, $accion, $usuarioc_id, $usuario_nombre)
    {
        $log = new Log;
        $log->user_id = $usuario_id;
        $log->event = 'usuario';
        $log->action = $accion;
        $log->userc_id = $usuarioc_id;
        switch ($accion) {
            case '1':
                $log->description = "Creo el Usuario " . $usuario_nombre;
                break;
            case '2':
                $log->description = "Actualizó el Usuario " . $usuario_nombre;
                break;
            case '100':
                $log->description = "El Usuario " . $usuario_nombre . " accedió al sistema";
                break;
            case '101':
                $log->description = "El Usuario " . $usuario_nombre . " salió del sistema";
                break;
            case '3':
                $log->description = "El Usuario " . $usuario_nombre . " realizó una impresion/captura de pantalla en el sistema";
                break;
            default:
                break;
        }
        $log->save();
        return true;
    }

    //https://elbigdata.mx/justicia/video-ella-es-la-sensual-maine-c-modelo-detenida-por-su-relacion-con-el-cjng/
}
