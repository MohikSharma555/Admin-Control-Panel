<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Auth;
use Session;

class NotesController extends Controller
{
    public function store(Request $request){
        //dd($request->all());
        $this->validate($request,[
            'note' => 'required',
            'client' => 'required',
        ]);
        Note::create([
            'user_id' => Auth::User()->id,
            'client_id' => $request->client,
            'note' => $request->note
        ]);

        Session::flash('flash_type','success');
        Session::flash('flash_message','Notes Added.');

        return redirect()->back();
    }
}
