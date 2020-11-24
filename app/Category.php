<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Article;


class Category extends Model
{
    use Sluggable;

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function getParent()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id')
            ->withDefault(['name' => '---']);
    }
	
    public function getChild()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles()
    {
        return $this->belongsTo(Article::class);
    }


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}