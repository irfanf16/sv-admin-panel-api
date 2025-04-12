<?php

namespace Modules\Plans\Http\Controllers;

use App\Models\Company;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\ModuleFeature;
use App\Models\ModuleFeatureList;
use App\Models\Products;
use Modules\Plans\Http\Requests\FeatureAddRequest;
use Modules\Plans\Http\Requests\FeatureUpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Base64FileUploader;
use Bkwld\Croppa\Facades\Croppa;

class PFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request) : JsonResponse{

        $featuresList = ModuleFeatureList::getFeaturesList();
        if($request->has('search'))
        {
            $search = $request->search;
            if(!empty($search))
            $featuresList = $featuresList->where('feature_label', 'like', "%$search%");
        }
        $featuresList = $featuresList->orderBy('id', 'desc')->paginate($request->limit ?? config('settings.record_per_page'));
        $featureIds = $featuresList !== null && $featuresList->isNotEmpty() ? $featuresList->pluck('id')->toArray() : [];

        $featurePlans = (new \App\Models\Products)->featurePlans($featureIds);

        // Extract and count features
        $features = DB::table(DB::raw('(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(t1.module_features_list, \',\', numbers.n), \',\', -1) AS feature_id
            FROM (SELECT JSON_UNQUOTE(JSON_EXTRACT(product, \'$.metadata.module_features_list\')) AS module_features_list FROM typicms_products) t1
            JOIN (SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
                     UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
                     UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15
                     UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20) numbers
                ON CHAR_LENGTH(t1.module_features_list)
                    - CHAR_LENGTH(REPLACE(t1.module_features_list, \',\', \'\')) >= numbers.n - 1) AS feature_list'))
            ->select('feature_id', DB::raw('COUNT(*) AS feature_count'))
            ->when($featureIds, function ($query, $featureIds) {
                return $query->whereIn('feature_id', $featureIds);
            })
            ->groupBy('feature_id')
            ->orderBy('feature_count', 'DESC')
            ->get();

        return response()->json([
            'features' => $featuresList,
            'featuresCount' => $features->toArray(),
            'featuresPlans' => $featurePlans
        ]);
    }
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function old_index(Request $request) {
        $module_features_ids = [];
        $featuresList = ModuleFeatureList::paginate($request->limit ?? config('settings.record_per_page'));
        foreach ($featuresList as $key => $featureList) {
            if(!in_array($featureList->module_features_id, $module_features_ids)) {
                $module_features_ids[] = $featureList->module_features_id;
            }
        }
        $features = ModuleFeature::wherein('id' , $module_features_ids)->get();
        if(!empty($features)) {
            $features = $features->toArray();
        } else {
            $features = [];
        }
        foreach ($featuresList as $key => $featureList) {
            $find = array_search($featureList->module_features_id, array_column($features, 'id'));
            if($find !== false) {
                $featureList->parent_module_id = $features[$find]['parent_module_id'];
                $featureList->sub_module_id = $features[$find]['sub_module_id'];
                $featureList->status = $features[$find]['status'];
            }
        }
        return response()->json([
            'features' => $featuresList,
        ]);
    }
    public function indexOLD()
    {
        // DB::enableQueryLog();
        $features = ModuleFeature::with(['featuresList'])->get();
        // dd($features, DB::getQueryLog());
        return $features;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('plansandpackages::create');
    }


    public function store(FeatureAddRequest $request,Base64FileUploader $fileUploader)
    {

        DB::beginTransaction();
        try{
            $featuresList = [];
            // $feature = ModuleFeature::create($request->only('parent_module_id','sub_module_id','status'));
            $matchThese = [
                'parent_module_id'   =>  $request->parent_module_id,
                'sub_module_id'     =>  $request->sub_module_id
            ];
            $feature = ModuleFeature::updateOrCreate($matchThese,$request->only('parent_module_id','sub_module_id','status'));
            $oldrule = ['input_field','unlimited'];
            foreach ($request->module_features_list as $key => $f) {
                $fl = new ModuleFeatureList;
                $rules = !in_array($f['rule'],$oldrule) ? $f['rule'] : $f['feature_value'];
                $fl->type = $f['type'];
                $fl->feature_key = $f['feature_key'];
                $fl->feature_value = $rules;//$f['feature_value'];
                $fl->feature_label = $f['feature_label'];
                $fl->rule = $f['rule'];
                $fl->status = $f['status'];
                $fl->content = $f['content'];
                $userUploadedImage = isset($f['image']) ? $f['image'] : '';
                if(!empty($userUploadedImage)) {
                    $url = $fileUploader->handle($userUploadedImage);
                    $fl->image = $url;
                    $f['image'] = $url;
                }
                $featuresList[] = $fl;
            }
            $feature->featuresList()->saveMany($featuresList);
            DB::commit();
            $feature->features_list = $featuresList;
            return [
                'feature' => $feature,
                // 'features_list' => $featuresList
            ] ;
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        $fl = ModuleFeatureList::findOrFail($id);
        $feature = ModuleFeature::where('id' , $fl->module_features_id)->first();
        $feature->features_list = [$fl];
        return response()->json([
            'feature' => $feature,
        ]);
        // return ModuleFeature::where('id' , $id)->with('featuresList')->get();

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('plansandpackages::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Jsonable
     */
    public function update(FeatureUpdateRequest $request,Base64FileUploader $fileUploader, $id = 0)
    {
        DB::beginTransaction();
        $featureList = [];
        $feature = [];
        try{
            $matchThese = [
                'parent_module_id'   =>  $request->parent_module_id,
                'sub_module_id'     =>  $request->sub_module_id
            ];
            $feature = ModuleFeature::updateOrCreate($matchThese,$request->only('parent_module_id','sub_module_id','status'));
            $oldrule = ['input_field','unlimited'];
            if(!empty($request->module_features_list)) {
                foreach ($request->module_features_list as $key => $f) {
                    $fl = ModuleFeatureList::findOrFail($f['id']);
                    $rules = !in_array($f['rule'],$oldrule) ? $f['rule'] : $f['feature_value'];
                    $fl->type = $f['type'];
                    $fl->feature_key = $f['feature_key'];
                    $fl->feature_value = $rules;//$f['feature_value'];
                    $fl->feature_label = $f['feature_label'];
                    $fl->rule = $f['rule'];
                    $fl->status = $f['status'];
                    $fl->content = $f['content'];
                    $userUploadedImage = isset($f['image']) ? $f['image'] : '';
                    if(!empty($userUploadedImage)) {
                        try {
                            Croppa::delete($fl->image);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        $url = $fileUploader->handle($userUploadedImage);
                        $fl->image = $url;
                        $f['image'] = $url;
                    }
                    $fl->save();
                    $featureList[] = $fl;
                }
                DB::commit();
            }
            $feature->features_list = $featureList;
            return response()->json([
                'feature' => $feature,
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }


    }
    public function updateOLD(FeatureAddRequest $request,Base64FileUploader $fileUploader, $id = 0)
    {
        DB::beginTransaction();
        $featuresList = [];
        $feature = [];
        try{
            // $feature = ModuleFeature::find($id) ?? abort(404, 'Feature Not Found.');
            $matchThese = [
                'parent_module_id'   =>  $request->parent_module_id,
                'sub_module_id'     =>  $request->sub_module_id
            ];
            $feature = ModuleFeature::updateOrCreate($matchThese,$request->only('parent_module_id','sub_module_id','status'));

            $feature->featuresList()->delete();
            if(!empty($request->module_features_list)) {
                foreach ($request->module_features_list as $key => $f) {
                    $fl = new ModuleFeatureList;
                    $fl->type = $f['type'];
                    $fl->feature_key = $f['feature_key'];
                    $fl->feature_value = $f['feature_value'];
                    $fl->feature_label = $f['feature_label'];
                    $fl->status = $f['status'];
                    $fl->content = $f['content'];
                    $userUploadedImage = isset($f['image']) ? $f['image'] : '';
                    if(!empty($userUploadedImage)) {
                        $url = $fileUploader->handle($userUploadedImage);
                        $fl->image = $url;
                        $f['image'] = $url;
                    }
                    if(isset($f['id']) && !empty($f['id'])) {
                        $hasRestore = ModuleFeatureList::withTrashed()->where('id', $f['id'])->restore();
                        if($hasRestore) {
                            $oldFeature = ModuleFeatureList::find($f['id']);
                            if(!empty($oldFeature->image)) {
                                try {
                                    Croppa::delete($oldFeature->image);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                            $fl->id = $f['id'];
                            $feature->featuresList()->where('id', $f['id'])->update($f);
                        }
                    } else {
                        $featuresList[] = $fl;
                    }
                }
                if(!empty($featuresList)) {
                    $feature->featuresList()->saveMany($featuresList);
                }

                DB::commit();
            }
            // $moduoleFeatureList = ModuleFeatureList::all();
            // if(!empty($moduoleFeatureList)) {
            //     $moduoleFeatureListArray = $moduoleFeatureList->toArray();
            // } else {
            //     $moduoleFeatureListArray = [];
            // }
            // dd($moduoleFeatureListArray);
            return response()->json([
                'feature' => $feature,
                'features_list' => $feature->featuresList()
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $fields = [
                'products.stripe_id',
                'products.product',
            ];
            $products = Products::getSubscriptionProducts(['fields' => $fields, 'feature_list_id' => $id])->get();
            foreach ($products as $key => $product) {
                if(isset($product->product['metadata']['module_features_list'])) {
                    $module_features_list_ids = explode(",", $product->product['metadata']['module_features_list']);
                    if(in_array($id,$module_features_list_ids)) {
                        return response()->json([
                            'message' => 'Feature is attached with a subscription.We can\'t delete.',
                        ], 401);
                    }
                }
            }
            $featurList = ModuleFeatureList::find($id);
            if(empty($featurList)) {
                return response()->json([
                    'message' => 'Feature Not Found.',
                ], 404);
            }
            if(!empty($featurList->image)) {
                try {
                    Croppa::delete($featurList->image);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            $featurList->delete();
            return response()->json([
                'success' => 'Deleted Successfully.'
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroyOLD($id)
    {
        try{
            $feature = ModuleFeature::find($id) ?? abort(404, 'Feature Not Found.');
            $featuresList = $feature->featuresList()->get();
            if($featuresList) {
                foreach ($featuresList as $key => $featurList) {
                    if(!empty($featurList->image)) {
                        try {
                            Croppa::delete($featurList->image);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }
            }
            $feature->featuresList()->delete();
            $feature->delete();
            return response()->json([
                'success' => 'Deleted Successfully.'
            ]);
        } catch(\Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function getModulesFeaturesByModuleId($sub_module_id = 0) {


        return ModuleFeature::where('sub_module_id' ,  $sub_module_id)->with('featuresList')->get()->toArray();
    }
}
