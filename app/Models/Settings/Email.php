<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\SettingsEmailPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;

class Email extends Model
{
    use
        SoftDeletes,
        PresentableTrait,
        Historable;
    protected $presenter = SettingsEmailPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'emails';
    protected $fillable = ["title","subject", "service", "trial", "active_plan", "card","cleanup","addons","bucket", "subscription", "invite_user", "forgot_password","occurrence","days","status","email_body","deleted_at","created_at","updated_at"];
}
