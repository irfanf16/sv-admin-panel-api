<?php

namespace Modules\Articles\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Articles;
use Modules\Plans\Http\Requests\FeatureAddRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        $articles = new Articles();
        if(!empty($request->id)) {
            $articles = $articles::where('articles.id', $request->id);
        } else if(!empty($request->search)) {
            $articles = $articles->where('articles.title', 'LIKE', '%'.$request->search.'%');
        }
        if(!empty($request->status)) {
            $articles = $articles->where('articles.status', $request->status);
        }
        if((boolean) $request->withoutpages != true) {
            $articles = $articles->with('pages');
        }
        $articles = $articles->paginate($request->limit?? config('settings.record_per_page'));
        return response()->json($articles);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('plansandpackages::create');
    }
 
  
    public function store(FeatureAddRequest $request)
    {
        
        DB::beginTransaction();
        try{
           
            DB::commit(); 
            
        }
        catch(\Exception $e)
        {
            
            DB::rollBack();
            
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return ModuleFeature::where('id' , $id)->with('featuresList')->get();
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
    public function update(FeatureAddRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
           
            DB::commit(); 
            
        }
        catch(\Exception $e)
        {
            
            DB::rollBack();
            
        }
        
         
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

}
