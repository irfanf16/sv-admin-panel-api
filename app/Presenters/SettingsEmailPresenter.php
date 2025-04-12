<?php

namespace App\Presenters;

class SettingsEmailPresenter extends Presenter
{
    public function title(): string
    {
        return $this->entity->title ;
    }
}
