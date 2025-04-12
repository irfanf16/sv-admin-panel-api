<?php

namespace App\Presenters;

class ModuleFeaturePresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return 'Module Feature';
    }
}
