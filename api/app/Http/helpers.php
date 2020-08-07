<?php

function gender($gender)
{
    $genders = [
        'm' => 'Male',
        'f' => 'Female'
    ];
    return $genders[$gender];
}

function display_storage_path($path)
{
    return '/storage/' . trim($path, '/');
}

function get_user_role($role)
{
    $roles = [
        App\User::USER_ROLE_CANDIDATE => 'candidate',
        App\User::USER_ROLE_EMPLOYEE => 'employee',
        App\User::USER_ROLE_ADMIN => 'admin'
    ];
    return @$roles[$role];
}

function get_current_date()
{
    return Carbon\Carbon::now()->format('Y-m-d');
}

function checkValidity($id)
{
    if ($id != Auth::user()->id) {
        abort(403, 'Unauthorized action.');
    }
}

function format_hours($hours)
{
    return $hours . ' hrs';
}

function get_admin_details()
{
    return App\User::where('role', App\User::USER_ROLE_ADMIN)->whereNull('deleted_at')->first();
}

//helps for vacation
function days_leave_year()
{
    return $days_leave_year = [
        ['min' => 1, 'max' => 4, 'days' => 15],
        ['min' => 5, 'max' => 9, 'days' => 20],
        ['min' => 10, 'max' => 0, 'days' => 30]
    ];
}

function type_employe()
{
    $day_6 = true;
    if ($day_6) {
        return $type_employe = [
            'Obrero/a' => ['day_6' => 1],
            'Oficinista' => ['day_6' => 0.5]
        ];
    }
    return [
        'Obrero/a' => ['day_6' => 0],
        'Oficinista' => ['day_6' => 0]
    ];
}


function get_date_employe($date1)
{
    $date2 = Carbon\Carbon::now()->format('Y-m-d');
    //$date1 = "2007-03-24";
    //$date2 = "2009-06-26";

    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    return ['y' => $years, 'm' => $months, 'd' => $days];
}


