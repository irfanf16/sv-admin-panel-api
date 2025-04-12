<?php

namespace App\Models\Company;

// use App\Libraries\Generic;
use Illuminate\Database\Eloquent\Model;

class ProfileModules extends Model
{
    
    protected $table = "profile_modules";
    protected $fillable = ["module_id", "permission_id", "profile_id", "status", "permanent_disabled"];

    public function getModulesPerm($view = 3,$profile_type=6)
    {
        $result = [];
        switch($profile_type)
        {
            case 6:
                    $sql = $this->get()
                                ->toArray();
                    if ( is_array($sql) && count($sql) > 0 ) {
                        foreach( $sql as $q ) {
                            $result[$q['module_id']] = 0;
                        }
                    }
                    return $result;
                break;
            default:
                    $sql = $this->where('profile_id',session('profile_id'))
                                ->where('permission_id',$view)
                                ->get()
                                ->toArray();
                    if ( is_array($sql) && count($sql) > 0 ) {
                        foreach( $sql as $q ) {
                            $result[$q['module_id']] = $q['status'];
                        }
                    }
                    return $result;
                break;
        }
    }

}
