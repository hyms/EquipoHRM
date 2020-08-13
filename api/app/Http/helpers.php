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

function type_employe($type = null)
{
    $day_6 = true;
    $type_employe = [];
    if ($day_6) {
        $type_employe = [
            'Obrero/a' => 1,
            'Oficinista' => 0.5
        ];
    } else {
        $type_employe = [
            'Obrero/a' => 0,
            'Oficinista' => 0
        ];
    }
    if (empty($type)) {
        return $type_employe;
    } else {
        return $type_employe[$type];
    }
}


function get_date_employe($date1)
{
    $date2 = Carbon\Carbon::now()->format('Y-m-d');
    $diff = date_diff(date_create($date2), date_create($date1));
    return ['y' => $diff->y, 'm' => $diff->m, 'd' => $diff->d];
}

function getWorkdays($date1, $date2, $workSat = FALSE, $patron = NULL, $daySat = 0)
{
    if (!defined('SATURDAY')) define('SATURDAY', 6);
    if (!defined('SUNDAY')) define('SUNDAY', 0);

    // Array of all public festivities
    $publicHolidays = array('2020-08-07', '2020-05-01', '2020-12-25', '2020-12-26');
    // The Patron day (if any) is added to public festivities
    if ($patron) {
        $publicHolidays = $patron;
    }

    /*
     * Array of all Easter Mondays in the given interval
     */
    $yearStart = date('Y', strtotime($date1));
    $yearEnd = date('Y', strtotime($date2));

    for ($i = $yearStart; $i <= $yearEnd; $i++) {
        $easter = date('Y-m-d', easter_date($i));
        list($y, $m, $g) = explode("-", $easter);
        $monday = mktime(0, 0, 0, date($m), date($g) + 1, date($y));
        $easterMondays[] = $monday;
    }

    $start = strtotime($date1);
    $end = strtotime($date2);
    $workdays = 0;
    for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
        $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
        $mmgg = date('Y-m-d', $i);
        if ($day != SUNDAY &&
            !in_array($mmgg, $publicHolidays) &&
            !in_array($i, $easterMondays) &&
            !($day == SATURDAY && $workSat == FALSE)) {
            if ($day != SATURDAY) {
                $workdays++;
            } else if ($day == SATURDAY && $daySat != 0) {
                $workdays += $daySat;
            }
        }

    }

    return floatval($workdays);
}
