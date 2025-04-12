<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\HistoryPresenter;

class History extends Base
{
    use PresentableTrait;

    protected $table = 'portal_history';

    protected $presenter = HistoryPresenter::class;

    protected $guarded = [];

    protected $appends = ['href'];

    protected $casts = [
        'old' => 'object',
        'new' => 'object',
    ];

    public $order = 'id';

    public $direction = 'desc';

    public function historable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHrefAttribute(): ?string
    {
        if ($this->historable) {
            return $this->historable->editUrl();
        }

        return null;
    }
}
