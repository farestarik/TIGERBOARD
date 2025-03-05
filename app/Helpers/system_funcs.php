<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Helpers\TigerPrint;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Settings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

define("COUNTRIES_ARRAY", [
    "AF" => "Afghanistan",
    "AX" => "Åland Islands",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "AS" => "American Samoa",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AI" => "Anguilla",
    "AQ" => "Antarctica",
    "AG" => "Antigua and Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AW" => "Aruba",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BM" => "Bermuda",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia and Herzegovina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean Territory",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos (Keeling) Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo, The Democratic Republic of The",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'ivoire",
    "HR" => "Croatia",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands (Malvinas)",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French Southern Territories",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GI" => "Gibraltar",
    "GR" => "Greece",
    "GL" => "Greenland",
    "GD" => "Grenada",
    "GP" => "Guadeloupe",
    "GU" => "Guam",
    "GT" => "Guatemala",
    "GG" => "Guernsey",
    "GN" => "Guinea",
    "GW" => "Guinea-bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HM" => "Heard Island and Mcdonald Islands",
    "VA" => "Holy See (Vatican City State)",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran, Islamic Republic of",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IM" => "Isle of Man",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JE" => "Jersey",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea, Democratic People's Republic of",
    "KR" => "Korea, Republic of",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao People's Democratic Republic",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macao",
    "MK" => "Macedonia, The Former Yugoslav Republic of",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MQ" => "Martinique",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "YT" => "Mayotte",
    "MX" => "Mexico",
    "FM" => "Micronesia, Federated States of",
    "MD" => "Moldova, Republic of",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "ME" => "Montenegro",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "AN" => "Netherlands Antilles",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PS" => "Palestinian Territory, Occupied",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "SH" => "Saint Helena",
    "KN" => "Saint Kitts and Nevis",
    "LC" => "Saint Lucia",
    "PM" => "Saint Pierre and Miquelon",
    "VC" => "Saint Vincent and The Grenadines",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome and Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "RS" => "Serbia",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia and The South Sandwich Islands",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard and Jan Mayen",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan, Province of China",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania, United Republic of",
    "TH" => "Thailand",
    "TL" => "Timor-leste",
    "TG" => "Togo",
    "TK" => "Tokelau",
    "TO" => "Tonga",
    "TT" => "Trinidad and Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TC" => "Turks and Caicos Islands",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "GB" => "United Kingdom",
    "US" => "United States",
    "UM" => "United States Minor Outlying Islands",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands, British",
    "VI" => "Virgin Islands, U.S.",
    "WF" => "Wallis and Futuna",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
]);


if (!function_exists('getTaskStatus')) {
    function getTaskStatus($code = 0, $html = null)
    {
        $word = match ($code) {
            0 => "Pending",
            1 => "Working On",
            2 => "Ready",
            3 => "Done",
            default => "Pending"
        };

        $html = match ($code) {
            0 => "<button class='btn btn-danger'>Pending</button>",
            1 => "<button class='btn btn-warning'>Working On</button>",
            2 => "<button class='btn btn-info'>Ready</button>",
            3 => "<button class='btn btn-success'>Done</button>",
            default => "<button class='btn btn-danger'>Pending</button>"
        };

        return $html ? $html : $word;
    }
}


if (!function_exists('userTenant')) {
    function userTenant($column = null)
    {
        return $column ? auth()->user()->tenant[$column] : auth()->user()->tenant_id;
    }
}





if (!function_exists('getAttendanceStatus')) {
    function getAttendanceStatus()
    {
        $tiger = new TigerPrint;
        $device_uids = $tiger->getAttendanceData();
        $db_uids = Attendance::pluck('uid')->toArray();
        $device_uids = collect($device_uids);
        $device_uids = $device_uids->where("type", 1)->toArray();
        $device_uids = array_column($device_uids, 'uid');
        return count(array_diff($device_uids, $db_uids)) > 0;
    }
}


if (!function_exists('maxDocumentsSize')) {
    function maxDocumentsSize()
    {
        $setting = Settings::first();
        $maxi = $setting->max_document_size ?? 10240;
        return $maxi;
    }
}


// Get Size Of File

if (!function_exists('getSize')) {

    function getSize($file_path = '', $disk = null, $trans = true)
    {
        $size = 0;
        if ($file_path) {
            if ($disk == null) {

                $size = Storage::size($file_path);
            } else {

                $size = Storage::disk($disk)->size($file_path);
            }
            if ($trans == true) {
                $size = bytesToHuman($size);
            }
        }

        return $size;
    }
}


// Get Invoice Status

if (!function_exists('invoice_status_name')) {
    function invoice_status_name($num = null)
    {
        if ($num) {
            return match ($num) {
                0 => __("site.not_paid"),
                1 => __("site.full_paid"),
                2 => __("site.part_paid"),
                default => ""
            };
        }
    }
}



// Get Selections Depends On Organizations

if (!function_exists('selections')) {

    function selections($model)
    {
        return $model::where('active', 1)->get();
    }
}


// Remove NULL Filters

if (!function_exists('remove_null_filters')) {

    function remove_null_filters($data)
    {

        foreach ($data as $key => $value) {

            if (key_exists('_token', $data)) {
                unset($data['_token']);
            }

            if (is_null($value)) {
                unset($data[$key]);
            }

            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_null($v)) {
                        unset($data[$key][$k]);
                    }
                }
            }

            if (is_array($value)) {
                if (empty($data[$key])) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }
}



// Get User ID

if (!function_exists('id')) {

    function id($username = null)
    {
        if (is_null($username)) {
            return auth()->user()->id;
        } else {
            $user = User::select("id")->where("username", $username)->first();
            return $user->id;
        }
    }
}

// Verify User Role

if (!function_exists('hasRole')) {

    function hasRole($role = 'user')
    {
        return auth()->user()->hasRole($role);
    }
}


// Verify User Permission

if (!function_exists('hasPermission')) {

    function hasPermission($permission = null)
    {
        if ($permission) {
            return auth()->user()->hasPermission($permission);
        } else {
            return NULL;
        }
    }
}



// Get User Token

if (!function_exists('my_token')) {

    function my_token($user_id = null)
    {
        if ($user_id) {
            return User::findOrFail($user_id)->token;
        } else {
            return auth()->user()->token;
        }
    }
}


// Get User Name

if (!function_exists('my_name')) {

    function my_name($user_id = null)
    {
        if ($user_id) {
            return User::findOrFail($user_id)->name;
        } else {
            return auth()->user()->name;
        }
    }
}



if (!function_exists('unset_prevented_perms')) {
    function unset_prevented_perms($perms, $prevents = null)
    {
        if (!is_null($prevents)):
            foreach ($prevents as $model => $actions) {

                foreach ($actions as $action) {
                    if (@array_search($action, ($perms[$model] ?? [])) !== false) {
                        $key = @array_search($action, $perms[$model]);
                        unset($perms[$model][$key]);
                    }
                }
            }
        endif;

        return $perms;
    }
}

if (!function_exists('clear_cache')) {

    function clear_cache()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
    }
}