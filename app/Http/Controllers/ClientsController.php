<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Auth;
use Session;

class ClientsController extends Controller
{
    public function store(Request $request){
        //dd($request);
        $this->validate($request,[
            'name' => 'required',
            'sin' => 'required|unique:clients,sin'
        ]);

        if(Auth::User()->isAdmin == 1){
            $this->validate($request,[
                'user' => 'required'
            ]);
            Client::create([
                'user_id' => $request->user,
                'name' => ucwords(strtolower($request->name)),
                'sin' => $request->sin
            ]);
        }else{
            Client::create([
                'user_id' => Auth::User()->id,
                'name' => ucwords(strtolower($request->name)),
                'sin' => $request->sin
            ]);
        }

        Session::flash('flash_type','success');
        Session::flash('flash_message','Client Created.');

        return redirect()->back();
    }

    public function show($cid){
        $client=Client::where('id',$cid)
            ->where(function($q){
                if(Auth::User()->isAdmin == 0){
                    $q->where('user_id',Auth::User()->id);
                }
            })
            ->with('listdocuments.documents')
            ->with('listdocuments',function($q){
                $q->orderBy('id','DESC');
            })
            ->with('notes',function($q){
                $q->orderBy('id','DESC');
            })
            ->first();
        if($client != NULL){
           // dd($client);
            return view('Clients.show')
                ->with('client',$client);
        }else{
            return abort(404);
        }
    }
}