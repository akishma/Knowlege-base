<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
class Description extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
     protected $table = 'description';
     public $timestamps = false;
     use Searchable;
     
         public function searchableAs()
    {
        return 'data';
    }
    
}