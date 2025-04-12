<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
use App\Models\Company\Module as CModule;
use App\Models\ModuleFeature;
use App\Models\ModuleFeatureList;

class PlanModuleController extends Controller
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
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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

    public function getModules(Request $request, $parent_id = 0) {

        $search = $request->has('search') ? strtolower($request->search) : '';
        // \DB::enableQueryLog();
        $modules = new CModule();
        if($search == ''){
            $modules = $modules->where('parent_module_id', $parent_id);
        }
        if(!empty($search)){
            $modules = $modules->where('title', 'LIKE', '%'.$search.'%');
        }
        $modules = $modules->with('children')
            // ->with('subModuleFeatures')
            // ->with('featuresList')
            ->get();

        // dd(\DB::getQueryLog());
        return $modules;
    }
}
