<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyCloseAccount extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'company_close_account';

    protected $fillable = [
        'company_id',
        'plan_type',
        'reason',
        'message',
        'closing_time',
    ];


}
