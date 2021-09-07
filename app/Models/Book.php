<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'year'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function libraries()
    {
        return $this->belongsToMany(Library::class)->orderBy('id', 'DESC');
    }
}
