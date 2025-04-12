<?php

namespace App\Composers;

use Illuminate\Contracts\View\View;

class LocaleComposer
{
    /*
     * For front end
     */
    public function compose(View $view)
    {
        $view->with('lang', config('app.locale'));
    }
}
