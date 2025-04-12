<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebAppTrackingMeta extends Model
{
    use HasFactory;
    protected $table = "web_app_tracking_meta";
    protected $fillable = ["app","description"];
    public $timestamps = true;
}
