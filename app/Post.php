<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use App\Enums\StatusType;
use App\Scopes\TitleScope;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $perPage = 25; 
    
    protected $dates = ['created_at', 'deleted_at']; // which fields will be Carbon-ized
    
    protected $fillable = [
        'title', 'content', 'status', 'category_id', 'user_id', 'visited'
    ];
    
    /**
     * Scope a query to only include posts of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    
    static function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TitleScope);
    }

        /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function resolveRouteBinding($value)
    // {
    //     return $this->where('slug', $value)->first() ?? abort(404);
    // }

    /**
     * 
     * Get the route key for the model.
     *
     * @return string
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
