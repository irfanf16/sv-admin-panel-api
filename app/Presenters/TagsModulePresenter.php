<?php

namespace App\Presenters;

class TagsModulePresenter extends Presenter
{
    /**
     * Get title.
     */
    public function title(): string
    {
        return $this->entity->tag;
    }
}
