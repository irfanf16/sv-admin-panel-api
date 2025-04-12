<?php

namespace App\Presenters;

class MasterUsersPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        if(empty($this->entity->first_name) && empty($this->entity->first_name)) {
            return $this->entity->email;
        }
        return $this->entity->first_name.' '.$this->entity->last_name;
    }
}
