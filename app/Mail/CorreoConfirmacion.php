<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class CorreoConfirmacion extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;

    /**
     * Create a new message instance.a
     *
     * @return void
     */
    public function __construct($nombre)
    {

        $this->name=$nombre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('rgutierrez@flesan.com.pe','Portal Checklist Abastecimiento')
            ->subject('Verificación Usuario')
            ->markdown('mail.verificacionUser')
            ->with([
                'name' =>$this->name,
                'link' =>'http://192.168.23.130.xip.io:8000/',
               // 'proyecto' => $this->nombre_proyecto,
            ]);
    }
}
