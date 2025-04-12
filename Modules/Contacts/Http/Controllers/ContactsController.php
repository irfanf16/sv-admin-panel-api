<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Contacts\Filters\FilterOr;
use Modules\Contacts\Models\Contact;
use Modules\Contacts\Http\Requests\FormRequest;

class ContactsController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    // public function index(Request $request)
    // {
    //     return $request->user();
    // }
    public function index(Request $request): LengthAwarePaginator
    {
        try {
            $data = QueryBuilder::for(Contact::class)
            ->allowedSorts(['created_at', 'name', 'email', 'message'])
            ->allowedFilters([
                AllowedFilter::custom('name,email,message', new FilterOr()),
            ])
            ->paginate($request->input('per_page'));

        return $data;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('contacts::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(FormRequest $request)
    {
        $contact = Contact::create($request->validated());
        return $contact;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('contacts::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Contact $contact)
    {
        return $this->sendResponse($contact, '');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Contact $contact, FormRequest $request)
    {
        try {
            $contact->update($request->validated());
            return $this->sendResponse($contact, '');
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
    }
}
