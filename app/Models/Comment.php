<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewCommentNotification;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';

    public function blogpost()
    {
        return $this->belongsTo(Blogpost::class, 'blogpost_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            $commenterName = $comment->user->first_name; // Assuming 'name' is the attribute for commenter's name
            $commentDetail = $comment->comment;

            Mail::to($comment->blogpost->user->email)->send(new NewCommentNotification($commenterName, $commentDetail));
        });
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'blogpost_id',
        'comment'
    ];
}
