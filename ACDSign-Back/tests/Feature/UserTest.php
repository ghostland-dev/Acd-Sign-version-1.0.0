<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    //Test de la route get /api/users/{id}. Si l'admin est connecté, il doit retourner un Ok
    public function testGet_User_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/users/{id}
        $this->getJson(url('') . "/api/users/$userConnected->id",[
            'Authorization'=> "Bearer $token"
        ])->assertOk();
    }

    //Test de la route get /api/users/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testGet_User_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/users/{id}
        $this->getJson(url('') . "/api/users/$userConnected->id",[
            'Authorization'=> "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route /api/users/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testGet_User_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Appel de la route get /api/users/{id}
        $this->getJson(url('') . "/api/users/$userConnected->id")->assertUnauthorized();
    }

    //Test de la route get /api/users. Si l'admin est connecté, il doit retourner un Ok
    public function testGet_Users_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/users
        $this->getJson(url('') . "/api/users",[
            'Authorization'=> "Bearer $token"
        ])->assertOk();
    }

    //Test de la route get /api/users. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testGet_Users_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/users
        $this->getJson(url('') . "/api/users",[
            'Authorization'=> "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route get /api/users. Si personne est connecté, il doit retourner un Unauthorized
    public function testGet_Users_Failed_No_Authenticated()
    {
        //Appel de la route get /api/users
        $this->getJson(url('') . "/api/users")->assertUnauthorized();
    }

    //Test de la route put /api/users/{id}. Si l'admin est connecté, il doit retourner un Ok
    public function testPut_User_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'user']);

        //Appel de la route put /api/users/{id}
        $this->putJson(url('') . "/api/users/$user->id",[
            "name" => "test"
        ], [
            'Authorization'=> "Bearer $token"
        ])->assertOk();
    }

    //Test de la route put /api/users/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testPut_User_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'user']);

        //Appel de la route put /api/users/{id}
        $this->putJson(url('') . "/api/users/$user->id",[
            "name" => "test"
        ], [
            'Authorization'=> "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route put /api/users/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testPut_User_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'user']);

        //Appel de la route put /api/users/{id}
        $this->putJson(url('') . "/api/users/$user->id",[
            "name" => "test"
        ])->assertUnauthorized();
    }

     //Test de la route delete /api/users/{id}. Si l'admin est connecté, il doit retourner un NoContent
    public function testDelete_User_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'user']);

        //Appel de la route delete /api/users/{id}
        $this->deleteJson(url('') . "/api/users/$user->id",[], [
            'Authorization'=> "Bearer $token"
        ])->assertNoContent();
    }

    //Test de la route delete /api/users/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testDelete_User_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'user']);

        //Appel de la route delete /api/users/{id}
        $this->deleteJson(url('') . "/api/users/$user->id",[], [
            'Authorization'=> "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route delete /api/users/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testDelete_User_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles admin
        $user = User::factory()->create(['role' => 'user']);

        //Appel de la route delete /api/users/{id}
        $this->deleteJson(url('') . "/api/users/$user->id",[])->assertUnauthorized();
    }
}
