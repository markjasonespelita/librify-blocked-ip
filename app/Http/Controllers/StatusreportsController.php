<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Statusreports};
use App\Http\Requests\StoreStatusreportsRequest;
use App\Http\Requests\UpdateStatusreportsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatusreportsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('statusreports.statusreports', [
            'statusreports' => Statusreports::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('statusreports.trash-statusreports', [
            'statusreports' => Statusreports::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($statusreportsId)
    {
        Statusreports::where('id', $statusreportsId)->update(['isTrash' => '0']);

        return redirect('/statusreports');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('statusreports.create-statusreports');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusreportsRequest $request)
    {
        Statusreports::create(['title' => $request->title,'basis' => $request->basis,'total_sites' => $request->total_sites,'done' => $request->done,'cant_be_reached' => $request->cant_be_reached,'offline' => $request->offline,'not_found' => $request->not_found,'users_id' => $request->users_id]);

        return back()->with('success', 'Statusreports Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Statusreports $statusreports, $statusreportsId)
    {
        return view('statusreports.show-statusreports', [
            'item' => Statusreports::where('id', $statusreportsId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statusreports $statusreports, $statusreportsId)
    {
        return view('statusreports.edit-statusreports', [
            'item' => Statusreports::where('id', $statusreportsId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusreportsRequest $request, Statusreports $statusreports, $statusreportsId)
    {
        $statusreports = Statusreports::findOrFail($statusreportsId);

        $statusreports->title = $request->title;
        $statusreports->basis = $request->basis;
        $statusreports->total_sites = $request->total_sites;
        $statusreports->done = $request->done;
        $statusreports->cant_be_reached = $request->cant_be_reached;
        $statusreports->offline = $request->offline;
        $statusreports->not_found = $request->not_found;
        $statusreports->users_id = $request->users_id;

        $statusreports->save();

        return back()->with('success', 'Statusreports Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Statusreports $statusreports, $statusreportsId)
    {
        return view('statusreports.delete-statusreports', [
            'item' => Statusreports::where('id', $statusreportsId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statusreports $statusreports, $statusreportsId)
    {

        Statusreports::where('id', $statusreportsId)->update(['isTrash' => '1']);

        return redirect('/statusreports');
    }

    public function search(Request $request)
    {

        $search = $request->get('search');

        // Perform the search logic
        $statusreports = Statusreports::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(10);

        return view('statusreports.statusreports', compact('statusreports', 'search'));
    }

    public function paginate(Request $request)
    {

        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided

        // Paginate the statusreports based on the 'paginate' value
        $statusreports = Statusreports::paginate($paginate); // Paginate with the specified number of items per page

        // Return the view with the paginated statusreports
        return view('statusreports.statusreports', compact('statusreports'));
    }

    public function filter(Request $request)
    {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Default query for statusreports
        $query = Statusreports::query();

        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $statusreports = $query->whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all statusreports without filtering
            $statusreports = $query->paginate(10);
        }

        // Return the view with statusreports and the selected date range
        return view('statusreports.statusreports', compact('statusreports', 'from', 'to'));
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Statusreports::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Statusreports::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            $restorable = Statusreports::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}