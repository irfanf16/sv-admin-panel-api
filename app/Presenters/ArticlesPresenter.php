<?php

namespace App\Presenters;

class ArticlesPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return $this->entity->title ;
    }
}
