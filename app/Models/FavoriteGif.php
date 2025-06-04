<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteGif extends Model
{
    protected $fillable = ['gif_id', 'alias', 'user_id'];
}