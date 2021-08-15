<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id','name'
    ];

    protected $table='list_documents';
    public function client(){
        return $this->belongsTo(Client::class);
    }
    
    public function documents(){
        return $this->hasMany(Documents::class,'list_document_id');
    }
   
}
