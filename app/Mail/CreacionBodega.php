<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class CreacionBodega extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $tipo;
    public $proyecto;
    public $week;

    /**
     * Create a new message instance.a
     *
     * @return void
     */
    public function __construct($nombre,$tipo,$proyecto,$week)
    {

        $this->name=$nombre;
        $this->tipo=$tipo;
        $this->proyecto=$proyecto;
        $this->week=$week;
        //print(json_encode($this->name));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('rgutierrez@flesan.com.pe','Portal Checklist Abastecimiento')
            ->subject('Creación Checklist')
            ->markdown('mail.creacionCheck')
            ->with([
                'name' =>$this->name,
                //'link' =>'http://192.168.23.130.xip.io:8000/',
                'tipo' => $this->tipo,
                'proyecto' => $this->proyecto,
                'week' => $this->week,
            ]);
    }
}
