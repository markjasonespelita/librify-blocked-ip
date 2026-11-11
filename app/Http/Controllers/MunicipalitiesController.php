<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Municipalities};
use App\Http\Requests\StoreMunicipalitiesRequest;
use App\Http\Requests\UpdateMunicipalitiesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MunicipalitiesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('municipalities.municipalities', [
            'municipalities' => Municipalities::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('municipalities.trash-municipalities', [
            'municipalities' => Municipalities::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($municipalitiesId)
    {
        Municipalities::where('id', $municipalitiesId)->update(['isTrash' => '0']);

        return redirect('/municipalities');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('municipalities.create-municipalities');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMunicipalitiesRequest $request)
    {
        Municipalities::create(['name' => $request->name]);

        return back()->with('success', 'Municipalities Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipalities $municipalities, $municipalitiesId)
    {
        return view('municipalities.show-municipalities', [
            'item' => Municipalities::where('id', $municipalitiesId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipalities $municipalities, $municipalitiesId)
    {
        return view('municipalities.edit-municipalities', [
            'item' => Municipalities::where('id', $municipalitiesId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMunicipalitiesRequest $request, Municipalities $municipalities, $municipalitiesId)
    {
        $municipalities = Municipalities::findOrFail($municipalitiesId);

        $municipalities->name = $request->name;

        $municipalities->save();

        return back()->with('success', 'Municipalities Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Municipalities $municipalities, $municipalitiesId)
    {
        return view('municipalities.delete-municipalities', [
            'item' => Municipalities::where('id', $municipalitiesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipalities $municipalities, $municipalitiesId)
    {

        Municipalities::where('id', $municipalitiesId)->update(['isTrash' => '1']);

        return redirect('/municipalities');
    }

    public function search(Request $request)
    {

        $search = $request->get('search');

        // Perform the search logic
        $municipalities = Municipalities::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(10);

        return view('municipalities.municipalities', compact('municipalities', 'search'));
    }

    public function paginate(Request $request)
    {

        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided

        // Paginate the municipalities based on the 'paginate' value
        $municipalities = Municipalities::paginate($paginate); // Paginate with the specified number of items per page

        // Return the view with the paginated municipalities
        return view('municipalities.municipalities', compact('municipalities'));
    }

    public function filter(Request $request)
    {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Default query for municipalities
        $query = Municipalities::query();

        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $municipalities = $query->whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all municipalities without filtering
            $municipalities = $query->paginate(10);
        }

        // Return the view with municipalities and the selected date range
        return view('municipalities.municipalities', compact('municipalities', 'from', 'to'));
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Municipalities::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Municipalities::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            $restorable = Municipalities::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}