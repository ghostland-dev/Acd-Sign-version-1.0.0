<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    //Test de la route Login. Si les identifiants sont corrects, alors il doit retourner un OK
    public function testLogin_Success()
    {
        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'admin']);

        //Appel de la route Login
        $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();
    }

    //Test de la route Login.  Si les identifiants sont incorrects, il doit retourner un Bad Request
    public function testLogin_Failed()
    {
        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'admin']);

        //Appel de la route Login
        $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'wrongPassword',
        ])->assertStatus(400);
    }

    //Test de la route Register. Si l'admin est connecté, il doit retourner un Created
    public function testRegister_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route Register
        $this->postJson(route('user.register'), [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName()
        ],[
            'Authorization'=> "Bearer $token"
        ])->assertCreated();
    }

    //Test de la route Register. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testRegister_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route Register
        $this->postJson(route('user.register'), [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName()
        ],[
            'Authorization'=> "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route Register. Si personne est authentifié, il doit retourner un Unauthorized
    public function testRegister_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'admin']);

        //Appel de la route Register
        $this->postJson(route('user.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => $user->firstName,
            'last_name' => $user->lastName
        ])->assertUnauthorized();
    }
}
