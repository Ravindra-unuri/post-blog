<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Like extends Model
{
    use HasFactory;
    protected $table = 'like';

    public function blogpost()
    {
        return $this->belongsTo(Blogpost::class, 'blogpost_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        // Assuming $value is in UTC format
        $utcDate = Carbon::parse($value);
        $indianDate = $utcDate->timezone('Asia/Kolkata')->format('d-m-Y H:i:s');

        return $indianDate;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'blogpost_id'
    ];
}
