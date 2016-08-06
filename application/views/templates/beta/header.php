<!DOCTYPE html>
<html>
    <head>
        <!-- Mobile mumbojumbo -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Reset -->
        <link href="<?=REL ?>/assets/css/reset.css" rel="stylesheet" />
        <!-- Web fonts -->
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:400,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <link href="<?=REL ?>/assets/fonts/font-awesome.css" rel="stylesheet" />
        <link href="<?=REL ?>/assets/fonts/weather-icons.css" rel="stylesheet" />
        <!-- Local CSS -->
        <link href="<?=REL ?>/assets/templates/<?=GLOBAL_TEMPLATE ?>/css/stylesheet.css" rel="stylesheet" />
        <!-- JavaScripts -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script type="text/javascript">

            function getTimespanText(f)
            {
                var d = new Date().getTime() / 1000;
                var m = Math.floor((d - f) / 60);

                var str = '';

                // Determine string timeframe
                switch (true)
                {
                    case (m <= 0):
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

                return str;
            }

            function get24HourTime(dt)
            {
                var h = '0' + dt.getHours();
                var i = '0' + dt.getMinutes();

                return h.substr(-2) + ':' + i.substr(-2);
            }

        </script>

        <!-- Doc info -->
        <title>Switchboard v<?=APP_VERSION ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
    </head>
    <body>
        <main>
