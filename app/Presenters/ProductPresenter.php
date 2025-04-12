<?php

namespace App\Presenters;

class ProductPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return 'Product ID=' . $this->entity->id;
    }
}
