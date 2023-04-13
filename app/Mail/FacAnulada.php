<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Facturacion;

class FacAnulada extends Mailable
{
    use Queueable, SerializesModels;
    
    public $factura;
    public $moneda;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Facturacion $factura )
    {
       
        $this->factura = $factura;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $msj1 = [storage_path('facturas/factura.pdf')];
        
        $email = $this->view('emailanulada')->subject('Factura Anulada');
        return $email;
    }
}
