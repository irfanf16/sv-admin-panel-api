<?php

namespace App\Presenters;

class TaxonomyPresenter extends Presenter
{
    public function title(): string
    {
        return $this->entity->name ?? '';
    }
}
