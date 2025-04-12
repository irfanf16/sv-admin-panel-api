<?php

namespace App\Presenters;

class CompanyUserPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        if(empty($this->entity->profile_name)) {
            return '';
        }
        return $this->entity->profile_name;
    }
}
