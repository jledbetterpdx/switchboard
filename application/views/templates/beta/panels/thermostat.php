<!-- Current thermostat settings -->
<script>

    // Global variables
    var THERMOSTAT_TIME = false;
    var thermostat_timespan;

    function getCurrentThermostat()
    {
        // Store jQuery objects as local variables
        $indoor_status          = $('#panel-thermostat-status');
        $indoor_temp            = $('#panel-thermostat-temp');
        $indoor_humidity        = $('#panel-thermostat-humidity-value');
        $indoor_target          = $('#panel-thermostat-target');
        $indoor_target_temp     = $('#panel-thermostat-target-temp');
        $indoor_target_time     = $('#panel-thermostat-target-time');
        $indoor_target_time_val = $('#panel-thermostat-target-time-value');
        $indoor_leaf            = $('#panel-thermostat-leaf-icon');
        $indoor_span            = $('#panel-thermostat-span');
        $indoor_offline         = $('#panel-thermostat-error');

        // Set interstitial defaults
        $indoor_span.text('updating...');

        // Stop the clock
        clearInterval(thermostat_timespan);

        // Using the core $.ajax() method
        $.ajax({
            // the URL for the request
            url: "index.php/ajax/thermostat",

            // whether this is a POST or GET request
            type: "GET",

            // the type of data we expect back
            dataType : "json",

            // code to run if the request succeeds;
            // the response is passed to the function
            success: function(json)
            {
                // Remove any visible offline indicators
                $indoor_offline.hide();

                // Load current thermostat data
                $indoor_status.removeClass().addClass(json.target.mode);
                $indoor_temp.html(Math.round(json.current_state.temperature) + '&deg;');
                $indoor_humidity.text(json.current_state.humidity + '%');
                if (json.target.time_to_target != 0)
                {
                    $indoor_target.show(0);
                    $indoor_target_time.show(0);
                    $indoor_target_time_val.text(get24HourTime(new Date(json.target.time_to_target * 1000)));
                }
                else
                {
                    $indoor_target.hide(0);
                    $indoor_target_time.hide(0);
                    $indoor_target_time_val.text('--:--');
                }
                $indoor_target_temp.html(Math.round(json.target.temperature) + '&deg;');
                $indoor_leaf.removeClass().addClass('fa' + (json.current_state.leaf ? ' fa-leaf' : ''));
                THERMOSTAT_TIME = parseInt(json.network.last_connection_unix);
                updateThermostatTimespan();

                // Set next iteration (between 30 and 60 seconds)
                var rand = parseInt((Math.random() * 30) + 30) * 1000;
                setTimeout(getCurrentThermostat, rand);
            },

            // code to run if the request fails; the raw request and
            // status codes are passed to the function
            error: function(xhr, status, errorThrown)
            {
                // Show a visible offline indicator
                $indoor_offline.show();

                // Show defaults
                $indoor_status.removeClass();
                $indoor_temp.html('--&deg;');
                $indoor_humidity.html('--%');
                $indoor_target.hide(0);
                $indoor_target_temp.hide(0);

                if (console)
                {
                    console.log("Therm Error: " + errorThrown);
                    console.log("Therm Status: " + status);
                    console.dir(xhr);
                }

                // Set next iteration (30 seconds)
                var retry = 30 * 1000;
                setTimeout(getCurrentThermostat, retry);
            },

            // code to run regardless of success or failure
            complete: function(xhr, status)
            {
                // Resume clock
                thermostat_timespan = window.setInterval(updateThermostatTimespan, 1000);
            }
        });
    }

    function updateThermostatTimespan()
    {
        if (window.THERMOSTAT_TIME !== false)
        {
            document.getElementById('panel-thermostat-span').innerText = getTimespanText(window.THERMOSTAT_TIME);
        }
    }

    $(document).ready(function()
    {
        getCurrentThermostat();
    });

</script>
<div id="panel-thermostat-status">
    <div id="panel-thermostat-temp">--&deg;</div>
    <div id="panel-thermostat-leaf"><i class="fa" id="panel-thermostat-leaf-icon"></i></div>
</div>
<div id="panel-thermostat-humidity" class="panel-thermostat-auxinfo">
    <i class="wi wi-humidity"></i><span id="panel-thermostat-humidity-value">--%</span>
</div>
<div id="panel-thermostat-target" class="panel-thermostat-auxinfo">
    <i id="panel-thermostat-target-icon" class="fa fa-dot-circle-o"></i><span id="panel-thermostat-target-temp">--&deg;</span>
</div>
<div id="panel-thermostat-target-time" class="panel-thermostat-auxinfo">
    <i id="panel-thermostat-target-time-icon" class="fa fa-at"></i><span id="panel-thermostat-target-time-value">--:--</span>
</div>
<div id="panel-thermostat-span" class="panel-thermostat-auxinfo">loading...</div>
<div id="panel-thermostat-error" class="panel-error">
    <div class="panel-error-icon"><i class="fa fa-warning"></i></div>
    <div class="panel-error-text">Offline</div>
</div>