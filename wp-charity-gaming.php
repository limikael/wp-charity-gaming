<?php

/*
Plugin Name: Charity Gaming
Plugin URI: http://github.com/limikael/wp-charity-gaming
GitHub Plugin URI: https://github.com/limikael/wp-charity-gaming
Description: Vote and distribute funds to charities.
Version: 0.0.1
*/

define('CHARITY_PATH',plugin_dir_path(__FILE__));
define('CHARITY_URL',plugins_url('',__FILE__));

if (!defined("RWMB_URL")) {
	define("RWMB_URL",CHARITY_URL."/ext/meta-box/");
	require_once __DIR__."/ext/meta-box/meta-box.php";
}

require_once __DIR__."/src/plugin/CharityGamingPlugin.php";

charity\CharityGamingPlugin::instance();
