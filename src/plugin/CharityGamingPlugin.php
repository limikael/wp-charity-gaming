<?php

namespace charity;

require_once __DIR__."/../utils/Singleton.php";
require_once __DIR__."/../controller/CharityController.php";

/**
 * The main plugin class.
 */
class CharityGamingPlugin extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		CharityController::instance();
	}
}