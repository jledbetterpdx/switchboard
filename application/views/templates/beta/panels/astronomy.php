<script>

// Astronomical variables
var sunrise, sunset, dawn, dusk;

function updateAstronomy()
{
    // Store DOM elements locally
    $body           = $('body');
    $sunrise        = $('#panel-astronomy-sunrise');
    $sunset         = $('#panel-astronomy-sunset');
    $sunrise_time   = document.getElementById('panel-astronomy-sunrise-time');
    $sunset_time    = document.getElementById('panel-astronomy-sunset-time');
    $astro_offline  = $('#panel-astronomy-error');

    // Using the core $.ajax() method
    $.ajax({
        // the URL for the request
        url: "index.php/ajax/astronomy",

        // whether this is a POST or GET request
        type: "GET",

        // the type of data we expect back
        dataType : "json",

        // code to run if the request succeeds;
        // the response is passed to the function
        success: function(json)
        {
            // Remove any visible offline indicators
            $astro_offline.hide();

            // Get updated astronomical times
            dawn    = json.civil_dawn;
            sunrise = json.sunrise;
            sunset  = json.sunset;
            dusk    = json.civil_dusk;

            // Get now
            var now = new Date().getTime() / 1000;

            // Prepare times for display
            var d_sunrise   = new Date(sunrise * 1000);
            var h_sunrise   = '0' + d_sunrise.getHours();
            var m_sunrise   = '0' + d_sunrise.getMinutes();
            var d_sunset    = new Date(sunset * 1000);
            var h_sunset    = '0' + d_sunset.getHours();
            var m_sunset    = '0' + d_sunset.getMinutes();

            // Change display times
            $sunrise_time.innerText = get24HourTime(d_sunrise);
            $sunset_time.innerText  = get24HourTime(d_sunset);

            // Check time against sunrise/set to determine dimming
            if (now > sunrise && !$sunrise.hasClass('dimmed'))
            {
                $sunrise.addClass('dimmed');
            }
            else if (now <= sunrise && $sunrise.hasClass('dimmed'))
            {
                $sunrise.removeClass('dimmed');
            }

            if (now > sunset && !$sunset.hasClass('dimmed'))
            {
                $sunset.addClass('dimmed');
            }
            else if (now <= sunset && $sunset.hasClass('dimmed'))
            {
                $sunset.removeClass('dimmed');
            }

            // Check to see if we need to enter or exit night mode
            if ((now < dawn || now > dusk) && !$body.hasClass('night'))
            {
                $body.addClass('night');
            }
            else if ((now >= dawn && now <= dusk) && $body.hasClass('night'))
            {
                $body.removeClass('night');
            }
        },

        // code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function(xhr, status, errorThrown)
        {
            // Show a visible offline indicator
            $astro_offline.show();

            if (console)
            {
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            }
        },

        // code to run regardless of success or failure
        complete: function(xhr, status)
        {
            // Do nothing
        }
    });
}

$(document).ready(function(){
    updateAstronomy();
});

var astronomy = window.setInterval(updateAstronomy, 30000);

</script>
<div id="panel-astronomy-sunrise" class="panel-astronomy-sun">
    <i class="wi wi-horizon-alt"></i> <span id="panel-astronomy-sunrise-time">--:--</span>
</div>
<div id="panel-astronomy-sunset" class="panel-astronomy-sun">
    <i class="wi wi-horizon"></i> <span id="panel-astronomy-sunset-time">--:--</span>
</div>
<div id="panel-astronomy-error" class="panel-error">
    <div class="panel-error-icon"><i class="fa fa-warning"></i></div>
    <div class="panel-error-text">Offline</div>
</div>