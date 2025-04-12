<?php

namespace App\Presenters;

class AddonsHistoryPresenter extends Presenter
{
    /**
     * Get title by concatenating first_name and last_name.
     */
    public function title(): string
    {
        return 'CompanyId:'. $this->entity->company_id. '. SubscriptionId: '. $this->entity->subscription_id. '. PriceId: '. $this->entity->stripe_price_id;
    }
}
