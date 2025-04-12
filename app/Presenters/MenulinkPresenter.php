<?php

namespace App\Presenters;

class MenulinkPresenter extends Presenter
{
    public function menuclass()
    {
        return $this->entity->menuclass;
    }
}
