<?php

namespace App\Presenters;

class CategoryFeaturesPresenter extends Presenter
{
    public function title(): string
    {
        return $this->entity->title ;
    }
}
