<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Libraries\Masterdb;


class WebAppTrackingSummariezModel extends Model
{
	// Automatically generate UUID for the primary key when a record is created
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'web_app_tracking_summariez';
    protected $fillable = [
    						'id',
                            'user_id',
                            'type',
    						'app_name',
    						'date_entry',
    						'spent_time',
    						'url_count',
    						'domain_name',
    						'full_url',
                            'timezone',
                            'productivity',
					        'created_at',
					        'updated_at',
    					];

    public static function update_or_insert($data=[])
    {
        return self::insert($data);
    }

}