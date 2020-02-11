<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use App\Http\Resources\Client\Client as ClientResource;
use App\Http\Resources\Client\ClientCollection;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::latest()->get();
        return new ClientCollection($clients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $validatedData = $r->validate([
            'clientName' => 'required',
            'clientEmail' => 'required|email|unique:clients',
            'clientId' => 'required|unique:clients',
            'company' => 'required|string|min:3|max:255',
            'emailAddress' => 'required',
            'telephone' => 'required',
            'location' => 'required',
        ]);

        if ($validatedData) {
            $client = new Client();
            $client->name = ucwords($r->company);
            $client->clientName = $r->clientName;
            $client->clientId = $r->clientId;
            $client->clientEmail = $r->clientEmail;
            $client->email = $r->emailAddress;
            $client->telephone = $r->telephone;
            $client->location = $r->location;
            $client->save();

            return new ClientResource($client);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $r)
    {
        $client = Client::where('clientId', $r->pin)->firstOrFail();
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
