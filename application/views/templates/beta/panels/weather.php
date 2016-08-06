<!-- Current weather conditions -->
<script>

// Global variables
var FORECAST_TIME = false;
var weather_timespan;

function getCurrentConditions()
{
    // Store jQuery objects as local variables
    $curr_icon              = $('#panel-weather-current-conditions-icon');
    $curr_temp              = $('#panel-weather-current-conditions-temp');
    $curr_wind              = $('#panel-weather-current-wind-value');
    $curr_gust_panel        = $('#panel-weather-current-gust');
    $curr_gust              = $('#panel-weather-current-gust-value');
    $curr_wind_icon         = $('#panel-weather-current-wind-icon');
    $curr_precip            = $('#panel-weather-current-precip-value');
    $curr_humidity          = $('#panel-weather-current-humidity-value');
    $curr_pressure          = $('#panel-weather-current-pressure-value');
    $curr_pressure_icon     = $('#panel-weather-current-pressure-icon');
    $span                   = $('#panel-weather-current-span');
    $weather_offline        = $('#panel-weather-error');

    // Loop variables
    $$loops                      = <?=APP_WEATHER_HOURLY_COUNT ?>;
    $$hourly_forecast_hours      = '#panel-weather-hourly-forecast-hour-time-';
    $$hourly_forecast_hour_icons = '#panel-weather-hourly-forecast-hour-icon-';
    $$hourly_forecast_icons      = '#panel-weather-hourly-forecast-conditions-icon-';
    $$hourly_forecast_temps      = '#panel-weather-hourly-forecast-conditions-temp-';

    // Set interstitial defaults
    $span.text('updating...');

    // Stop the clock
    clearInterval(weather_timespan);

    // Using the core $.ajax() method
    $.ajax({
        // the URL for the request
        url: "index.php/ajax/weather",

        // whether this is a POST or GET request
        type: "GET",

        // the type of data we expect back
        dataType : "json",

        // code to run if the request succeeds;
        // the response is passed to the function
        success: function(json)
        {
            // Remove any visible offline indicators
            $weather_offline.hide();
            
            // Convert certain values to numeric
            if (json.current.temp) json.current.temp *= 1;
            if (json.current.wind.speed) json.current.wind.speed *= 1;
            if (json.current.wind.gust) json.current.wind.gust *= 1;

            // Load current conditions
            $curr_icon.removeClass().addClass('wi ' + json.current.icon);
            if (json.current.temp)
            {
                if (Math.round(json.current.temp) >= 100 || Math.round(json.current.temp) <= -10)
                {
                    $curr_temp.addClass('condensed');
                }
                else
                {
                    $curr_temp.removeClass('condensed');
                }
                $curr_temp.html(Math.round(json.current.temp) + '&deg;');
            }
            else
            {
                $curr_temp.removeClass('condensed');
                $curr_temp.html('??&deg;');
            }
            $curr_temp.html((!json.current.temp ? '??' : Math.round(json.current.temp)) + '&deg;');
            $curr_wind_icon.removeClass().addClass('wi wi-wind towards-' + json.current.wind.degrees + '-deg');
            $curr_wind.html(json.current.wind.speed <= 0 ? 'Calm' : json.current.wind.speed + ' mph');
            json.current.wind.speed *= 1;   // Convert to numeric
            json.current.wind.gust *= 1;
            if (json.current.wind.gust <= json.current.wind.speed) $curr_gust_panel.hide(0);
            if (json.current.wind.gust > json.current.wind.speed) $curr_gust_panel.show(0);
            $curr_gust.html(json.current.wind.gust);
            $curr_precip.text(json.current.precip.today);
            $curr_humidity.text(json.current.humidity);
            $curr_pressure.text(json.current.pressure.value);
            FORECAST_TIME = parseInt(json.current.time);
            updateWeatherTimespan();
            $curr_pressure_icon.removeClass();
            if (json.current.pressure.trend != 0)
            {
                $curr_pressure_icon.addClass('wi').addClass('wi-fw').addClass(json.current.pressure.icon);
            }

            // Load hourly conditions
            for (i = 0; i < $$loops; i++)
            {
                $hourly_forecast_hour       = $($$hourly_forecast_hours + i);
                $hourly_forecast_hour_icon  = $($$hourly_forecast_hour_icons + i);
                $hourly_forecast_icon       = $($$hourly_forecast_icons + i);
                $hourly_forecast_temp       = $($$hourly_forecast_temps + i);

                $hourly_forecast_hour.text(json.hourly[i].hour[24]);
                $hourly_forecast_hour_icon.removeClass().addClass('wi wi-time-' + json.hourly[i].hour[12]);
                $hourly_forecast_icon.removeClass().addClass('wi').addClass(json.hourly[i].icon);
                $hourly_forecast_temp.html(json.hourly[i].temp + '&deg;');
            }

            // Set next iteration (between 4 and 6 minutes)
            var rand = parseInt((Math.random() * 120) + 240) * 1000;
            setTimeout(getCurrentConditions, rand);
        },

        // code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function(xhr, status, errorThrown)
        {
            // Show a visible offline indicator
            $weather_offline.show();

            // Show defaults
            $curr_icon.removeClass().addClass('wi wi-meteor');
            $curr_temp.html('--&deg;');
            $curr_wind.text('--');
            $curr_gust.text('--');
            $curr_wind_icon.removeClass().addClass('wi wi-moon-new');
            $curr_precip.text('-.--');
            $curr_humidity.text('--%');
            $curr_pressure.text('----');
            $curr_pressure_icon.removeClass().addClass('wi wi-cloud-refresh');

            // Load hourly conditions
            for (i = 0; i < $$loops; i++)
            {
                $hourly_forecast_hour       = $($$hourly_forecast_hours + i);
                $hourly_forecast_hour_icon  = $($$hourly_forecast_hour_icons + i);
                $hourly_forecast_icon       = $($$hourly_forecast_icons + i);
                $hourly_forecast_temp       = $($$hourly_forecast_temps + i);

                $hourly_forecast_hour.text('--');
                $hourly_forecast_hour_icon.removeClass().addClass('wi wi-time-4');
                $hourly_forecast_icon.removeClass().addClass('wi wi-meteor');
                $hourly_forecast_temp.html('---&deg;');
            }

            if (console)
            {
                console.log("Weather Error: " + errorThrown);
                console.log("Weather Status: " + status);
                console.dir(xhr);
            }

            // Set next iteration (30 seconds)
            var retry = 30 * 1000;
            setTimeout(getCurrentConditions, retry);
        },

        // code to run regardless of success or failure
        complete: function(xhr, status)
        {
            // Resume clock
            weather_timespan = window.setInterval(updateWeatherTimespan, 1000);
        }
    });
}

function updateWeatherTimespan()
{
    if (window.FORECAST_TIME !== false)
    {
        document.getElementById('panel-weather-current-span').innerText = getTimespanText(window.FORECAST_TIME);
    }
}

$(document).ready(function()
{
    getCurrentConditions();
});

</script>
<div id="panel-weather-current">
    <div id="panel-weather-current-conditions">
        <i id="panel-weather-current-conditions-icon" class="wi wi-stars"></i><span id="panel-weather-current-conditions-temp">--&deg;</span>
    </div>
    <div id="panel-weather-current-wind" class="panel-weather-current-auxinfo half-panel left-panel">
        <i class="wi wi-wind" id="panel-weather-current-wind-icon"></i> <span id="panel-weather-current-wind-value">-- mph</span>
    </div>
    <div id="panel-weather-current-gust" class="panel-weather-current-auxinfo half-panel right-panel">
        <i class="wi wi-strong-wind"></i> <span id="panel-weather-current-gust-value">--</span> mph
    </div>
    <div id="panel-weather-current-precip" class="panel-weather-current-auxinfo">
        <i class="wi wi-raindrops"></i> <span id="panel-weather-current-precip-value">-.--</span> in
    </div>
    <div id="panel-weather-current-humidity" class="panel-weather-current-auxinfo">
        <i class="wi wi-humidity"></i> <span id="panel-weather-current-humidity-value">--%</span>
    </div>
    <div id="panel-weather-current-pressure" class="panel-weather-current-auxinfo">
        <i class="wi wi-barometer"></i> <i id="panel-weather-current-pressure-icon"></i><span id="panel-weather-current-pressure-value">----</span> mbar
    </div>
    <div id="panel-weather-current-span">loading...</div>
</div>
<div id="panel-weather-hourly">
<?
for ($i = 0; $i < APP_WEATHER_HOURLY_COUNT; $i++):
?>
    <div class="panel-weather-hourly-forecast" id="panel-weather-hourly-forecast-<?=$i ?>">
        <div class="panel-weather-hourly-forecast-hour" id="panel-weather-hourly-forecast-hour-<?=$i ?>">
            <i id="panel-weather-hourly-forecast-hour-icon-<?=$i ?>" class="wi wi-time-<?=($i + 1) ?>"></i><span id="panel-weather-hourly-forecast-hour-time-<?=$i ?>">--</span>
        </div>
        <div class="panel-weather-hourly-forecast-conditions condensed" id="panel-weather-hourly-forecast-conditions-<?=$i ?>">
            <i id="panel-weather-hourly-forecast-conditions-icon-<?=$i ?>" class="wi wi-stars"></i><span id="panel-weather-hourly-forecast-conditions-temp-<?=$i ?>">---&deg;</span>
        </div>
    </div>
<?
endfor;
?>
</div>
<div id="panel-weather-forecast">
</div>
<div id="panel-weather-error" class="panel-error">
    <div class="panel-error-icon"><i class="fa fa-warning"></i></div>
    <div class="panel-error-text">Offline</div>
</div>