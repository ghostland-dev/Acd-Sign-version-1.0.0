<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Template;

class TemplateTest extends TestCase
{
    //Test de la route post /api/templates. Si l'admin est connecté, il doit retourner un Created
    public function testPost_Template_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route post /api/templates
        $this->postJson(url('') . "/api/templates", [
            'subject' => 'test',
            'message' => 'test',
            'footer' => 'test'
        ], [
            'Authorization' => "Bearer $token"
        ])->assertCreated();
    }

    //Test de la route post /api/templates. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testPost_Template_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route post /api/templates
        $this->postJson(url('') . "/api/templates", [
            'subject' => 'test',
            'message' => 'test',
            'footer' => 'test'
        ], [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route post /api/templates. Si personne est authentifié, il doit retourner un Unauthorized
    public function testPost_Template_Failed_No_Authenticated()
    {
        //Appel de la route post /api/templates
        $this->postJson(url('') . "/api/templates", [
            'subject' => 'test',
            'message' => 'test',
            'footer' => 'test'
        ])->assertUnauthorized();
    }

    //Test de la route get /api/templates/{id}. Si l'admin est connecté, il doit retourner un Ok
    public function testGet_Template_Success_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route get /api/templates/{id}
        $this->getJson(url('') . "/api/templates/$template->id", [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route get /api/templates/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testGet_Template_Failed_No_Authenticated()
    {
        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route get /api/templates/{id}
        $this->getJson(url('') . "/api/templates/$template->id")->assertUnauthorized();
    }

    //Test de la route get /api/templates. Si l'admin est connecté, il doit retourner un Ok
    public function testGet_Templates_Success_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/templates
        $this->getJson(url('') . "/api/templates", [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route /api/templates. Si personne est authentifié, il doit retourner un Unauthorized
    public function testGet_Templates_Failed_No_Authenticated()
    {
        //Appel de la route get /api/templates
        $this->getJson(url('') . "/api/templates")->assertUnauthorized();
    }

    //Test de la route put /api/templates/{id}. Si l'admin est connecté, il doit retourner un Ok
    public function testPut_Template_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route put /api/templates/{id}
        $this->putJson(url('') . "/api/templates/$template->id", [
            "subject" => "test1"
        ], [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route put /api/templates/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testPut_Template_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route put /api/templates/{id}
        $this->putJson(url('') . "/api/templates/$template->id", [
            "subject" => "test1"
        ], [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route put /api/users/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testPut_User_Failed_No_Authenticated()
    {
        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route put /api/templates/{id}
        $this->putJson(url('') . "/api/templates/$template->id", [
            "subject" => "test1"
        ])->assertUnauthorized();
    }

    //Test de la route delete /api/templates/{id}. Si l'admin est connecté, il doit retourner un NoContent
    public function testDelete_Template_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route delete /api/templates/{id}
        $this->deleteJson(url('') . "/api/templates/$template->id", [], [
            'Authorization' => "Bearer $token"
        ])->assertNoContent();
    }

    //Test de la route delete /api/templates/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testDelete_User_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route delete /api/templates/{id}
        $this->deleteJson(url('') . "/api/templates/$template->id", [], [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route delete /api/templates/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testDelete_Template_Failed_No_Authenticated()
    {
        //Création d'un template de test
        $template = Template::factory()->create();

        //Appel de la route delete /api/templates/{id}
        $this->deleteJson(url('') . "/api/templates/$template->id", [])->assertUnauthorized();
    }
}
