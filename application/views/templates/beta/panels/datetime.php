<script>

function updateClock()
{
    var months  = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var dows    = ['Su', 'M', 'T', 'W', 'Th', 'F', 'Sa'];

    var dt      = new Date();
    var m       = dt.getMonth();
    var d       = '0' + dt.getDate();
    var w       = dt.getDay();

    document.getElementById('panel-datetime-date-month').innerText = months[m];
    document.getElementById('panel-datetime-date-day').innerText = d.substr(-2);
    document.getElementById('panel-datetime-date-dow').innerText = dows[w];
    document.getElementById('panel-datetime-time').innerText = get24HourTime(dt);
}

function changeMoonPhase()
{
    var $moon_icon = $('#panel-datetime-moon-icon');

    // Using the core $.ajax() method
    $.ajax({
        // the URL for the request
        url: "index.php/ajax/moonphase",

        // whether this is a POST or GET request
        type: "GET",

        // the type of data we expect back
        dataType : "json",

        // code to run if the request succeeds;
        // the response is passed to the function
        success: function(json)
        {
            // Get updated moon phase icon
            phase   = json.phase;

            // Change moon phase icon
            $moon_icon.removeClass().addClass('wi ' + phase);
        },

        // code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function(xhr, status, errorThrown)
        {
            // Show question mark icon
            $moon_icon.removeClass().addClass('wi wi-alien');

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

$(document).ready(function()
{
    updateClock();
    changeMoonPhase();
});

var clock = window.setInterval(updateClock, 1000);      // 1 second
var clock = window.setInterval(changeMoonPhase, 900000);    // 15 minutes

</script>
<div id="panel-datetime-date">
    <div id="panel-datetime-date-month">---------</div>
    <div id="panel-datetime-date-day">00</div>
    <div id="panel-datetime-moon-shadow" class="panel-datetime-moon-phase">
        <i class="wi wi-moon-full"></i>
    </div>
    <div id="panel-datetime-moon" class="panel-datetime-moon-phase">
        <i id="panel-datetime-moon-icon" class="wi"></i>
    </div>
    <div id="panel-datetime-date-dow">?</div>
</div>
<div id="panel-datetime-time">00:00</div>


