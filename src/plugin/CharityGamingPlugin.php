<?php

namespace charity;

require_once __DIR__."/../utils/Singleton.php";
require_once __DIR__."/../controller/CharityController.php";
require_once __DIR__."/../model/Vote.php";

/**
 * The main plugin class.
 */
class CharityGamingPlugin extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		CharityController::instance();

        register_activation_hook(CHARITY_PATH.'/wp-charity-gaming.php', array($this, 'activate'));
        register_uninstall_hook(CHARITY_PATH.'/wp-charity-gaming.php', array($this, 'uninstall'));
		add_action('wp_enqueue_scripts', array($this,"enqueueScripts"));
	}

	/**
	 * Activate plugin.
	 */
	public function activate() {
		Vote::install();
	}

	/**
	 * Uninstall plugin.
	 */
	public function uninstall() {
		Vote::uninstall();
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueueScripts() {
		wp_enqueue_style(
			"charity-gaming",
			CHARITY_URL."/wp-charity-gaming.css"
		);
	}
}