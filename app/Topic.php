<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Topic extends Model
{

       protected $guarded = [];
      

    public function user()
     {
            return $this->belongsTo('App\User');
     }

     public function comments(){

       return $this->morphMany('App\Comment' , 'commentable')->latest();

     }

     public function category()
     {
         return $this->belongsTo('App\Category' );
     }
     
     public function getCategory($id)
     {
       return Category::find($id);
     }
}
