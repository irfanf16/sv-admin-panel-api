<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\UserTableMaster;
use App\Services\StripeService;
class AddOwnersInStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-owners-in-stripe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Do you wish to continue?')) {
            $fields = [
                "users.id",
                "users.email",
                "users.first_name",
                "users.last_name",
                "users.phone",
                "users.stripe_customer_id",
                "users.updated_at",
            ];
            $user_companies = UserTableMaster::select($fields)->join('companies_users', 'companies_users.user_id', '=', 'users.id')
                ->where([
                    'companies_users.profile_type' => 'Owner', 
                    'companies_users.is_deleted' => 0, 
                    'companies_users.is_terminated' => 0,
                    'companies_users.status'=>'active'
                ])
                ->whereNotNull('users.email')
                ->where(function ($query) {
                    $query->whereNull('users.stripe_customer_id')->orWhere('users.stripe_customer_id','=','');
                })
                ->groupBy($fields)
                ->get();
            if($user_companies->count() > 0) {
                $stripeService = new StripeService();
                foreach ($user_companies as $key => $uc) {
                    $customer = $stripeService->upsertCustomer($uc->email, [
                        "email" => $uc->email,
                        "name" => $uc->first_name . ' ' . $uc->last_name,
                        "phone" => $uc->phone,
                    ]);
                    $this->line('Customer:'. $uc->email. ' has Added in Stripe with Stripe Id='. $customer->id);
                    $this->line('-----------------');
                }
            } else {
                $this->line('It seems all the owners are already in the stripe.');
            }
           
        }
    }
}
