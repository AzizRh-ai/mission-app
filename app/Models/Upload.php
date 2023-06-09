<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = ['title','path','filetype'];

    //Appartient à user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
