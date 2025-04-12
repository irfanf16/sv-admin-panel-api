<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Invoices;
use App\Services\StripeService;

class InvoiceController extends Controller
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
        return response()->json([
            'invoices' => Invoices::getInvoices($request->all())->latest()->paginate($request->limit ?? config('settings.record_per_page')),
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'invoice' => $this->stripeService->updateInvoice($id, $request->all())
        ]);
    }
    public function upcoming(Request $request, $customer_id)
    {
        return response()->json([
            'invoice' => $this->stripeService->upcomingInvoice($customer_id, $request->all())
        ]);
    }
    public function pay($invoice_id) {
        return response()->json([
            'invoice' => $this->stripeService->payInvoice($invoice_id)
        ]);
    }
}
