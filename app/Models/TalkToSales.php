<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\TalkToSalesPresenter;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;

class TalkToSales extends Model
{
    use SoftDeletes, PresentableTrait, Historable;
    protected $presenter = TalkToSalesPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'talk_to_sales';
    protected $fillable = ["first_name","last_name", "number_of_employees","status","company", "email", "phone", "reason","message","created_at","updated_at","deleted_at"];
}
