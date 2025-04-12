<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Chatbot extends Model
{
    protected $primaryKey = "id";
    protected $table = 'chat_bot_client';
    protected $fillable = ["client_email", "client_name", "client_phone", "client_comments", "rating_stars", "created_at","updated_at"];
}
