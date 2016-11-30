<?php

/*
Plugin Name: Charity Accounts
Plugin URI: http://github.com/limikael/wp-charity-accounts
GitHub Plugin URI: https://github.com/limikael/wp-charity-accounts
Description: Vote and distribute funds to charities.
Version: 0.0.1
*/

define('CHARITY_PATH',plugin_dir_path(__FILE__));
define('CHARITY_URL',plugins_url('',__FILE__));

if (!defined("RWMB_URL")) {
	define("RWMB_URL",CHARITY_URL."/ext/meta-box/");
	require_once __DIR__."/ext/meta-box/meta-box.php";
}

/**
 * Render a template file.
 */
function charity_render_template($fileName, $vars=array()) {
	foreach ($vars as $key=>$value)
		$$key=$value;

	ob_start();
	require $fileName;
	return ob_get_clean();
}

/**
 * The init action.
 */
function charity_init() {
	register_post_type("charity",array(
		"labels"=>array(
			"name"=>"Charities",
			"singular_name"=>"Charity",
			"not_found"=>"No charities found.",
			"add_new_item"=>"Add new Charity",
			"edit_item"=>"Edit Charity",
		),
		"public"=>true,
		"has_archive"=>true,
		"supports"=>array("title"),
		"show_in_nav_menus"=>false
	));
}

/**
 * Register meta boxes.
 */
function charity_rwmb_meta_boxes($metaBoxes) {
	$metaBoxes[]=array(
		"title"=>"Listing",
		"post_types"=>"charity",
		"priority"=>"low",
		"fields"=>array(
			array(
                'id'=>'description',
                'type'=>'textarea',
                'name'=>"Description",
				"desc"=>"Description shown in the listing."
			),
			array(
                'id'=>'logo',
                'type'=>'image_advanced',
                'name'=>"Logo",
				"max_file_uploads"=>1,
				"max_status"=>false,
				"desc"=>"Image shown in the listing."
			)
		)
	);

	$metaBoxes[]=array(
		"title"=>"Addresses",
		"post_types"=>"charity",
		"priority"=>"low",
		"fields"=>array(
			array(
				"id"=>"url",
				"name"=>"Home Page",
				"type"=>"text",
				"desc"=>"The listed home page for the charity."
			),
			array(
				"id"=>"bitcoinAddress",
				"name"=>"Bitcoin Address",
				"type"=>"text",
				"desc"=>"The bitcoin address for the charity."
			)
		)
	);

	return $metaBoxes;
}

/**
 * List charities.
 */
function charity_list() {
	wp_enqueue_style(
		"charity-accounts",
		CHARITY_URL."/wp-charity-accounts.css"
	);

	$q=new WP_Query(array(
		"post_type"=>"charity"
	));

	$posts=$q->get_posts();
	$vars=array();
	$vars["charities"]=array();

	foreach ($posts as $post) {
		$attachmentId=get_post_meta($post->ID,"logo",TRUE);
		$logoUrl=wp_get_attachment_image_url($attachmentId,array(150,150));

		$charityView=array(
			"logoUrl"=>$logoUrl,
			"title"=>$post->post_title,
			"description"=>get_post_meta($post->ID,"description",TRUE),
			"url"=>get_post_meta($post->ID,"url",TRUE),
			"isCurrentVote"=>FALSE
		);

		$vars["charities"][]=$charityView;
	}

	return charity_render_template(__DIR__."/charity-listing.php",$vars);
}

add_action('init','charity_init');
add_filter("rwmb_meta_boxes","charity_rwmb_meta_boxes");
add_shortcode("list-charities","charity_list");