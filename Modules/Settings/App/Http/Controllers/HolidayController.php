<?php
namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\holiday;
use Illuminate\Http\Request;
use Modules\Settings\App\Http\Requests\CreatePostEmail;
use App\Models\Settings\Email;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return  Configuration::orderBy('id')->paginate($request->limit ?? config('settings.configurations'));
    }

    public function holidays()
    {
        return  holiday::paginate($request->limit ?? config('settings.configurations'));
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
    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:300',
            'dates' => 'required|date',
            'year' => 'required|integer',
            'description'=> 'nullable'
        ]);

        // Create a new record
        $record = holiday::create($validatedData);

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'data' => $record,
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {

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
    public function update(Request $request, $id)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:300',
            'dates' => 'required|date',
            'year' => 'required|integer',
            'description'=> 'nullable'
        ]);

        if (!empty($id)){
            // Create a new record
            $record = holiday::where('id', $id)->update($validatedData);

            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Record created successfully',
                'data' => $record,
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

    }
}

