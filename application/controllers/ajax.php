<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function index()
	{

	}

    public function weather()
    {
        // Get weather info
        $weather = $this->weather_model->get_weather();

        // Headers
        header('Content-type: application/json');

        // Echo the output
        echo json_encode($weather);

        // End
        return;
    }

    public function astronomy()
    {
        // Current time (prevents race conditions)
        $time = time();

        // Get astronomy information
        $astronomy = array();

        $astronomy['astronomical_dawn'] = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_ASTRONOMICAL);
        $astronomy['nautical_dawn']     = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_NAUTICAL);
        $astronomy['civil_dawn']        = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_CIVIL);
        $astronomy['sunrise']           = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_HORIZON);
        $astronomy['apex']              = null;
        $astronomy['sunset']            = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_HORIZON);
        $astronomy['apex']              = round(($astronomy['sunset'] + $astronomy['sunrise']) / 2);
        $astronomy['civil_dusk']        = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_CIVIL);
        $astronomy['nautical_dusk']     = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_NAUTICAL);
        $astronomy['astronomical_dusk'] = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, ZENITH_ASTRONOMICAL);

        // Headers
        header('Content-type: application/json');

        // Echo the output
        echo json_encode($astronomy);

        // End
        return;
    }

    function moonphase()
    {
        error_reporting(0);
        ini_set('display_errors', 0);

        // Get moon phase information
        $moonphase = array();

        // fetch Aeris API output as a string and decode into an object
        $url = 'http://api.aerisapi.com/sunmoon/' . APP_ZIP . '?filter=moonphase&client_id=' . API_KEY_HAMWEATHER_OAUTH_CLIENTID . '&client_secret=' . API_KEY_HAMWEATHER_OAUTH_CLIENTSECRET;
        $response = file_get_contents($url);
        $json = json_decode($response);
        if ($json->success == true) {
            // Get current phase
            $phase = $json->response[0]->moon->phase->phase;
            // create reference to our returned observation object
            $moonphase['phase'] = get_moon_phase_icon($phase);
        }
        else {
            $moonphase['phase'] = 'wi-alien';
        }

        // Headers
        header('Content-type: application/json');

        // Echo the output
        echo json_encode($moonphase);
    }

    /** @noinspection PhpExpressionResultUnusedInspection */
    function thermostat()
    {
        // Require class file (can't autoload for some reason)
        require_once('/var/www/html/switchboard/application/libraries/nest.php');

        // Retrieve current Nest state
        $nest       = new Nest(API_KEY_NEST_USERNAME, API_KEY_NEST_PASSWORD);
        $devices    = $nest->getDevices();
        $data       = $nest->getDeviceInfo($devices[0]);

        // Add custom data fields
        $data->network->last_connection_unix = strtotime($data->network->last_connection);
        $data->target->time_to_target_diff   = ($data->target->time_to_target == 0 ? false : $data->target->time_to_target - $data->network->last_connection_unix);

        // Headers
        header('Content-type: application/json');

        // Echo the output
        echo json_encode($data);

        // End
        return;

    }

    function news()
    {
        // Return variable
        $return = false;

        // Load RSS feed
        exec('wget -qO- ' . APP_NEWS_FEED, $ret);
        $xml = simplexml_load_string(implode(PHP_EOL, $ret));
        #$xml = simplexml_load_file(APP_NEWS_FEED);

        if ($xml !== false)
        {
            // Retrieve all stories
            $items = $xml->channel->item;
            // Reinitialize return as array
            $return = array();
            // Load all titles into array
            foreach ($items as $item)
            {
                $return['articles'][] = (string)$item->title[0];
            }

            // Reduce list to maximum allowed
            $return['source']   = (string)$xml->channel->title[0];
            $return['url']      = APP_NEWS_FEED;
            $return['pubDate']  = (string)$xml->channel->pubDate[0];
            $return['articles'] = array_slice($return['articles'], 0, APP_NEWS_FEED_MAX_ARTICLES);
        }
        // Headers
        header('Content-type: application/json');

        // Echo the output
        echo json_encode($return);

        // End
        return;
    }

    function news2()
    {
        // Return variable
        $return = false;

        // Load RSS feed
        $xml = simplexml_load_file(APP_NEWS_FEED);

        if ($xml !== false)
        {
            // Retrieve all stories
            $items = $xml->channel->item;
            // Reinitialize return as array
            $return = array();
            // Load all titles into array
            foreach ($items as $item)
            {
                $return['articles'][] = '<a href="' . (string)$item->guid[0] . '" target="_blank">' . (string)$item->title[0] . '</a>';
            }

            // Reduce list to maximum allowed
            $return['source']   = (string)$xml->channel->title[0];
            $return['url']      = APP_NEWS_FEED;
            $return['pubDate']  = (string)$xml->channel->lastBuildDate[0];
            $return['pubTime']  = date('H:i', strtotime($return['pubDate']));
            $return['articles'] = array_slice($return['articles'], 0, APP_NEWS_FEED_MAX_ARTICLES);
        }
        // Headers
        header('Content-type: application/json');

        // Echo the output
        echo json_encode($return, JSON_UNESCAPED_SLASHES);

        // End
        return;
    }

    function television()
    {

    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */