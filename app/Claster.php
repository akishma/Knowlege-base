<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claster extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
    protected $table = 'claster';
    public $timestamps = false;
    
        public function main_claster()
    {
        return $this->belongsTo('App\Main_claster', 'parent');
    }
}