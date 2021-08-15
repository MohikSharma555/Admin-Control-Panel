<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $clients=Client::where(function($q){
                if(Auth::User()->isAdmin == 0){
                    $q->where('user_id',Auth::User()->id);
                }
            })
            ->with('user')
            ->withCount('listdocuments as documentcount')
            ->orderBy('id','DESC')
        ->paginate(15);

        $user=user::where('id','!=',Auth::User()->id)
            ->get();
        return view('home')
            ->with('clients',$clients)
            ->with('user',$user);
    }

    public function searchclients(Request $request){
        //dd($request->all());
        if($request->name == NULL && $request->sin == NULL){
            return redirect()->back();
        }
        //dd($request->all());
        $clients=Client::where(function($q) use($request){
                if(Auth::User()->isAdmin == 0){
                    $q->where('user_id',Auth::User()->id);
                }
                if($request->name != NULL){
                    $q->where('name','like','%'.$request->name.'%');
                }
                if($request->sin != NULL){
                    $q->where('sin',$request->sin);
                }
            })
            ->withCount('listdocuments as documentcount')
            ->with('user')
            ->orderBy('id','DESC')
            ->get();
        return view('home')
            ->with('clients',$clients);
    }

    public function createuser(Request $request){
        //dd($request->role);
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user=User::create([
           'name' => ucwords(strtolower($request->name)),
           'email' => $request->email,
           'password' => Hash::make($request->password),
        ]);

        if($request->role != NULL){
            $user->update([
                'isAdmin' => 1
            ]);
        }

        Session::flash('flash_type','success');
        Session::flash('flash_message', 'User Added Successfully.'); 

        return redirect()->back();
            
    }

    public function changepassword(Request $request){
        if($request->userid == NULL){
            $this->validate($request,[
                'oldpassword' => 'required|string|min:8',
                'password' =>'required|string|min:8|confirmed',
            ]);
            if(Hash::check($request->oldpassword, Auth::user()->password)){
                User::where('id',Auth::user()->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);
                Session::flash('flash_type','success');
                Session::flash('flash_message', 'Password Changed Successfully.'); 
            }else{
                Session::flash('flash_type','danger');
                Session::flash('flash_message', 'Wrong Old Password.'); 
            }
        }else if(Auth::User()->isAdmin == 1  && $request->oldpassword == NULL){
            $this->validate($request,[
                'userid' => 'required',
                'password' =>'required|string|min:8|confirmed',
            ]);
            User::where('id',$request->userid)
                ->update([
                    'password' => Hash::make($request->password),
            ]);
            Session::flash('flash_type','success');
            Session::flash('flash_message', 'Password Changed Successfully.'); 
        }
            
        return redirect()->back(); 
    }
}
