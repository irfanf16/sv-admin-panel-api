<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniesIndustry extends Model
{
    use HasFactory;

    protected $table = "companies_industry";
    protected $fillable = ["name"];

}
