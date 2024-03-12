<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogpost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "blogpost";

    // public function comment()
    // {
    //     return $this->hasMany(Comment::class);
    // }

    // public function like()
    // {
    //     return $this->belongsToMany(Like::class);
    // }

    public function like()
    {
        return $this->hasMany(Like::class, 'blogpost_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'blogpost_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'blogpost_name',
        'category_id',
        'user_id',
        'blogpost_desc',
        'upload_file',
    ];
}
