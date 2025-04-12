<?php

namespace App\Presenters;

class TranslationsPresenter extends Presenter
{
    /**
     * Return name.
     */
    public function title(): string
    {
        return $this->entity->key;
    }
}
