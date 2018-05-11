<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoginEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }

    public function onUserLogin($event) 
    {
        $event->listen('auth.login', function ($user, $remember) {
            $usuario = $user->toArray();
            $data = date('d/m/Y h:m:i');
            $msg = "O usuário $usuario efetuou login em $data" ;
            Log::create([
                'ip'  => \Request::ip(), 
                'usuario_id' => $user->id, 
                'descricao' => '',
            ]);
        });
    }

    
    public function onUserLogout($event) 
    {
        
    }
}
