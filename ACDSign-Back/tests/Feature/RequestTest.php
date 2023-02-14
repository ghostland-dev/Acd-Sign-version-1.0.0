<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    //Test de la route post /api/requests. Si l'utilisateur est connecté, il doit retourner un Created
    public function testPost_Request_Success_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route post /api/requests
        $this->postJson(url('') . "/api/requests", [
            'siren' => '5555555555555',
            'company_name' => 'test',
            'address' => 'test',
            'city' => 'test',
            'zip' => 'test',
            'signed' => true,
            'PDL' => 'test',
            'PCE' => 'test',
            'first_name' => 'test',
            'last_name' => 'test',
            'supplier' => true,
            'email' => 'test',
        ], [
            'Authorization' => "Bearer $token"
        ])->assertCreated();
    }

    //Test de la route post /api/requests. Si personne est authentifié, il doit retourner un Unauthorized
    public function testPost_Request_Failed_No_Authenticated()
    {

        //Appel de la route post /api/requests
        $this->postJson(url('') . "/api/requests", [
            'siren' => '5555555555555',
            'company_name' => 'test',
            'address' => 'test',
            'city' => 'test',
            'zip' => 'test',
            'signed' => true,
            'PDL' => 'test',
            'PCE' => 'test',
            'first_name' => 'test',
            'last_name' => 'test',
            'supplier' => true,
            'email' => 'test',
        ])->assertUnauthorized();
    }

    //Test de la route get /api/requests/{id}. Si l'admin est connecté, il peut donc voir n'importe quel requests, il doit retourner un Ok
    public function testGet_Request_Show_Another_Request_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route get /api/requests/{id}
        $this->getJson(url('') . "/api/requests/$request->id", [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route get /api/requests/{id}. Si l'utilisateur est connecté, il peut donc voir ses propres requests, il doit retourner un Ok
    public function testGet_Request_Success_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $userConnected->id]);

        //Appel de la route get /api/requests/{id}
        $this->getJson(url('') . "/api/requests/$request->id", [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route get /api/requests/{id}. Si l'utilisateur est connecté, mais qu'il tente d'accéder à une request que les siens, il doit retourner un Unauthorized
    public function testGet_Request_Not_Autorized_To_Access_To_Another_Request_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route get /api/requests/{id}
        $this->getJson(url('') . "/api/requests/$request->id", [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route get /api/requests/{id}. Si personne n'est pas authentifié, il doit retourner un Unauthorized
    public function testGet_Request_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route get /api/requests/{id}
        $this->getJson(url('') . "/api/requests/$request->id")->assertUnauthorized();
    }

    //Test de la route get /api/requests. Si l'admin est connecté, il doit retourner un Ok
    public function testGet_Requests_Success_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/requests
        $this->getJson(url('') . "/api/requests", [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route /api/requests. Si personne est authentifié, il doit retourner un Unauthorized
    public function testGet_Requests_Failed_No_Authenticated()
    {
        //Appel de la route get /api/requests
        $this->getJson(url('') . "/api/requests")->assertUnauthorized();
    }

    //Test de la route put /api/requests. Si l'admin est connecté, il doit retourner un Ok
    public function testPut_Request_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $userConnected->id]);

        //Appel de la route put /api/requests/{id}
        $this->putJson(url('') . "/api/requests/$request->id", [
            'company_name' => 'test'
        ], [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route put /api/requests/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testPut_Request_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $userConnected->id]);

        //Appel de la route put /api/requests/{id}
        $this->putJson(url('') . "/api/requests/$request->id", [
            'company_name' => 'test'
        ], [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route put /api/requests/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testPut_Request_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route put /api/requests/{id}
        $this->putJson(url('') . "/api/requests/$request->id", [
            'company_name' => 'test'
        ])->assertUnauthorized();
    }

    //Test de la route delete /api/request/{id}. Si l'admin est connecté, il doit retourner un NoContent
    public function testDelete_Request_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles admin
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];
        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $userConnected->id]);

        //Appel de la route delete /api/requests/{id}
        $this->deleteJson(url('') . "/api/requests/$request->id", [], [
            'Authorization' => "Bearer $token"
        ])->assertNoContent();
    }

    //Test de la route delete /api/requests/{id}. Si c'est un utilisateur, il doit retourner un Unauthorized
    public function testDelete_Request_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $userConnected->id]);

        //Appel de la route delete /api/requests/{id}
        $this->deleteJson(url('') . "/api/requests/$request->id", [], [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route delete /api/requests/{id}. Si personne est authentifié, il doit retourner un Unauthorized
    public function testDelete_Request_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route delete /api/requests/{id}
        $this->deleteJson(url('') . "/api/requests/$request->id", [])->assertUnauthorized();
    }

    //Test de la route get /api/requests/mine. Si la personne est authentifié, il doit retourner un Ok
    public function testGet_My_Request_Success_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Appel de la route get /api/mine/requests
        $this->getJson(url('') . "/api/mine/requests", [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route get /api/requests/mine. Si personne est authentifié, il doit retourner un Unauthorized
    public function testGet_My_Request_Failed_No_Authenticated()
    {
        //Appel de la route get /api/mine/requests
        $this->getJson(url('') . "/api/mine/requests")->assertUnauthorized();
    }

    //Test de la route post /api/requests/mine. Si l'admin est connecté, qu'il tente d'envoyé un mail a une request qu'il n'a pas créer, il doit retourner un Ok
    public function testPost_Send_Email_Another_Request_Success_Admin_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'admin']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route post /api/sendEmail/requests/{id}
        $this->postJson(url('') . "/api/sendEmail/requests/" . $request->id, [], [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route post /api/sendEmail/requests. Si c'est un utilisateur, mais qu'il tente d'envoyé un mail a une request qu'il n'a pas créer, il doit retourner un Unauthorized
    public function testPost_Send_Email_Another_Request_Failed_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route post /api/sendEmail/requests/{id}
        $this->postJson(url('') . "/api/sendEmail/requests/" . $request->id, [], [
            'Authorization' => "Bearer $token"
        ])->assertUnauthorized();
    }

    //Test de la route post /api/sendEmail/requests. Si c'est un utilisateur, et qu'il tente d'envoyé un mail à l'une de ses requests, il doit retourner un Ok
    public function testPost_Send_Email_Success_User_Connected()
    {
        //Création de utilisateur de test ayant les roles user
        $userConnected = User::factory()->create(['role' => 'user']);

        //Création d'un token d'authentification, grâce à la route login
        $token = $this->postJson(route('user.login'), [
            'email' => $userConnected->email,
            'password' => 'password',
        ])['token'];

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $userConnected->id]);

        //Appel de la route post /api/sendEmail/requests/{id}
        $this->postJson(url('') . "/api/sendEmail/requests/" . $request->id, [], [
            'Authorization' => "Bearer $token"
        ])->assertOk();
    }

    //Test de la route post /api/sendEmail/requests. Si personne n'est pas connecté, il doit retourner un Unauthorized
    public function testPost_Send_Email_Failed_No_Authenticated()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Appel de la route post /api/sendEmail/requests/{id}
        $this->postJson(url('') . "/api/sendEmail/requests/" . $request->id)->assertUnauthorized();
    }

    //Test de la route put /api/confirmSignature/requests. Il doit retourner un Ok
    public function testPut_Confirm_Signature_Success()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Génération de l'url
        $urlConfirm = URL::temporarySignedRoute(
            'request.confirmSignature', now()->addMinutes(60 * 24 * 2), ['id' => $request->id]
        );

        //Appel de la route put de l'url généré
        $this->putJson($urlConfirm)->assertOk();
    }

    //Test de la route put /api/confirmSignature/requests. Si la signature est invalide, il doit retourner un Unauthorized
    public function testPut_Confirm_Signature_Failed_Invalid_Signature()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);
        
        //Génération de l'url
        $urlConfirm = URL::temporarySignedRoute(
            'request.confirmSignature', now()->addMinutes(60 * 24 * 2), ['id' => $request->id]
        );

        //Appel de la route put de l'url généré
        $this->putJson(substr($urlConfirm, 0, -1))->assertUnauthorized();
    }

    //Test de la route put /api/confirmSignature/requests. Si la personne a déjà confirmer, il doit retourner un Unauthorized
    public function testPut_Confirm_Signature_Failed_Already_Confirm()
    {
        //Création de utilisateur de test ayant les roles user
        $user = User::factory()->create(['role' => 'user']);

        //Création d'un request de test
        $request = Request::factory()->create(["user_id" => $user->id]);

        //Génération de l'url
        $urlConfirm = URL::temporarySignedRoute(
            'request.confirmSignature', now()->addMinutes(60 * 24 * 2), ['id' => $request->id]
        );

        //Appel de la route put de l'url généré
        $this->putJson($urlConfirm)->assertOk();

        //Appel une deuxième fois la route put de l'url généré pour vérifier le retour
        $this->putJson($urlConfirm)->assertUnauthorized();
    }
}
