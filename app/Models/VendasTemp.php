<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendasTemp extends Model
{
    //
    public function item()
    {
    	return $this->belongsTo('App\Models\Produtos','produto_id','id');
    }
}
