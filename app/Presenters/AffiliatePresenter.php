<?php

namespace App\Presenters;

class AffiliatePresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return $this->entity->name;
    }
}
