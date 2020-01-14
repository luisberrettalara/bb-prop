<?php

namespace Tests\Browser\Propiedades;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Inmuebles\Models\Usuarios\User;

class PublicarTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Probamos mostrar el formulario de 
     *
     * @return void
     */
    public function testPublicarSinUsuarioLogueado()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/propiedades/create')
                    ->assertSee('Para comenzar a publicar debe estar registrado');
        });
    }

    /**
     * Probamos mostrar el formulario de 
     *
     * @return void
     */
    public function testPublicarSinDatosMinimos()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/propiedades/create')
                    ->assertSee('Para comenzar a publicar debe estar registrado');
        });
    }
}
