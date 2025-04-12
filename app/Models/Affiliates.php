<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\AffiliatePresenter;
class Affiliates extends Model
{

    use Historable;
    use PresentableTrait;
    protected $fillable = ["tenantId", "type","publicId","registeredAt","email","name","tierId","tierName","accountId","sourceId","sourceStatus","fraudSuspicion","created_at","updated_at"];
    protected $presenter = AffiliatePresenter::class;

    protected $casts = [
        'fraudSuspicion' => 'array'
    ];
}
