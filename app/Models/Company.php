<?php

namespace App\Models;
use App\Services\Base64FileUploader;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\CompanyPresenter;
use Illuminate\Support\Facades\DB;
class Company extends Model
{

    use Historable;
    use PresentableTrait;

    protected $fillable = [
                            "title",
                            "emp_code_format",
                            "no_of_employee",
                            "plan_id",
                            "plan_staus",
                            "subscription_id",
                            "plan_expiry",
                            "created_at",
                            "updated_at",
                            "status",
                            "super_admin_id",
                            "advocate_id",
                            "instance_id",
                            "screen_capture_image_size",
                            "screen_capture_duration",
                            "logo",
                            "timezone",
                            "company_initial",
                            "formation_type",
                            "status",
                            "has_setup",
                            "company_admin_emails",
                            "payment_status",
                            "plan_users"
                        ];

    protected $presenter = CompanyPresenter::class;
//    protected $appends = ['advocate'];
//    public function getAdvocateAttribute()
//    {
//        dd($adv_users);
//        // Assuming you've already resolved $adv_users, return the relevant name here
//        return isset($this->attributes['advocate_id']) ? $adv_users[$this->attributes['advocate_id']] ?? null : null;
//    }


    protected $casts = [
        'company_admin_emails' => 'array'
    ];

    public function scopeCompanyWithTotalUsers($query, $fields=[])
    {
        $prefix = config('database.connections.'.config('database.default').'.prefix');
        if(empty($fields)) {
            $fields = [
                "companies.id",
                "no_of_employee",
                "title",
                "logo",
                "companies.status",
                "companies.created_at",
                "has_setup",
                "users.first_name",
                "users.last_name",
            ];
        }

        if(!in_array('advocate_id', $fields)){
            $fields[] = 'companies.advocate_id';
        }
        $prefix = DB::getTablePrefix();

        $fields = array_values($fields);
        $query->select($fields);
        $query->selectRaw('(SELECT COUNT(`'.$prefix.'companies_users`.`user_id`) FROM `'.$prefix.'companies_users`
            INNER JOIN `'.$prefix.'users` ON `'.$prefix.'users`.`id`=`'.$prefix.'companies_users`.`user_id`
            WHERE `'.$prefix.'companies_users`.`company_id` = `'.$prefix.'companies`.`id` AND is_deleted = 0
            AND `'.$prefix.'users`.`email` IS NOT NULL
        ) AS total_users

        ');


       $query->leftJoin('companies_users', 'companies_users.company_id', '=', 'companies.id')
            ->leftJoin('users', 'users.id', '=', 'companies_users.user_id');
//       $query->selectRaw(
//           'CASE WHEN advocate_id = '.$prefix.'users.id THEN CONCAT('.$prefix.'users.first_name," ", '.$prefix.'users.last_name) END as advocate'
//       );
//
//       $fields[] = 'users.id';
        $query->groupBy($fields)
        ->OrderBy('id', 'desc');
        // ->OrderBy('total_users', 'desc');

        return $query;
    }

    public function scopeCompanyWithUsers($query, $id, $fields=[], $search = '', $status = '')
    {

        if(empty($fields)) {
            $fields = [
                "user_id",
                "email",
                "first_name",
                "last_name",
                "phone",
                "image",
                "profile_type",
                "profile_name",
                "companies_users.status",
                "is_terminated",
                "is_employee",
                "web_tracking",
            ];
        }
        $query = $query->select($fields)
            ->leftJoin('companies_users', 'companies_users.company_id', '=', 'companies.id')
            ->leftJoin('users', 'users.id', '=', 'companies_users.user_id');
        $query = $query->where('company_id', $id)->whereNotNull('email')->where("is_deleted", "0");
        if(!empty($search)) {
            $query->where(function($query) use ($search) {
                $query = $query->where('email', 'LIKE', '%'.$search.'%');
                $query = $query->orWhere(\DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$search."%");
            });
        }
        if(!empty($status)) {
            $query = $query->where('companies_users.status', $status);
        }
        $query = $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc');
        return $query;
    }
    public function scopeCompanyOwner($query, $company_id, $fields=[], $search = '', $status = '')
    {
        if(empty($fields)) {
            $fields = [
                "user_id",
                "email",
                "first_name",
                "last_name",
                "phone",
                "image",
                "profile_type",
                "profile_name",
                "companies_users.status",
                "companies.title",
                "companies.id",
                "is_terminated",
                "is_employee",
                "web_tracking",
                "formation_type",

            ];
        }
        $query = $query->select($fields)
            ->leftJoin('companies_users', 'companies_users.company_id', '=', 'companies.id')
            ->leftJoin('users', 'users.id', '=', 'companies_users.user_id');
        $query = $query->where(['company_id' => $company_id, 'profile_type' => 'Owner', 'companies_users.status' => 'active', 'is_terminated' => 0]);
        $query = $query->orderBy('user_id', 'asc');
        return $query;
    }


    public function scopeCompanyWithActiveTrial($query)
    {
        $fields = [
            "user_id",
            "email",
            "first_name",
            "last_name",
            "phone",
            "image",
            "profile_type",
            "profile_name",
            "companies_users.status",
            "companies.title",
            "companies.id",
            "is_terminated",
            "is_employee",
            "web_tracking",
            "formation_type",
            "plan_staus",
            "plan_expiry",
            "subscription_id",
            "price_id"
        ];
        $query = $query->select($fields)
                        ->leftJoin('companies_users', 'companies_users.company_id', '=', 'companies.id')
                        ->leftJoin('users', 'users.id', '=', 'companies_users.user_id');
        $query = $query->where(['profile_type' => 'Owner', 'companies_users.status' => 'active', 'is_terminated' => 0]);
        $query = $query->where(['plan_staus' => 'trialing']);
        $query = $query->orderBy('id', 'desc');
        return $query;
    }

    public function scopegetCompanyInstance($query , $id) {
        return $query->join('instances','instances.id','=','companies.instance_id')
        ->where(['companies.id' => $id])
        ->select([
            'companies.*',
            'instance',
            DB::raw('AES_DECRYPT(db_host,"'.env('ENC_KEY').'") as db_host'),
            DB::raw('AES_DECRYPT(db_port,"'.env('ENC_KEY').'") as db_port'),
            DB::raw('AES_DECRYPT(db_username,"'.env('ENC_KEY').'") as db_username'),
            DB::raw('AES_DECRYPT(db_password,"'.env('ENC_KEY').'") as db_password'),
            DB::raw('AES_DECRYPT(db_driver,"'.env('ENC_KEY').'") as db_driver'),
        ]);
    }

    public function scopesearchSubscriptionProduct($query , $data) {
        if(isset($data["price_id"])) {
            $query->where("price_id", $data['price_id']);
        }

        if(isset($data["plan_id"])) {
            $query->whereIn("plan_id", is_array($data['plan_id']) ? $data['plan_id'] : [$data['plan_id']]);
        }

        return $query;
    }
    public function updateCompany($request, $sendCompanyData = false, Base64FileUploader $fileUploader = null ) {

        $company_id =   $request->company_id;
        $title = $request->title;
        $grace_period = $request->gracePeriod;
        $logo  = $request->logo;
        $payment_status  = $request->payment_status;

        if (is_null($fileUploader)) {
            $fileUploader = new Base64FileUploader();
        }

        $url = $fileUploader->handle($logo);

        $this->where('id', $company_id)->update([
            'title' => trim($title),'logo' => trim($url), 'grace_period' => $grace_period, 'payment_status' => $payment_status
        ]);
        if($sendCompanyData){
            return $this->where('id', $company_id)->first();
        }

    }

}
