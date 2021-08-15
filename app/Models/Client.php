<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','sin',
    ];
   
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function listdocuments(){
         return $this->hasMany(ListDocument::class);
    }

    public function notes(){
        return $this->hasMany(Note::class);
    }
}