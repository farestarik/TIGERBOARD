<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Client;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Exports\ActivityExport;
use App\Imports\ActivityImport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{

    public $perPage = 25;

    public $user_tenant_id = 0;

    public function __construct()
    {
        $this->middleware(['permission:create_activities'])->only('create');
        $this->middleware(['permission:read_activities'])->only('index');
        $this->middleware(['permission:update_activities'])->only('edit');
        $this->middleware(['permission:delete_activities'])->only('destroy');



    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activities = Activity::query();

        // Apply `tenant_id` condition **only if the user is NOT an owner**
        if (!hasRole('owner')) {
            $activities->where('tenant_id', userTenant('id'));
        }

        if ($request->all()) {
            $activities = $activities
            ->where(function($query)use($request){
                return $query->where("name", "like", "%" . $request->search . "%")
                ->orWhere("code", "like", "%" . $request->search . "%")
                ->orWhere("mission_belongs", "like", "%" . $request->search . "%")
                ->orWhere("address", "like", "%" . $request->search . "%")
                ->orWhere("tax_registeration_number", "like", "%" . $request->search . "%")
                ->orWhere("notes", "like", "%" . $request->search . "%");
                })->where(function($query) use($request){
                    $query->orWhereHas("client", function ($query) use ($request) {
                        return $query->where("name", "like", "%" . $request->search. "%")
                        ->where("code", "like", "%" . $request->search. "%")
                        ->where("email", "like", "%" . $request->search. "%")
                        ->where("national_id", "like", "%" . $request->search. "%");
                    });
                });

        }


        if (!$activities) {
            abort(500);
        }

        $activities = $activities->paginate($this->perPage);

        return view("dashboard.activities.index")
            ->with("activities", $activities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::select(['id', 'name']);
        $tenants = [];
        if(!hasRole('owner')){
            $clients = $clients->where('tenant_id', userTenant('id'));
        }else{
            $tenants = Tenant::all();
        }
        $clients = $clients->get();
        return view("dashboard.activities.create", compact('clients', 'tenants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'tax_registeration_number' => 'nullable|alpha_num',
            'client_id' => 'nullable|exists:clients,id',
            'agency' => 'nullable|alpha_num',
            'agency_pics.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB per image
            'rent_contract' => 'nullable|alpha_num',
            'rent_contract_pics.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB per image
        ]);

        $data = [
            'name' => $request->name ?? NULL,
            'address' => $request->address ?? NULL,
            'tax_registeration_number' => $request->tax_registeration_number ?? NULL,
            'mission_belongs' => $request->mission_belongs ?? NULL,

            'agency' => $request->agency ?? 0,
            'rent_contract' => $request->rent_contract ?? 0,

            'client_id' => $request->client_id ?? NULL,
            'tenant_id' => $request->tenant_id ?? userTenant(),

            'notes' => $request->notes ?? NULL,
            "active" => $request->active ?? 1,
            'created_by' => id(),
            'updated_by' => 0
        ];

        $data['code'] = generateCode(7, "ACT_" . Activity::max("id") + 1, "activities") ?? NULL;
        $activity = Activity::create($data);

        if (!$activity) {
            return abort(500, __("site.contact_support"));
        }

        $dataToBeUpdated = $this->handleUploadPics($activity, $request);


        $activity->update($dataToBeUpdated);

        session()->put('success', __('site.added_successfully'));

        return redirect()->route('dashboard.activities.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $activity = Activity::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return redirectWithError('Activity Not Found!', 'dashboard.activities.index');
        }




        $clients = Client::select(['id', 'name']);

        $tenants = [];
        if(!hasRole('owner')){
            $clients = $clients->where('tenant_id', userTenant('id'));
            if($activity->tenant_id != userTenant('id')){
                return redirectWithError('Activity Not Found !', 'dashboard.activities.index');
            }
        }else{
            $tenants = Tenant::all();
        }
        $clients = $clients->get();


        return view("dashboard.activities.edit", compact('activity', 'clients', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {


        $this->validate($request, [
            'name' => 'required',
            'tax_registeration_number' => 'nullable|alpha_num',
            'national_id' => 'nullable|alpha_num',
            'client_id' => 'nullable|exists:clients,id',
            'agency' => 'nullable|alpha_num',
            'agency_pics.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB per image
            'rent_contract' => 'nullable|alpha_num',
            'rent_contract_pics.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB per image
        ]);

        $data = [
            'name' => $request->name ?? NULL,
            'address' => $request->address ?? NULL,
            'tax_registeration_number' => $request->tax_registeration_number ?? NULL,
            'mission_belongs' => $request->mission_belongs ?? NULL,

            'agency' => $request->agency ?? 0,
            'rent_contract' => $request->rent_contract ?? 0,
'tenant_id' => $request->tenant_id ?? userTenant(),
            'client_id' => $request->client_id ?? NULL,
            'notes' => $request->notes ?? NULL,
            "active" => $request->active ?? 1,
            'updated_by' => id()
        ];


        $dataToBeUpdated = $this->handleUploadPics($activity, $request);

        $data = array_merge($data, $dataToBeUpdated);

        if(!$activity->code){
            $data['code'] = generateCode(7, "ACT_" . $activity->id, "activities") ?? NULL;
        }

        if (!$activity->update($data)) {
            abort(500, __("site.contact_support"));
        }

        session()->put('success', __('site.updated_successfully'));

        return redirect()->route('dashboard.activities.edit', $activity->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        if(!hasRole('owner')){
            if($activity->tenant_id !== userTenant()){
                return redirectWithError('Activity Not Found !', 'dashboard.activities.index');
            }
        }

        if (!$activity->delete()) {
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.activities.index");
    }

    public function import_page(Request $request)
    {
        return view('dashboard.activities.import');
    }

    public function import(Request $request)
    {
        Excel::import(new ActivityImport, request()->file('activities_csv'));
        session()->put('success', 'Imported Successfully!');
        return redirect()->route('dashboard.activities.import');
    }

    public function export(Request $request)
    {
        return Excel::download(new ActivityExport, 'activities.xlsx');
    }

    public function handleUploadPics(Activity $activity, Request $request)
    {
        // Define all the file types and their respective directories
        $fileTypes = [
            'agency_pics' => 'agency_pics',
            'rent_contract_pics' => 'rent_contract_pics'
        ];

        $data = [];

        // Loop through each file type

        foreach ($fileTypes as $fieldName => $directory) {
            $pics = [];
            // Check if files exist
            if ($request->has($fieldName)) {
                foreach ($request->file($fieldName) as $side => $image) {

                    // Generate a unique filename using microtime
                    $filename = microtime(true) . '_' . uniqid('ActivityID_' . $activity->id . '_', true) . $side . '.' . $image->getClientOriginalExtension();

                    // Replace dots with underscores for clean filenames
                    $filename = str_replace('.', '_', $filename) . '.' . $image->getClientOriginalExtension();

                    // Store the file in the respective client folder
                    $image->move(public_path("uploads/activities/activity_{$activity->id}/{$directory}"), $filename);


                    $current = @json_decode($activity[$fieldName], true);
                    if ($current) {
                        $current[$side] = $filename;
                        $pics[$fieldName] = $current;
                    } else {
                        $pics[$fieldName][$side] = $filename;
                    }
                }
                // Add the file paths to the $data array

                $data[$fieldName] = @json_encode($pics[$fieldName]);
            }
        }
        return $data;
    }
}