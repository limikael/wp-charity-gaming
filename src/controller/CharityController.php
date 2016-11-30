<?php

namespace charity;

require_once __DIR__."/../utils/Template.php";
require_once __DIR__."/../utils/Singleton.php";
require_once __DIR__."/../model/Vote.php";

use \WP_Query;
use \Exception;

/**
 * Charity controller.
 */
class CharityController extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('init',array($this,'init'));
		add_filter("rwmb_meta_boxes",array($this,"rwmbMetaBoxes"));
		add_shortcode("list-charities",array($this,"listCharities"));
	}

	/**
	 * The WordPress init action.
	 */
	public function init() {
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
	 * Set up meta boxes.
	 */
	public function rwmbMetaBoxes($metaBoxes) {
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
	function listCharities() {
		wp_enqueue_style(
			"charity-gaming",
			CHARITY_URL."/wp-charity-gaming.css"
		);

		$q=new WP_Query(array(
			"post_type"=>"charity"
		));

		$posts=$q->get_posts();
		$vars=array();
		$vars["showVoteCastInfo"]=FALSE;

		if (isset($_REQUEST["charityId"])) {
			$vars["showVoteCastInfo"]=TRUE;

			Vote::invalidateIp($_SERVER["REMOTE_ADDR"]);

			$vote=new Vote();
			$vote->ip=$_SERVER["REMOTE_ADDR"];
			$vote->stamp=time();
			$vote->charityId=$_REQUEST["charityId"];
			$vote->save();

			$vars["voteIp"]=$vote->ip;
			$vars["voteName"]=$vote->getCharityPost()->post_title;
			$vars["voteDays"]=Vote::getValidDays();
			$vars["voteUntil"]=date("l jS \of F Y",$vote->getValidUntil());
		}

		$currentPost=Vote::getCurrentCharityPost();
		$vars["charities"]=array();

		foreach ($posts as $post) {
			$attachmentId=get_post_meta($post->ID,"logo",TRUE);
			$logoUrl=wp_get_attachment_image_url($attachmentId,array(150,150));

			$charityView=array(
				"logoUrl"=>$logoUrl,
				"title"=>$post->post_title,
				"description"=>get_post_meta($post->ID,"description",TRUE),
				"url"=>get_post_meta($post->ID,"url",TRUE),
				"id"=>$post->ID,
				"votes"=>Vote::getNumVotesForChairtyId($post->ID),
				"votePercent"=>Vote::getPercentVotesForChairtyId($post->ID),
				"isCurrentVote"=>FALSE
			);

			if ($currentPost && $post->ID==$currentPost->ID)
				$charityView["isCurrentVote"]=TRUE;

			$vars["charities"][]=$charityView;
		}

		$t=new Template(__DIR__."/../view/charity-listing.php");
		return $t->render($vars);
	}
}