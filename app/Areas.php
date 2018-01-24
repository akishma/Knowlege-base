<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * 
     */
     protected $slugFrom = 'article_name';
  //  protected $table = 'entity';
    public $timestamps = false;
}