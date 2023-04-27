<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    use HasFactory;

    protected $table = 'chatmessages';

    protected $fillable = ['role_user', 'message_user', 'role_ai', 'message_ai'];

}
