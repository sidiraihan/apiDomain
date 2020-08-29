<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Domain;
use App\Http\Resources\DomainCollection;


use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function verifyUser($request){

        $username = $request->header('username');
        $password = $request->header('password');
        $idkey = $request->header('idkey');
       
        $get = User::where('username', $username)
        ->where('idkey', $idkey)
        ->first();

        if(empty($get)){
             return false;
        }else{
            if(Hash::check($password, $get->password)) {
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {   
        try{
            $auth = $this->verifyUser($request);
            if(!$auth){
                return response()->json(['status' => 'forbidden request']);
            }
        } catch (Throwable $e) {
            return $e;
        }

        return new DomainCollection(Domain::paginate());
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $auth = $this->verifyUser($request);
            if(!$auth){
                return response()->json(['status' => 'forbidden request']);
            }
        } catch (Throwable $e) {
            return $e;
        }


        $owner = User::select('id')->where('idkey', $request->header('idkey'))->first();
        $domain_check = Domain::where('status', 'active')->where('name', $request->get('domain_name'))->first();
        if(empty($domain_check)){
            $domain = Domain::firstOrNew(
                ['name' => $request->get('domain_name')],
                ['status' => 'active', 'owner' => $owner->id, 'expiration_date' => $request->get('expiration_date')],
            );
            $domain->save();
            return response()->json(['status' => 'domain registration complete']);
        }else{
            return response()->json(['status' => 'domain already existed']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $status)
    {
        try{
            $auth = $this->verifyUser($request);
            if(!$auth){
                return response()->json(['status' => 'forbidden request']);
            }
        } catch (Throwable $e) {
            return $e;
        }

        return new DomainCollection(Domain::where('status', $status)->paginate());
    }


    public function check(Request $request)
    {
        try{
            $auth = $this->verifyUser($request);
            if(!$auth){
                return response()->json(['status' => 'forbidden request']);
            }
        } catch (Throwable $e) {
            return $e;
        }

        return new DomainCollection(Domain::with('ownerData')->where('name', $request->get('domain_name'))->get());
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
