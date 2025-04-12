<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\InvoicesPresenter;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;

class Invoices extends Model
{
    use SoftDeletes, PresentableTrait, Historable;
    protected $presenter = InvoicesPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'invoices';
    protected $fillable = ["invoice_id", "stripe_customer_id", "subscription_id", "invoice","status","created_at","updated_at","deleted_at"];

    protected $casts = [
        'invoice' => 'array'
    ];
    public function scopeGetInvoices($query, $params) {
        if(isset($params['fields']) && !empty($params['fields'])) {
            $query->select($params['fields']);
        } else {
            $query->select(
                $this->table.'.invoice_id',
                $this->table.'.stripe_customer_id',
                $this->table.'.status',
                $this->table.'.subscription_id',
                $this->table.'.created_at',
                $this->table.'.updated_at',
                $this->table.'.invoice',
                'users.first_name',
                'users.last_name',
                'companies.title as company_title',
                'companies.id as company_id',
                'companies.status as company_status',
                'companies.has_setup as company_has_setup',
            );
        }
        
        $query->join('companies','companies.subscription_id','=', $this->table.'.subscription_id');
        $query->join('users','users.id','=', 'companies.super_admin_id');
        
        if(isset($params['stripe_customer_id']) && !empty($params['stripe_customer_id'])) {
            $query->where($this->table.'.stripe_customer_id', $params['stripe_customer_id']);
        }
        if(isset($params['subscription_id']) && !empty($params['subscription_id'])) {
            $query->where($this->table.'.subscription_id', $params['subscription_id']);
        }
        if(isset($params['status']) && !empty($params['status'])) {
            $query->where($this->table.'.status', $params['status']);
        }
        if(isset($params['company_id']) && !empty($params['company_id'])) {
            $query->where('companies.id', $params['company_id']);
        }
        
        return $query;
    }
}
