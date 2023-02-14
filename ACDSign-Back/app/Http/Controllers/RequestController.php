<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\RequestResource;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = RequestModel::all();
        return RequestResource::collection($requests);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array_merge($request->all(), ['user_id' => Auth::user()->id], ['signed' => false]);
        $requestData = RequestModel::create($data);
        return new RequestResource($requestData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(RequestModel $request)
    {
        if ($request->user_id == Auth::user()->id || Auth::user()->role == 'admin') return $request;
        return response()->json(['status' => 'Unauthorized'], 401);
    }

    /**
     * Permet d'afficher les requests de l'utilisateur actif
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showMine(RequestModel $request)
    {
        $request = RequestModel::where('user_id', Auth::user()->id)->get();
        return new RequestResource($request);
    }

    public function sendEmail(RequestModel $request)
    {
        if (!($request->user_id == Auth::user()->id || Auth::user()->role == 'admin'))
            return response()->json(['status' => 'Unauthorized'], 401);

        $title = 'Récupération de vos données';

        $sender = [
            'first_name' => Auth::user()->first_name,
            'last_name' => Auth::user()->last_name,
            'email' => Auth::user()->email,
        ];

        $urlConfirm = URL::temporarySignedRoute(
            'request.confirmSignature', now()->addMinutes(30), ['id' => $request->id]
        );

        $sendmail = Mail::to($request->email)->send(new SendEmail($title, $sender, $request, $urlConfirm));

        if (!empty($sendmail)) {
            return response()->json(['message' => 'Mail Sent Successfully'], 200);
        } else {
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
    }

    public function confirmSignature(Request $requestData)
    {

        $request = RequestModel::find($requestData->id);

        if (! $requestData->hasValidSignature()) {
            return response()->json(['status' => 'Unauthorized'], 401) ;
        }

        if ($request->signed == true) {
            return response()->json(['status' => 'Vous avez déjà signé'], 401);
        }

        $request->update(array('signed' => true));

        return new RequestResource($request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestModel $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requestData, RequestModel $request)
    {
        $data = array_merge($requestData->all(), ['user_id' => $request->user_id]);
        $request->update($data);
        return new RequestResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestModel $request)
    {
        $request->delete();
        return response(null, 204);
    }
}
