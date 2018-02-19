<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature_ignore extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
     protected $table = 'links';
     public $timestamps = false;
}