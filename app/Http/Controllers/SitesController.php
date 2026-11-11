<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Sites};
use App\Http\Requests\StoreSitesRequest;
use App\Http\Requests\UpdateSitesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SitesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sites.sites', [
            'sites' => Sites::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('sites.trash-sites', [
            'sites' => Sites::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($sitesId)
    {
        Sites::where('id', $sitesId)->update(['isTrash' => '0']);

        return redirect('/sites');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sites.create-sites');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSitesRequest $request)
    {
        Sites::create(['name' => $request->name,'municipalities_id' => $request->municipalities_id,'olts_id' => $request->olts_id,'statuses_id' => $request->statuses_id,'ip_address' => $request->ip_address,'remarks' => $request->remarks]);

        return back()->with('success', 'Sites Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sites $sites, $sitesId)
    {
        return view('sites.show-sites', [
            'item' => Sites::where('id', $sitesId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sites $sites, $sitesId)
    {
        return view('sites.edit-sites', [
            'item' => Sites::where('id', $sitesId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSitesRequest $request, Sites $sites, $sitesId)
    {
        $sites = Sites::findOrFail($sitesId);

        $sites->name = $request->name;
        $sites->municipalities_id = $request->municipalities_id;
        $sites->olts_id = $request->olts_id;
        $sites->statuses_id = $request->statuses_id;
        $sites->ip_address = $request->ip_address;
        $sites->remarks = $request->remarks;

        $sites->save();

        return back()->with('success', 'Sites Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Sites $sites, $sitesId)
    {
        return view('sites.delete-sites', [
            'item' => Sites::where('id', $sitesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sites $sites, $sitesId)
    {

        Sites::where('id', $sitesId)->update(['isTrash' => '1']);

        return redirect('/sites');
    }

    public function search(Request $request)
    {

        $search = $request->get('search');

        // Perform the search logic
        $sites = Sites::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(10);

        return view('sites.sites', compact('sites', 'search'));
    }

    public function paginate(Request $request)
    {

        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided

        // Paginate the sites based on the 'paginate' value
        $sites = Sites::paginate($paginate); // Paginate with the specified number of items per page

        // Return the view with the paginated sites
        return view('sites.sites', compact('sites'));
    }

    public function filter(Request $request)
    {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');

        // Default query for sites
        $query = Sites::query();

        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $sites = $query->whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all sites without filtering
            $sites = $query->paginate(10);
        }

        // Return the view with sites and the selected date range
        return view('sites.sites', compact('sites', 'from', 'to'));
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Sites::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            $deletable = Sites::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            $restorable = Sites::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}