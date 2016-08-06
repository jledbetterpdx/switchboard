<?php

class Weather_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_weather()
    {
        // Retrieve weather data
        $_weather = $this->get_raw_weather_data();

        // Start extracting info
        $weather = array(
            'current'   => array(),
            'forecast'  => array()
        );

        /**** CURRENT WEATHER SECTION ****/
        // Current weather
        $weather['current']['time']     = $_weather->current_observation->observation_epoch;
        $weather['current']['temp']     = ($_weather->current_observation->temp_f == -9999 ? false : $_weather->current_observation->temp_f);
        //$weather['current']['temp']     = 100;
        $weather['current']['text']     = $_weather->current_observation->weather;
        $weather['current']['icon']     = get_weather_icon($_weather->current_observation->icon, is_daytime($weather['current']['time']));
        $weather['current']['humidity'] = $_weather->current_observation->relative_humidity;
        // Atmospheric pressure
        $weather['current']['pressure']             = array();
        $weather['current']['pressure']['value']    = $_weather->current_observation->pressure_mb;
        $weather['current']['pressure']['trend']    = $_weather->current_observation->pressure_trend;
        $_trend                                     = $weather['current']['pressure']['trend'];
        $weather['current']['pressure']['icon']     = ($_trend !== '0' ? ('wi-direction-' . ($_trend == '+' ? 'up' : 'down')) : '');
        // Precipitation
        $weather['current']['precip']           = array();
        $weather['current']['precip']['1hr']    = (is_zero_or_na($_weather->current_observation->precip_1hr_in) ? '0.00' : $_weather->current_observation->precip_1hr_in);
        $weather['current']['precip']['today']  = (is_zero_or_na($_weather->current_observation->precip_today_in) ? '0.00' : $_weather->current_observation->precip_today_in);
        // Wind
        $_windspeed                                 = $_weather->current_observation->wind_mph;
        $_windgust                                  = $_weather->current_observation->wind_gust_mph;
        $weather['current']['wind']                 = array();
        $weather['current']['wind']['direction']    = $_weather->current_observation->wind_dir;
        $weather['current']['wind']['degrees']      = $_weather->current_observation->wind_degrees;
        $weather['current']['wind']['speed']        = (!is_numeric($_windspeed) || $_windspeed < 0 ? '--' : round($_windspeed));
        $weather['current']['wind']['gust']         = (!is_numeric($_windgust) || $_windgust < 0 ? '--' : round($_windgust));
        $weather['current']['wind']['icon']         = $weather['current']['wind']['degrees'];
        // Pretty shit
        $weather['current']['color']                = get_weather_color($weather['current']['temp']);

        /**** HOURLY FORECAST SECTION ****/
        for ($i = 0; $i < APP_WEATHER_HOURLY_COUNT; $i++)
        {
            $hour = $_weather->hourly_forecast[$i]->FCTTIME->hour_padded;
            $weather['hourly'][] = array(
                'hour' => array(
                    12 => get_12hr($hour),
                    24 => $hour
                ),
                'temp' => $_weather->hourly_forecast[$i]->temp->english,
                'icon' => get_weather_icon($_weather->hourly_forecast[$i]->icon, is_daytime($_weather->hourly_forecast[$i]->FCTTIME->epoch))
            );
        }

        return $weather;
    }

    function get_raw_weather_data()
    {
        $url = "http://api.wunderground.com/api/" . API_KEY_WUNDERGROUND . "/forecast/hourly/astronomy/conditions/q/pws:" . API_KEY_WUNDERGROUND_STATION . ".json";

        // Make call with cURL
        $session = curl_init($url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        return json_decode($json);
    }
}