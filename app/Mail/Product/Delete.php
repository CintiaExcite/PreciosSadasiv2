<?php

namespace App\Mail\Product;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Delete extends Mailable
{
    use Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@precios.sadasi.com', 'Sistema de Precios Sadasi')
                    ->subject('Modelo eliminado')
                    ->with(['product' => $this->product])
                    ->view('emails.product.delete');
    }
}
