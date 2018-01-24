<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
    protected $table = 'test';
    public $timestamps = false;
}