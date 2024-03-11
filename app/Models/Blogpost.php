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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'blogpost_name',
        'category_id',
        'blogpost_desc',
        'upload_file',
    ];
}
