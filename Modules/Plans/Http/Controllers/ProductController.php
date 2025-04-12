<?php

namespace Modules\Plans\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
use Modules\Plans\App\Http\Requests\UpdateProductStatusPublic;
use Modules\Plans\Http\Requests\ProductsAddRequest;
use Modules\Plans\Http\Requests\ProductsUpdateRequest;
use App\Models\Products;
use App\Services\Base64FileUploader;

class ProductController extends Controller
{
    private $stripeService = null;

    public function __construct()
    {
        $this->stripeService = new StripeService();
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        $search['query'] = "active:'true'";
        if (!empty($request->type)) {
            $search['query'] .= " AND metadata['type']:'" . $request->type . "'";
        }
        return response()->json([
            'products' => $this->stripeService->searchProducts($search),
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function productsLocal(Request $request)
    {
//        dd($request->all());
        $category_ids = [];
        $products = Products::getProducts($request->except(['limit', 'page','categories']))
            ->with('companies')
            ->paginate($request->limit ?? config('settings.record_per_page'));

        if ($request->has("categories") && $request->categories == "1") {
            if ($products != null && $products->isNotEmpty()) {
                $category_ids = $products->pluck('product.metadata.category')->toArray();
                $result = Category::whereIn('id', $category_ids)->pluck('id','published')->toArray();
                return response()->json(["products" => $products, "categories_status" => $result]);
            }
        }

        return response()->json($products);
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function productLocal(Request $request)
    {
        $product = Products::where('stripe_id', $request->id)->first();

        if (empty($product)) {
            return response()->json([
                'product' => [],
            ]);
        } else {
            $product = $product->makeHidden(['id', 'stripe_id']);
            $product = $product->toArray();
            $product = $product['product'];
        }

        if (isset($product['metadata']['modules'])) {
            $product['modules'] = explode(',', $product['metadata']['modules']);
            unset($product['metadata']['modules']);
        }
        if (isset($product['metadata']['module_features_list'])) {
            $product['module_features_list'] = explode(',', $product['metadata']['module_features_list']);
            unset($product['metadata']['module_features_list']);
        }
        foreach ($product['prices'] as $key => $price) {
            if ($price['billing_scheme'] == 'tiered') {
                $product['prices'][$key] = $this->stripeService->retrievePrice($price['id'], ['expand' => ['tiers']])->toArray();
            }
        }
        return response()->json([
            'product' => $product,
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
    public function store(ProductsAddRequest $request, Base64FileUploader $fileUploader)
    {

        $post = $request->all();
        if (isset($post["image"]) && !empty($post["image"])) {
            $url = $fileUploader->handle($post["image"]);
            $completeUrl = url($url);
            $post["images"] = [$completeUrl];
            unset($post['image']);
        }
        $modules = isset($post['modules']) ? $post['modules'] : [];
        $module_features_list = isset($post['module_features_list']) ? $post['module_features_list'] : [];
        $addons = isset($post['addons']) ? $post['addons'] : [];
        if (!empty($modules)) {
            $post['metadata']['modules'] = implode(',', $modules);
        } else {
            $post['metadata']['modules'] = '';
        }
        if (!empty($module_features_list)) {
            $post['metadata']['module_features_list'] = implode(',', $module_features_list);
        } else {
            $post['metadata']['module_features_list'] = '';
        }

        if (!empty($addons)) {
            $post['metadata']['addons'] = implode(',', $addons);
        } else {
            $post['metadata']['addons'] = '';
        }
        unset($post['modules']);
        unset($post['module_features_list']);
        unset($post['addons']);

        ['product' => $product, 'prices' => $prices] = $this->stripeService->createProduct($post);

        if (!empty($prices)) {
            //retrieve price detail from stripe if billing scheme is tiered and save updated tiered price data in local database
            foreach ($prices as $key => $price) {
                if ($price['billing_scheme'] == 'tiered') {
                    $prices[$key] = $this->stripeService->retrievePrice($price['id'], ['expand' => ['tiers']])->toArray();
                }
            }
        }


        $product['prices'] = $prices;

        $products = new Products();
        $products->stripe_id = $product['id'];
        $products->product = $product;
        $products->save();

        $productReturn = $products->toArray();
        $productReturn['modules'] = $modules;
        $productReturn['module_features_list'] = $module_features_list;
        $productReturn['addons'] = $addons;
        return response()->json([
            'product' => $productReturn,
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        ['product' => $product, 'prices' => $prices] = $this->stripeService->product($id);
        if (isset($product['metadata']['modules'])) {
            $product['modules'] = explode(',', $product['metadata']['modules']);
            unset($product['metadata']['modules']);
        }
        if (isset($product['metadata']['module_features_list'])) {
            $product['module_features_list'] = explode(',', $product['metadata']['module_features_list']);
            unset($product['metadata']['module_features_list']);
        }

        if (isset($product['metadata']['addons'])) {
            $product['addons'] = explode(',', $product['metadata']['addons']);
            unset($product['metadata']['addons']);
        }
        $product['prices'] = $prices;

        return response()->json([
            'product' => $product,
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
    public function update(ProductsUpdateRequest $request, $id, Base64FileUploader $fileUploader)
    {
        $post = $request->all();

        if (isset($post["image"]) && !empty($post["image"])) {
            $url = $fileUploader->handle($post["image"]);
            $completeUrl = url($url);
            $post["images"] = [$completeUrl];
            unset($post['image']);
        }
        $modules = isset($post['modules']) ? $post['modules'] : [];
        $module_features_list = isset($post['module_features_list']) ? $post['module_features_list'] : [];
        $addons = isset($post['addons']) ? $post['addons'] : [];
        if (!empty($modules)) {
            $post['metadata']['modules'] = implode(',', $modules);
        } else {
            $post['metadata']['modules'] = '';
        }
        if (!empty($module_features_list)) {
            $post['metadata']['module_features_list'] = implode(',', $module_features_list);
        } else {
            $post['metadata']['module_features_list'] = '';
        }
        if (!empty($addons)) {
            $post['metadata']['addons'] = implode(',', $addons);
        } else {
            $post['metadata']['addons'] = '';
        }
        unset($post['modules']);
        unset($post['module_features_list']);
        unset($post['addons']);

        ['product' => $product, 'prices' => $prices] = $this->stripeService->updateProduct($post, $id);
//        dd($post,'d',$product);
        $product['prices'] = $prices;
        $dbProduct = Products::where("stripe_id", $product['id'])->first();
        $notFound = false;
        if (empty($dbProduct)) {
            $dbProduct = new Products();
            $notFound = true;
        }
        $dbProduct->stripe_id = $product['id'];
        $dbProduct->product = $product;
        if ($notFound) {
            $dbProduct->save();
        } else {
            $dbProduct->update();
        }
        $productReturn = $dbProduct->toArray();
        $productReturn['modules'] = $modules;
        $productReturn['module_features_list'] = $module_features_list;
        $productReturn['addons'] = $addons;
        return response()->json([
            'product' => $productReturn,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        $product = $this->stripeService->deleteProduct($id);
        $local_product = Products::where('stripe_id', $id)->first();
        if (!empty($local_product)) {
            $local_product->delete();
        }
        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function prices()
    {
        return response()->json([
            'prices' => $this->stripeService->searchPrices(['query' => "active:'true'"]),
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function pricesDestroy($id, $stripe_id)
    {
        return response()->json([
            'price' => $this->stripeService->deletePrice($id, $stripe_id),
        ]);
    }

    public function currentPackage($company_id)
    {
        return response()->json([
            'product' => Products::getCurrentPackage($company_id)->first(),
        ]);
    }

    public function updateProductPublicStatus(UpdateProductStatusPublic $request)
    {
        $result = (new Products())->updateProductStatus($request);
        if ($result['status'] == 'error')
            return response()->json($result, 500);
        else
            return response()->json($result, 200);
    }

    public function updatePlansSorting(Request $request)
    {
        $data = $request->input('data_to_sorted');
        $stripe_ids = $request->input('stripe_ids') ;
        $category_id = $request->input('category_id');
        //don't check boolean parameters with filled method
        if($request->filled(['data_to_sorted', 'stripe_ids', 'category_id']))
        {
            // Initialize the CASE statement
            $caseString = "CASE \n";

            // Loop through the data array to generate CASE conditions
            foreach ($data as $item) {
                $caseString .= "    WHEN stripe_id = '{$item['stripe_id']}' THEN {$item['index']} \n";
            }

            // Close the CASE expression
            $caseString .= "END";

            Products::whereIn('stripe_id', $stripe_ids)
                ->whereJsonContains('product->metadata->category',trim($category_id))
                ->update(['sort_by' => \DB::raw($caseString), 'category_id' =>  $category_id]);

            // Return the generated CASE expression as JSON
            return response()->json([
                'case_statement' => $caseString
            ]);
        }else{
            return response()->json([
                'error' => 'Missing required parameters'
            ]);
        }
    }


}
