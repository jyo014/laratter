<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    /** @use HasFactory<\Database\Factories\TweetFactory> */
    use HasFactory;


    protected $fillable = ['tweet','image_path'];

    //連携の設定　自分が多
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function liked()
    {
    return $this->belongsToMany(User::class)->withTimestamps();
    }

     // 🔽 1対多の関係
  public function comments()
  {
    return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
  }
}
