<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
    protected $table = 'features';
    public $timestamps = false;
}