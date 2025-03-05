<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:create_settings'])->only('create');
        $this->middleware(['permission:read_settings'])->only('index');
        $this->middleware(['permission:update_settings'])->only('edit');
        $this->middleware(['permission:delete_settings'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tenant_id = session('userTenantID', 0);
        $setting = Settings::where('tenant_id', $tenant_id)->first();

        return view('dashboard.settings.index', compact('setting'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $setting = Settings::findOrFail($id);

        $old_img = $setting->logo;

        $data = [
            "phone" => $request->phone,
            "company_name" => $request->company_name,
            "commercial_register" => $request->commercial_register,
            "tax_num" => $request->tax_num ?? NULL,
            "address" => $request->address ?? NULL,
            "max_document_size" => $request->max_document_size * 1024 ?? NULL,
        ];

        if(request()->has('img')){
            $photo = $request->img;
            $photo_file = time() . '.' . $photo->getClientOriginalExtension();
            if($photo->move('pics', $photo_file)){
                if($old_img !== 'default.png'){
                    Storage::disk("public_folder")->delete("pics/".$old_img);
                }
            }
            $data['logo'] = $photo_file;
        }




        $setting->update($data);

        session()->put('success', __("site.updated_successfully"));

        return \redirect()->route('dashboard.settings.index');
    }
}