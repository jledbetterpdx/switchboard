<?php

function get_weather_icons()
{
    $icons                   = array();
    $icons['chanceflurries'] = array('day' => 'wi-day-snow',             'night' => 'wi-night-alt-snow');
    $icons['chancerain']     = array('day' => 'wi-day-rain',             'night' => 'wi-night-alt-rain');
    $icons['chancesleet']    = array('day' => 'wi-day-sleet',            'night' => 'wi-night-alt-sleet');
    $icons['chancesnow']     = array('day' => 'wi-day-snow',             'night' => 'wi-night-alt-snow');
    $icons['chancetstorms']  = array('day' => 'wi-day-thunderstorm',     'night' => 'wi-night-alt-thunderstorm');
    $icons['clear']          = array('day' => 'wi-day-sunny',            'night' => 'wi-night-clear');
    $icons['cloudy']         = array('day' => 'wi-cloudy',               'night' => 'wi-cloudy');
    $icons['flurries']       = array('day' => 'wi-day-snow',             'night' => 'wi-night-alt-snow');
    $icons['fog']            = array('day' => 'wi-day-fog',              'night' => 'wi-night-fog');
    $icons['hazy']           = array('day' => 'wi-day-haze',             'night' => 'wi-night-fog');
    $icons['mostlycloudy']   = array('day' => 'wi-day-cloudy',           'night' => 'wi-night-alt-cloudy');
    $icons['mostlysunny']    = array('day' => 'wi-day-sunny-overcast',   'night' => 'wi-night-alt-partly-cloudy');
    $icons['partlycloudy']   = $icons['mostlysunny'];
    $icons['partlysunny']    = $icons['mostlycloudy'];
    $icons['sleet']          = array('day' => 'wi-sleet',                'night' => 'wi-sleet');
    $icons['rain']           = array('day' => 'wi-rain',                 'night' => 'wi-rain');
    $icons['snow']           = array('day' => 'wi-snow',                 'night' => 'wi-snow');
    $icons['sunny']          = array('day' => 'wi-day-sunny',            'night' => 'wi-night-clear');
    $icons['tstorms']        = array('day' => 'wi-thunderstorm',         'night' => 'wi-thunderstorm');
    $icons['unknown']        = array('day' => 'wi-meteor',               'night' => 'wi-alien');

    return $icons;
}

function get_moon_phase_icon($phase)
{
    $phases = array();

    $phases[] = '';
    $phases[] = 'wi-moon-waxing-crescent-1';
    $phases[] = 'wi-moon-waxing-crescent-2';
    $phases[] = 'wi-moon-waxing-crescent-3';
    $phases[] = 'wi-moon-waxing-crescent-4';
    $phases[] = 'wi-moon-waxing-crescent-5';
    $phases[] = 'wi-moon-waxing-crescent-6';
    $phases[] = 'wi-moon-first-quarter';
    $phases[] = 'wi-moon-waxing-gibbous-1';
    $phases[] = 'wi-moon-waxing-gibbous-2';
    $phases[] = 'wi-moon-waxing-gibbous-3';
    $phases[] = 'wi-moon-waxing-gibbous-4';
    $phases[] = 'wi-moon-waxing-gibbous-5';
    $phases[] = 'wi-moon-waxing-gibbous-6';
    $phases[] = 'wi-moon-full';
    $phases[] = 'wi-moon-waning-gibbous-1';
    $phases[] = 'wi-moon-waning-gibbous-2';
    $phases[] = 'wi-moon-waning-gibbous-3';
    $phases[] = 'wi-moon-waning-gibbous-4';
    $phases[] = 'wi-moon-waning-gibbous-5';
    $phases[] = 'wi-moon-waning-gibbous-6';
    $phases[] = 'wi-moon-third-quarter';
    $phases[] = 'wi-moon-waning-crescent-1';
    $phases[] = 'wi-moon-waning-crescent-2';
    $phases[] = 'wi-moon-waning-crescent-3';
    $phases[] = 'wi-moon-waning-crescent-4';
    $phases[] = 'wi-moon-waning-crescent-5';
    $phases[] = 'wi-moon-waning-crescent-6';
    $phases[] = '';

    // Get phase index (ensuring any overflows wrap properly)
    $idx = (int)round($phase * (count($phases) - 1)) % (count($phases) - 1);

    // Return icon based on phase index
    $return = $phases[$idx];
    return $return;
}

function get_weather_icon($icon, $is_day)
{
    $icons = get_weather_icons();

    $return = $icons[$icon][($is_day ? 'day' : 'night')];

    return $return;
}

function get_wind_icon($direction)
{
    return (int)(round(($direction % 360) / 15) * 15);
}

function get_weather_color($temp)
{
    if (empty($temp)) return false;

    $return = array();

    // Store temp as integer
    $temp = (int)$temp;

    $temps[-20] = array('r' => 255, 'g' => 255, 'b' => 255);
    $temps[-10] = array('r' => 216, 'g' => 115, 'b' => 220);
    $temps[0] = array('r' => 146, 'g' => 58, 'b' => 187);
    $temps[10] = array('r' => 55, 'g' => 35, 'b' => 152);
    $temps[20] = array('r' => 7, 'g' => 182, 'b' => 220);
    $temps[30] = array('r' => 1, 'g' => 215, 'b' => 134);
    $temps[40] = array('r' => 95, 'g' => 205, 'b' => 3);
    $temps[50] = array('r' => 255, 'g' => 255, 'b' => 0);
    $temps[60] = array('r' => 251, 'g' => 120, 'b' => 0);
    $temps[70] = array('r' => 210, 'g' => 36, 'b' => 2);
    $temps[80] = array('r' => 160, 'g' => 8, 'b' => 2);
    $temps[90] = array('r' => 96, 'g' => 38, 'b' => 6);
    $temps[100] = array('r' => 0, 'g' => 0, 'b' => 0);

    $pos = $temp % 10; // Get remainder
    // Check to see if we're not already on a known color

    if ($temp <= -20)
    {
        $return = $temps[-20];
    }
    elseif ($temp >= 100)
    {
        $return = $temps[100];
    }
    else
    {
        if ($pos == 0)
        {
            $return = $temps[$temp];
        }
        else
        {
            // Get low and high colors
            $low  = (int)(floor($temp / 10) * 10);
            $high = (int)(ceil($temp / 10) * 10);

            // Calculate color to return
            $return = array(
                'r' => (int)round($temps[$low]['r'] + ((($temps[$high]['r'] - $temps[$low]['r']) / 10) * $pos)),
                'g' => (int)round($temps[$low]['g'] + ((($temps[$high]['g'] - $temps[$low]['g']) / 10) * $pos)),
                'b' => (int)round($temps[$low]['b'] + ((($temps[$high]['b'] - $temps[$low]['b']) / 10) * $pos))
            );
        }
    }

    return $return;
}

function get_dow_abbrev($dow)
{
    $_dow = array('Su', 'M', 'T', 'W', 'Th', 'F', 'Sa');
    return $_dow[$dow];
}

function is_zero_or_na($val)
{
    $zero_or_na = array(-9999, -999, 0, "", "na", "NA", false, null);
    return in_array($val, $zero_or_na);
}

function is_daytime($time)
{
    $sunrise    = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_HORIZON);
    $sunset     = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_HORIZON);

    return ($time >= $sunrise && $time <= $sunset);
}

function get_12hr($hour)
{
    return (string)date('g', mktime($hour, 0, 0, 1, 1, 2015));
}