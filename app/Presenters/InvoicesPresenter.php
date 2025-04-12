<?php

namespace App\Presenters;

class InvoicesPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return 'Invoice ID=' . $this->entity->invoice_id;
    }
}
