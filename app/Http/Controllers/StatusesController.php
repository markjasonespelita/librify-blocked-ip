<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Statuses};
use App\Http\Requests\StoreStatusesRequest;
use App\Http\Requests\UpdateStatusesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatusesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('statuses.statuses', [
            'statuses' => Statuses::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('statuses.trash-statuses', [
            'statuses' => Statuses::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($statusesId)
    {
        Statuses::where('id', $statusesId)->update(['isTrash' => '0']);

        return redirect('/statuses');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('statuses.create-statuses');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusesRequest $request)
    {
        Statuses::create(['name' => $request->name,'icon' => $request->icon, 'color' => $request->color]);

        return back()->with('success', 'Statuses Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Statuses $statuses, $statusesId)
    {
        return view('statuses.show-statuses', [
            'item' => Statuses::where('id', $statusesId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statuses $statuses, $statusesId)
    {
        return view('statuses.edit-statuses', [
            'item' => Statuses::where('id', $statusesId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusesRequest $request, Statuses $statuses, $statusesId)
    {
        $statuses = Statuses::findOrFail($statusesId);

        $statuses->name = $request->name;
        $statuses->icon = $request->icon;
        $statuses->color = $request->color;

        $statuses->save();

        return back()->with('success', 'Statuses Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Statuses $statuses, $statusesId)
    {
        return view('statuses.delete-statuses', [
            'item' => Statuses::where('id', $statusesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statuses $statuses, $statusesId)
    {

        Statuses::where('id', $statusesId)->update(['isTrash' => '1']);

        return redirect('/statuses');
    }

    public function search(Request $request)
    {

        $search = $request->get('search');

        // Perform the search logic
        $statuses = Statuses::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(10);

        return view('statuses.statuses', compact('statuses', 'search'));
    }

    public function paginate(Request $request)
    {

        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided

        // Paginate the statuses based on the 'paginate' value
        $statuses = Statuses::paginate($paginate); // Paginate with the specified number of items per page

        // Return the view with the paginated statuses
        return view('statuses.statuses', compact('statuses'));
    }

    public function filter(Request $request)
    {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Default query for statuses
        $query = Statuses::query();

        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $statuses = $query->whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all statuses without filtering
            $statuses = $query->paginate(10);
        }

        // Return the view with statuses and the selected date range
        return view('statuses.statuses', compact('statuses', 'from', 'to'));
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Statuses::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Statuses::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            $restorable = Statuses::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}
