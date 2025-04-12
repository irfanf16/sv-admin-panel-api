<?php

namespace App\Presenters;

class RolePresenter extends Presenter
{
    public function title(): string
    {
        return $this->entity->name;
    }
}
