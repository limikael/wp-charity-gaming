<?php

namespace charity;

require_once __DIR__."/../utils/Template.php";
require_once __DIR__."/../utils/Singleton.php";
require_once __DIR__."/../controller/CharityController.php";

use \Exception;

/**
 * Manage the settings page.
 */
class SettingsController extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('admin_menu',array($this,'admin_menu'));
		add_action('admin_init',array($this,'admin_init'));			

		if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="update" &&
				isset($_REQUEST["charity_distribute_revenue"]))
			$this->updateSchedule();
	}

	/**
	 * Add options page
	 */
	public function admin_menu() {
		// This page will be under "Settings"
		add_options_page(
			'Charity Gaming',
			'Charity Gaming',
			'manage_options', 
			'charity_gaming_settings',
			array($this,'create_settings_page')
		);
	}		

	/**
	 * Updated option.
	 */
	public function updateSchedule() {
		if (!isset($_REQUEST["charity_distribute_revenue"]))
			return;

		wp_clear_scheduled_hook("charity_distribute_revenue");

		$schedule=$_REQUEST["charity_distribute_revenue"];
		$schedules=wp_get_schedules();
		if (!$schedules[$schedule])
			throw new Exception("Unknown schedule");

        wp_schedule_event(
            time()+$schedules[$schedule]["interval"],
            $_REQUEST["charity_distribute_revenue"],
            "charity_distribute_revenue"
        );
	}

	/**
	 * Admin init.
	 */
	public function admin_init() {
		register_setting("charity","charity_operational_percentage");
		register_setting("charity","charity_withdraw_when");
	}

	/**
	 * Create the settings page.
	 */
	public function create_settings_page() {
		if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="collect") {
			CharityController::instance()->distributeRevenue();
		}

		$vars=array();
		$vars["schedules"]=array(
			"hourly"=>"Hourly",
			"daily"=>"Daily",
		);

		$vars["currentSchedule"]=wp_get_schedule("charity_distribute_revenue");
		$vars["collectUrl"]=admin_url("options-general.php?page=charity_gaming_settings&action=collect");

		$template=new Template(__DIR__."/../view/settings.php");
		$template->display($vars);
	}
}