<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Client;
use App\Company;
use App\Http\Resources\Company\CompanyCollection;
use App\Http\Resources\Company\Company as CompanyResource;
use App\Http\Controllers\Traits\FileUploadTrait;

class SettingsController extends Controller
{
    use FileUploadTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function general()
    {
        $user = Auth::user();
        $client = Client::where('id', $user->staff->client_id)->first();

        return  new CompanyResource($client->company);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function gemeral_store(Request $request)
    {
        // uplaod image
        // $r = $this->savebase64($request);

        return response()->json($r->logoFile);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gemeral_update(Request $r, $id)
    {
        // uplaod image
        // $r = $this->saveFiles($request);
        // $f =  $request->hasFile('logoUrl');
        $c = Company::find($id);
        $c->name = $r->name;
        $c->reg_no = $r->regNo;
        $c->vat_no = $r->vatNo;
        $c->website = $r->website;
        $c->agent_no = $r->agentNo;
        $c->pin_no = $r->pinNo;
        $c->save();

        return  new CompanyResource($c);
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
