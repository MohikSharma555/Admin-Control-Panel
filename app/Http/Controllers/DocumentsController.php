<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\ListDocument;
use Illuminate\Http\Request;
use Auth;
use Session;

class DocumentsController extends Controller
{
    public function store(Request $request){
        //dd($request->all());
        $this->validate($request,[
            'name' => 'required',
            'client' => 'required',
            'document' => 'required|min:1|array',
            'document.*' => 'mimes:pdf,jpg,jpeg'
        ]);
        //dd($request->all());
        $listdocument=ListDocument::create([
            'client_id'=>$request->client,
            'name' => $request->name,
        ]);
        foreach($request->document as $item){
            $document = $item->store('Documents');
            Documents::create([
                'list_document_id' => $listdocument->id,
                'name' => $item->getClientOriginalName(),
                'document' => $document
            ]);
        }

        Session::flash('flash_type','success');
        Session::flash('flash_message','Documents Added.');

        return redirect()->back();
    }

    public function delete($did){
       // dd($did);
        $doc= Documents::where('id',$did)
            ->whereHas('listdocument.client',function($q){
                if(Auth::User()->isAdmin == 0){
                    $q->where('user_id',Auth::User()->id);
                }
            })
            ->with('listdocument',function($q){
                $q->withCount('documents as doccount');
            })
            ->first();
        //dd($doc);
        if($doc != NULL){
            if($doc->listdocument->doccount == 1){
                //For Last Document
                ListDocument::where('id',$doc->list_document_id)
                    ->delete();
            }else{
                Documents::where('id',$did)
                    ->delete();
            }
        }else{
            return abort(404);
        }

        Session::flash('flash_type','success');
        Session::flash('flash_message','Document Deleted Successfully.');

        return redirect()->back();
    }
}
