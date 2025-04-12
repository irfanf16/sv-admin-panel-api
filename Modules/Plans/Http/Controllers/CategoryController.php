<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
// use App\Services\StripeService;
use Modules\Plans\Http\Requests\CategoryAddRequest;
use App\Models\Category;
use App\Models\Products;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        $categories = Category::getCategories($request->all())->orderBy('categories.id', 'desc')->paginate($request->limit ?? config('settings.record_per_page'));
        $categories_ids = $categories->pluck('id')->toArray();

        $plan_attached_categories = null;
        if($categories_ids !== null && count($categories_ids) > 0) {
            $plan_attached_categories = Products::selectRaw("
            JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.category')) AS category_id, stripe_id AS plan_id,
            CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.category')) IN  (". implode(',',$categories_ids) .") AND JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.is_popular')) =  1
            THEN stripe_id END as is_popular
            ")->whereRaw(" JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.category')) IN  (". implode(',',$categories_ids) .") ")->get();//->pluck('category_id','plan_popular')->toArray();

        }
        else{
            $plan_attached_categories = [];
        }
        return response()->json(['categories' => $categories,'products' => $plan_attached_categories]);

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
    public function store(CategoryAddRequest $request)
    {
        $allowed_fields = (new Category())->getFillable();
        $skip_fields = ["deleted_at","created_at","updated_at"];
        $category = new Category();
        foreach ($allowed_fields as $key => $field) {
            if(in_array($field, $skip_fields)) {
                continue;
            }
            $category->{$field} = $request->{$field};
        }
        $category->save();
        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $plan_attached_categories = [];
        if(!is_null($id)){
            $plan_attached_categories = Products::selectRaw("
            JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.category')) AS category_id, stripe_id AS plan_id,
            CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.category')) IN  (". $id .") AND JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.is_popular')) =  1
            THEN stripe_id END as is_popular
            ")->whereRaw(" JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.category')) IN  (". $id .") ")->first();
        }

//        $product = Products::whereJsonContains('product->metadata->type',trim('plans'))->first();
//        dd($product->toArray());
        return response()->json([
            'category' => $category,
            'attached_plans' => $plan_attached_categories != null ?  $plan_attached_categories->plan_id : null
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
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Jsonable
     */
    public function update(CategoryAddRequest $request, $id)
    {
        $allowed_fields = (new Category())->getFillable();
        $skip_fields = ["deleted_at","created_at","updated_at"];
        $category = Category::findOrFail($id);
        foreach ($allowed_fields as $key => $field) {
            if(in_array($field, $skip_fields)) {
                continue;
            }
            $category->{$field} = $request->{$field};
        }
        $category->save();
        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $products = Products::getProducts(["metadata" => ["category_id" => $id]])->get();
        if($products->count() == 0) {
            $products = Products::getProducts(["metadata" => ["category_id" => (int) $id]])->get();
            if($products->count() == 0) {
                $category->delete();
                return response()->json([
                    'message' => 'Category deleted successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'Category is attached with the products. You must delete the products first',
                    'products' => $products->pluck('product')->pluck('name')->toArray()
                ], 422);
            }

        } else {
            return response()->json([
                'message' => 'Category is attached with the products. You must delete the products first',
                'products' => $products->pluck('product')->pluck('name')->toArray()
            ], 422);
        }

    }
}
