<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniesTimezone extends Model
{
    use HasFactory;

    protected $table = "companies_timezone";
    protected $fillable = ["name","zone_value","created_at"];
}
