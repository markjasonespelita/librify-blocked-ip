<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Olts};
use App\Http\Requests\StoreOltsRequest;
use App\Http\Requests\UpdateOltsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OltsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('olts.olts', [
            'olts' => Olts::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('olts.trash-olts', [
            'olts' => Olts::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($oltsId)
    {
        Olts::where('id', $oltsId)->update(['isTrash' => '0']);

        return redirect('/olts');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('olts.create-olts');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOltsRequest $request)
    {
        Olts::create(['name' => $request->name]);

        return back()->with('success', 'Olts Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Olts $olts, $oltsId)
    {
        return view('olts.show-olts', [
            'item' => Olts::where('id', $oltsId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Olts $olts, $oltsId)
    {
        return view('olts.edit-olts', [
            'item' => Olts::where('id', $oltsId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOltsRequest $request, Olts $olts, $oltsId)
    {
        $olts = Olts::findOrFail($oltsId);

        $olts->name = $request->name;

        $olts->save();

        return back()->with('success', 'Olts Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Olts $olts, $oltsId)
    {
        return view('olts.delete-olts', [
            'item' => Olts::where('id', $oltsId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Olts $olts, $oltsId)
    {

        Olts::where('id', $oltsId)->update(['isTrash' => '1']);

        return redirect('/olts');
    }

    public function search(Request $request)
    {

        $search = $request->get('search');

        // Perform the search logic
        $olts = Olts::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(10);

        return view('olts.olts', compact('olts', 'search'));
    }

    public function paginate(Request $request)
    {

        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided

        // Paginate the olts based on the 'paginate' value
        $olts = Olts::paginate($paginate); // Paginate with the specified number of items per page

        // Return the view with the paginated olts
        return view('olts.olts', compact('olts'));
    }

    public function filter(Request $request)
    {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Default query for olts
        $query = Olts::query();

        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $olts = $query->whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all olts without filtering
            $olts = $query->paginate(10);
        }

        // Return the view with olts and the selected date range
        return view('olts.olts', compact('olts', 'from', 'to'));
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Olts::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Olts::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            $restorable = Olts::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}