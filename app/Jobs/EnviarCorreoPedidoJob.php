<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Mail\PedidoConfirmadoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoPedidoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function handle(): void
    {
        // 1. Enviar el email usando la clase Mailable
        Mail::to($this->pedido->cliente_email)->send(new PedidoConfirmadoMail($this->pedido));

        // 2. Marcar en la base de datos que ya se mandó
        $this->pedido->update([
            'email_enviado_at' => now()
        ]);
    }
}