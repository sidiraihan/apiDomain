<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ['name','status','owner','expiration_date'];
    
    public function ownerData()
    {
        return $this->belongsTo('App\User', 'owner');
    }
}
