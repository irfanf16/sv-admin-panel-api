<?php
namespace Database\Seeders;

use App\Libraries\Generic;
use Database\Seeders\ModuleSeeder;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**check if new company created then insert dummy data with current logged in user,
         *else insert dummy data() if user manually run db:seed command in empty db
         */
        $user = session('temp_user');
        DB::table('users')->insert(
            array(
                'id' => $user['id'],
                'email' => $user['email'],
                'password' => $user['password'],
                'app_password' => '',//Generic::decryptAppPassword($user['password'], $user['email']),
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone' => $user['phone'],
                'image' => $user['image'],
                'macAddress' => $user['macAddress'],
                'client_app_version' => $user['client_app_version'],
                'remember_token' => $user['remember_token'],
                'stripe_customer_id' => $user['stripe_customer_id'],
                'theme_color' => 'theme-two',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );
        //        uuid update on projects
        $this->call(ProjectUpdateUuid::class);
        $this->call(Permissions::class);
        $this->call(ModuleSeeder::class);
        $this->call(Profiles::class);
        $this->call(Companies::class);
        $this->call(ProfilesTypes::class);
        $this->call(CountrySeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(CitiesSeeder::class);

        /**
         * check if new company created then insert dummy data with current logged in user,
         *else insert dummy data if user manually run db:seed command in empty db
         */
        $latest_company = session('temp_company');
        $user = session('temp_comp');
        DB::table('companies_users')->insert(array(
            'company_id' => $latest_company->id,
            'user_id' => $user['user_id'],
            'profile_id' => 1,
            'profile_name'=>'Owner', 
            'profile_type' => 'Owner',
            'status' => $user['status'],
            'is_deleted' => $user['is_deleted'],
            'created_at' => Carbon::now(), 
            'updated_at' => Carbon::now(), 
            'is_terminated' => 0, 
            'is_employee' => 1
        ));
        $this->call(Profiles_modules::class);
        $this->call(WebApptrackingMetaSeeder::class);
        $this->call(SystemFeaturesSeeder::class);
    }
}
