<script>
    function scrollNewsTicker()
    {
        $ticker = $('#panel-news-crawl');
        $ticker.animate({left: '-=1vw'}, 0);

        w = $ticker.outerWidth(true);
        l = parseInt($ticker.css('left'));

        if (l < 0 && Math.abs(l) > w)
        {
            // Update news, which resets ticker
            updateNews();
            //$ticker.animate({left: '100vw'}, 0);
        }
    }

    function updateNews()
    {
        // Store DOM elements locally
        $ticker = $('#panel-news-crawl');

        // Stop ticker
        window.clearInterval(ticker);

        // Hide news feed
        $ticker.hide(200);

        // Using the core $.ajax() method
        $.ajax({
            // the URL for the request
            url: "index.php/ajax/news",

            // whether this is a POST or GET request
            type: "GET",

            // the type of data we expect back
            dataType : "json",

            // code to run if the request succeeds;
            // the response is passed to the function
            success: function(json)
            {
                // Get updated article list
                articles = json.articles.join('<i class="fa fa-newspaper-o panel-news-separator"></i>');
                // Load article list into news ticker
                $ticker.html(articles);
            },

            // code to run if the request fails; the raw request and
            // status codes are passed to the function
            error: function(xhr, status, errorThrown)
            {
                // Load default text into news ticker
                $ticker.html('News ticker offline -- reloading shortly');

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
                // Move all the way to the right and show
                $ticker.css({'left' : '100vw'}).show(200);
                ticker = window.setInterval(scrollNewsTicker, 100);
            }
        });
    }

    $(document).ready(function(){
        updateNews();
    });

    var ticker;

</script>
<div id="panel-news-crawl">Loading news feed...</div>