<?php

namespace App\Presenters;

class TalkToSalesPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return $this->entity->first_name . ' '  . $this->entity->first_name;
    }
}
