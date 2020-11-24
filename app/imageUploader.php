<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class imageUploader extends Model
{
    protected $fillable = ['image', 'section'];
}