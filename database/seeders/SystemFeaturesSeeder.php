<?php

namespace Database\Seeders;

use App\Models\Company\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Database\Seeders\ModuleSeeder;
// use App\Models\ModuleFeatureList;
use Illuminate\Support\Facades\Http;
use App\Models\Company\SystemFeature;
use Database\Seeders\ModuleSeeder;
class SystemFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemFeature::withTrashed()->forceDelete();
        $database_name = DB::connection()->getDatabaseName();
        $insert = [];
        $modules = (new ModuleSeeder())->getModules();
        foreach ($modules as $mod_key => $module) {
            $rules = json_decode($module['rules'],1);
            foreach ($rules as $key_imp => $rule) {
                if($key_imp == 'implementation' && !empty($rule)) {
                    foreach ($rule as $key2 => $r) {
                        $systemFeature = SystemFeature::where("feature_key", $r['key'])->where("sub_module_id", $module["id"])->first();
                        if(!empty($systemFeature)) {
                            if(empty($systemFeature->feature_value)) {
                                $systemFeature->feature_value = 'Unlimited';
                                $systemFeature->deleted_at =  null;
                                if($database_name == 'crt_23') {
                                    $systemFeature->status =  1;
                                }
                                $systemFeature->save();
                            }
                        } else {
                            $data = [
                                'sub_module_id' => $module["id"],
                                'parent_module_id' => $module["parent_module_id"],
                                'feature_key' => $r["key"],
                                'feature_value' => 'Unlimited',
                                'package_id' => 0,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'deleted_at' => null,
                            ];
                            if($database_name == 'crt_23') {
                                $data["status"] =  1;
                            }
                            $insert[] = $data;
                        }
                    }
                }
                if(!in_array($key_imp, ['info', 'implementation'])) {
                    foreach ($rule as $key_imp_2 => $r2) {
                        if($key_imp_2 == 'implementation' && !empty($rule)) {
                            foreach ($r2 as $key2 => $r) {
                                $systemFeature = SystemFeature::where("feature_key", $r['key'])->where("sub_module_id", $module["id"])->first();
                                if(!empty($systemFeature)) {
                                    if(empty($systemFeature->feature_value)) {
                                        $systemFeature->feature_value = 'Unlimited';
                                        $systemFeature->deleted_at =  null;
                                        if($database_name == 'crt_23') {
                                            $systemFeature->status =  1;
                                        }
                                        $systemFeature->save();
                                    }
                                } else {
                                    $data = [
                                        'sub_module_id' => $module["id"],
                                        'parent_module_id' => $module["parent_module_id"],
                                        'feature_key' => $r["key"],
                                        'feature_value' => 'Unlimited',
                                        'package_id' => 0,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        'deleted_at' => null,
                                    ];
                                    if($database_name == 'crt_23') {
                                        $data["status"] =  1;
                                    }
                                    $insert[] = $data;
                                }
                            }
                        }
                    }
                }
                
            }
        }
        if(!empty($insert)) {
            DB::table('system_features')->insert($insert);
        }
    }
    public function runOLD()
    {
        $insert = [];
        $systemFeaturesList = [];
        $systemFeatures = DB::table('system_features')->get();
        // DB::table('system_features')->truncate();
        $module_features_list = session()->get('module_features_list');
        if(!empty($systemFeatures)) {
            $systemFeaturesList = $systemFeatures->toArray();
        }
        
        if(!empty($module_features_list)) {
            foreach ($module_features_list as $key => $features) {
                if(!empty($features['features_list'])) {
                    foreach ($features['features_list'] as $key => $feature) {
                        if(empty($feature['feature_value'])) {
                            $feature_value = 'Unlimited';
                        } else {
                            $feature_value = $feature['feature_value'];
                        }
                        if(!empty($systemFeaturesList)) {
                            foreach ($systemFeaturesList as $key => $sf) {
                                $sf = (array) $sf;
                                if($feature['id'] == $sf['feature_key']) {
                                    DB::table('system_features')->where('pf_id', $sf['pf_id'])->update([
                                        'feature_value' => $feature_value,
                                    ]);
                                    continue 2;
                                }
                            }
                        }
                        $insert[] = [
                            'parent_module_id' => $features['parent_module_id'],
                            'sub_module_id' => $features['sub_module_id'],
                            'feature_key' => $feature['id'],
                            'feature_value' => $feature_value,
                            'package_id' => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'deleted_at' => null,
                        ];
                    }
                }
            }
        }
        if(!empty($insert)) {
            DB::table('system_features')->insert($insert);
        }
        
    }
}
