<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniesConfiguration extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = 'companies_configuration';
    protected $fillable = [
        "company_id", 
        "trial_data_deletion_days", 
        "has_setup_deletion_days", 
        "timelogs_deletion_days", 
        "snapshot_deletion_days", 
        "closeaccount_timelogs_deletion_days", 
        "closeaccount_snapshot_deletion_days",
        "active_free_plan_days",
    ];

}
