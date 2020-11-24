<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Category;

class Article extends Model
{
    use Sluggable;

    protected $guarded = [];

    protected $hidden = ['updated_at'];



    public function category()
    {
        return $this->belongsTo(Category::class)->select(['id', 'name']);
    }
	
    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name']);
    }
	
	


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}