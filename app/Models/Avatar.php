<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    //L'avatar appartient a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
