<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Main_claster extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
    protected $table = 'main_claster';
    public $timestamps = false;
    
    public function clasters(){
        return $this->hasMany('App\Claster', 'parent');
    }
}