<?php

namespace App\Presenters;

class CategoryFeaturesListPresenter extends Presenter
{
    public function title(): string
    {
        return $this->entity->feature_title ;
    }
}
