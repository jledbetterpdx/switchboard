<!-- Current weather conditions -->
<script>

// Global variables
var FORECAST_TIME = false;
var timespan;

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

    // Set interstitial defaults
    $curr_icon.removeClass().addClass('wi wi-stars');
    $curr_temp.html('--&deg;');
    $curr_wind.text('--');
    $curr_gust.text('--');
    $curr_wind_icon.removeClass().addClass('wi wi-moon-new');
    $curr_precip.text('-.--');
    $curr_humidity.text('--%');
    $curr_pressure.text('----');
    $curr_pressure_icon.removeClass().addClass('wi wi-cloud-refresh');
    $span.text('updating...');

    // Stop the clock
    clearInterval(timespan);

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

            // Load current conditions
            $curr_icon.removeClass().addClass('wi ' + json.current.icon);
            $curr_temp.html((!json.current.temp ? '??' : Math.round(json.current.temp * 1)) + '&deg;');
            $curr_wind_icon.removeClass().addClass('wi wi-wind-default _' + json.current.wind.icon + '-deg');
            $curr_wind.html(json.current.wind.speed == 0 ? 'Calm' : json.current.wind.speed + ' mph');
            json.current.wind.speed *= 1;   // Convert to numeric
            json.current.wind.gust *= 1;
            if (json.current.wind.gust <= json.current.wind.speed) $curr_gust_panel.hide(0);
            if (json.current.wind.gust > json.current.wind.speed) $curr_gust_panel.show(0);
            $curr_gust.html(json.current.wind.gust);
            $curr_precip.text(json.current.precip.today);
            $curr_humidity.text(json.current.humidity);
            $curr_pressure.text(json.current.pressure.value);
            FORECAST_TIME = parseInt(json.current.time);
            updateTimespan();
            if (json.current.pressure.trend !== false)
            {
                $curr_pressure_icon.removeClass().addClass('wi').addClass(json.current.pressure.icon);
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

            // Show special offline indicator icon
            $curr_icon.removeClass().addClass('wi wi-meteor');

            if (console)
            {
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
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
            timespan = window.setInterval(updateTimespan, 1000);
        }
    });
}

function updateTimespan()
{
    if (window.FORECAST_TIME !== false)
    {
        var f = window.FORECAST_TIME;
        var d = new Date().getTime() / 1000;
        var m = Math.floor((d - f) / 60);

        var str = '';

        // Determine string timeframe
        switch (true)
        {
            case (m == 0):
                str = 'now';
                break;
            case (m == 1):
                str = '1 minute ago';
                break;
            case (m < 60):
                str = m + ' minutes ago';
                break;
            case (m < (60 * 24)):
                h = Math.floor(m / 60);
                str = h + ' hour' + (h == 1 ? '' : 's') + ' ago';
                break;
            case (m < (60 * 24 * 7)):
                d = Math.floor((Math.floor(m / 60)) / 24);
                str = d + ' day' + (d == 1 ? '' : 's') + ' ago';
                break;
            default:
                str = 'a long time ago';
        }
        document.getElementById('panel-weather-current-span').innerText = str;
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
        <i class="wi wi-wind-default _0-deg" id="panel-weather-current-wind-icon"></i> <span id="panel-weather-current-wind-value">-- mph</span>
    </div>
    <div id="panel-weather-current-gust" class="panel-weather-current-auxinfo half-panel right-panel">
        <i class="wi wi-strong-wind"></i> <span id="panel-weather-current-gust-value">--</span> mph
    </div>
    <div id="panel-weather-current-precip" class="panel-weather-current-auxinfo">
        <i class="wi wi-sprinkles"></i> <span id="panel-weather-current-precip-value">-.--</span> in
    </div>
    <div id="panel-weather-current-humidity" class="panel-weather-current-auxinfo">
        <i class="wi wi-day-haze"></i> <span id="panel-weather-current-humidity-value">--%</span>
    </div>
    <div id="panel-weather-current-pressure" class="panel-weather-current-auxinfo">
        <i class="wi wi-cloud" id="panel-weather-current-pressure-icon"></i> <span id="panel-weather-current-pressure-value">----</span> mbar
    </div>
    <div id="panel-weather-current-span">loading...</div>
</div>
<div id="panel-weather-forecast">
</div>
<div id="panel-weather-error" class="panel-error">
    <div class="panel-error-icon"><i class="fa fa-warning"></i></div>
    <div class="panel-error-text">Offline</div>
</div>