<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
// use App\Services\StripeService;
use Modules\Plans\Http\Requests\CategoryAddRequest;
use App\Models\CategoryFeatures;
use App\Models\CategoryFeaturesList;

class CategoryFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index($id, Request $request)
    {
        return response()->json([
            'category_features' => CategoryFeatures::with("featuresList")->where("category_id", $id)->get(),
        ]);
    }
    public function list(Request $request)
    {
        return response()->json([
          'category_features' => CategoryFeatures::with("featureList")->get(),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     * @return Jsonable
     */
    public function create()
    {
        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Jsonable
     */
    public function store($category_id, Request $request)
    {
        foreach ($request->all() as $key => $r) {
            $categoryFeatures = null;
            if(isset($r['id'])) {
                $categoryFeatures = CategoryFeatures::find($r['id']);
            }
            if(empty($categoryFeatures)) {
                $categoryFeatures = new CategoryFeatures();
            }
            $categoryFeatures->title = $r["title"];
            $categoryFeatures->category_id = $category_id;
            $categoryFeatures->save();
            $categoryFeautersList = [];
            foreach ($r["category_features_list"] as $key => $feature) {

                if(isset($feature['id'])) {
                    CategoryFeaturesList::where("id", $feature["id"])->update([
                        "feature_title" => $feature['feature_title'],
                        "plan" => $feature["plan"],
                        "plan_value" => $feature["planInputs"],
                        "category_id" => $category_id
                    ]);
                } else {
                    $categoryFeautersList[] = new CategoryFeaturesList([
                        "feature_title" => $feature['feature_title'],
                        "plan" => $feature["plan"],
                        "plan_value" => $feature["planInputs"],
                        "category_id" =>    $category_id
                    ]);
                }
            }

            if(!empty($categoryFeautersList)) {
                $categoryFeatures->featureList()->saveMany($categoryFeautersList);
            }
        }

        return response()->json([
            'category_features' => CategoryFeatures::where("category_id", $category_id)->with('featureList')->get(),
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        return response()->json([
            'category_features' => CategoryFeatures::with("featureList")->where("category_id", $id)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function edit($id)
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $category_id, $feature_id
     * @return Jsonable
     */
    public function destroy($category_id, $id)
    {
        CategoryFeatures::where("id",$id)->delete();
        CategoryFeaturesList::where('category_feature_id', $id)->delete();
        return response()->json([]);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $category_id, $feature_id
     * @return Jsonable
     */
    public function destroyFeatureList($category_id, $id)
    {
        CategoryFeaturesList::where('id', $id)->delete();
        return response()->json([]);
    }
}
