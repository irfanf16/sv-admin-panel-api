<?php

namespace Modules\Contacts\Models;

use Laracasts\Presenter\PresentableTrait;
use Modules\Contacts\Presenters\ModulePresenter;
use App\Models\base;
use App\Traits\Historable;

class Contact extends Base
{
    use Historable;
    use PresentableTrait;

    protected $presenter = ModulePresenter::class;

    protected $guarded = ['my_name', 'my_time'];

    public function uri($locale = null): string
    {
        return url('/');
    }
}
