<?php

namespace App\Models;

use App\Models\Client;
use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\ActivitiesFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }


    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }

    public function getPic($type = null, $side = "front")
    {
        if (!$type) {
            return asset("pics/default.jpg");
        }

        // Define all the file types and their respective directories
        $fileTypes = [
            'agency' => 'agency_pics',
            'rent_contract' => 'rent_contract_pics'
        ];

        $wanted = $fileTypes[$type];

        if ($this[$wanted] == ''||
            $this[$wanted] == 'default.png' ||
            $this[$wanted] == 'default.jpg') {
            return asset('pics/default.jpg');
        }

        $pic = @json_decode($this[$wanted], true)[$side];

        if(!$pic){
            return asset('pics/default.jpg');
        }

        return asset("uploads/activities/activity_{$this->id}/{$wanted}/" . $pic);
    }
}