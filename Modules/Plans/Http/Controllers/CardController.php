<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
class CardController extends Controller
{
    private $stripeService = null;
    public function __construct() {
        $this->stripeService = new StripeService();
    }
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        $search['query'] = "active:'true'";
        if(!empty($request->type)) {
            $search['query'] .= " AND metadata['type']:'".$request->type."'";
        }
        return response()->json([
            'products' => $this->stripeService->searchProducts($search),
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
    public function store(SubscriptionAddRequest $request)
    {
        $subscription = $this->stripeService->getCardsByCustomerId($request->all());
        return response()->json([
            'subscription' => $subscription,
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function getCardsByCustomerId($customer_id)
    {
        return response()->json([
            'cards' => $this->stripeService->getCardsByCustomerId($customer_id),
            'customer_id' => $customer_id
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function getCardByCustomerIdCardId($customer_id, $card_id)
    {
        return response()->json([
            'card' => $this->stripeService->getCardByCustomerIdCardId($customer_id, $card_id),
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
    public function update(ProductsUpdateRequest $request, $id)
    {
        ['product' => $product, 'prices' => $prices]= $this->stripeService->updateProduct($request->all(), $id);
        $product['prices'] = $prices;
        $dbProduct = Products::where("stripe_id", $product['id'])->first();
        $notFound = false;
        if(empty($dbProduct)) {
            $dbProduct = new Products();
            $notFound = true;
        }
        $dbProduct->stripe_id = $product['id'];
        $dbProduct->product = $product;
        if($notFound) {
            $dbProduct->save();
        } else {
            $dbProduct->update();
        }
        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        return response()->json([
            'product' => $this->stripeService->deleteProduct($id),
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
    public function pricesDestroy($id)
    {
        return response()->json([
            'price' => $this->stripeService->deletePrice($id),
        ]);
    }
}
