<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ResetPassword extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'token',
        'email',
        'user_id',
        'created_at',
        'updated_at',
    ];
    protected $table = 'reset_password';
}
