<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        $weather = $this->weather_model->get_weather();

        $vars = array(
            'views' => array(
                'weather',
                'thermostat',
                'datetime',
                'news',
                'astronomy'
            ),
            'vars' => array(
                'weather' => $weather
            )
        );

        $this->load->vars($vars);

        $this->load->view('templates/' . GLOBAL_TEMPLATE . '/header');
        $this->load->view('templates/' . GLOBAL_TEMPLATE . '/main');
        $this->load->view('templates/' . GLOBAL_TEMPLATE . '/footer');
	}

    public function phpinfo()
    {
        phpinfo();
    }

    public function timecheck()
    {
        // Get current conditions
        $time    = time();  // Eliminate any race conditions
        $sunrise = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, 90);
        $sunset  = date_sunset($time, SUNFUNCS_RET_TIMESTAMP, APP_LAT, APP_LONG, 90);

        $is_day = ($time >= $sunrise && $time <= $sunset);

        echo date('Y-m-d H:i.s', $sunrise) . '<br />' . PHP_EOL;
        echo date('Y-m-d H:i.s', $time) . '<br />' . PHP_EOL;
        echo date('Y-m-d H:i.s', $sunset) . '<br />' . PHP_EOL;
        var_dump($is_day);
    }

    public function test()
    {
        $weather = $this->weather_model->get_raw_weather_data();

        echo '<textarea style="width: 800px; height: 600px;">';
        var_dump($weather);
        echo '</textarea>';
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */