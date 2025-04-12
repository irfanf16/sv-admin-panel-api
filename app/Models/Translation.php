<?php

namespace App\Models;

use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use App\Presenters\TranslationsPresenter;
use App\Traits\Historable;

class Translation extends Base
{
    use HasTranslations;
    use Historable;
    use PresentableTrait;
    protected $table = 'portal_translations';
    protected $presenter = TranslationsPresenter::class;

    protected $guarded = [];

    public $translatable = [
        'translation',
    ];
}
