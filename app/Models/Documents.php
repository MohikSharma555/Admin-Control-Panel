<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Block\Element\Document;

class Documents extends Model
{
    use HasFactory;
    protected $fillable = [
        'list_document_id','name','document'
    ];

    public function listdocument(){
        return $this->belongsTo(ListDocument::class,'list_document_id');
    }
}
