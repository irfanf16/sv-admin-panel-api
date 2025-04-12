<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
use App\Models\Company\Module;
use App\Models\ModuleFeature;
use App\Models\ModuleFeatureList;
use App\Services\Base64FileUploader;
use Bkwld\Croppa\Facades\Croppa;
use Modules\Plans\Http\Requests\FeatureAddRequest;
class PFeaturesListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('plansandpackages::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('plansandpackages::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Jsonable
     */
    public function store(FeatureAddRequest $request,Base64FileUploader $fileUploader)
    {
        return response()->json([], 200);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('plansandpackages::show');
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
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function featuresListByFeatureId($feature_id = 0, $search = null) {
        // \DB::enableQueryLog();
        $features = ModuleFeature::where('id', $feature_id)->with('featuresList')->get();
        // dd(\DB::getQueryLog());
        return $features;
    }
}
