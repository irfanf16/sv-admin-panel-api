<?php
namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\App\Http\Requests\CreatePostEmail;
use App\Models\Settings\Email;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query =  Email::query();
        if($request->has('filter') && !empty($request->filter)){

            $query->where('service', '=',$request->filter);
        }
        //get if request related to search
        if($request->has('search') && !empty($request->search)){

            $search = strtolower($request->search);
            $query->whereRAW(' ( (title LIKE "%'.$search.'%" ) OR ( subject LIKE "%'.$search.'%" ) )');
        }
        return $query->orderBy('emails.id', 'desc')->paginate($request->limit ?? config('settings.record_per_page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostEmail $request){
        $email = new Email();
        $allowed_fields = $email->getFillable();
        $skip_fields = ["deleted_at","created_at","updated_at"];
        foreach ($allowed_fields as $key => $field) {
            if(in_array($field, $skip_fields)) {
                continue;
            }
            $email->{$field} = $request->{$field};
        }
        $email->save();
        return response()->json([
            'email' => $email,
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $email = Email::findOrFail($id);
        return response()->json([
            'email' => $email,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('settings::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePostEmail $request, $id)
    {
        $allowed_fields = (new Email())->getFillable();
        $skip_fields = ["deleted_at","created_at","updated_at"];
        $email = Email::findOrFail($id);
        foreach ($allowed_fields as $key => $field) {
            if(in_array($field, $skip_fields)) {
                continue;
            }
            $email->{$field} = $request->{$field};
        }
        $email->save();
        return response()->json([
            'email' => $email,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $email = Email::find($id);
        if(!empty($email)) {
            $email->delete();
            return response()->json([ 'success' => true,  'message' => 'Deleted']);
        }
        return response()->json([
            'success' => false,  'message' => 'Not found!'
        ], 404);
    }

}

