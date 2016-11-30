<?php

namespace charity;

require_once __DIR__."/../../ext/wprecord/WpRecord.php";

use \WpRecord;
use \Exception;

/**
 * Represents a vote for a charity.
 */
class Vote extends WpRecord {

	private static $votesByCharityId;
	private static $numValidVotes;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->valid=TRUE;
	}

	/**
	 * Initialize.
	 */
	public function initialize() {
		self::field("id","integer not null auto_increment");
		self::field("ip","varchar(255) not null");
		self::field("charityId","integer not null");
		self::field("valid","integer not null");
		self::field("stamp","integer not null");
	}

	/**
	 * Get related charity post.
	 */
	public function getCharityPost() {
		$post=get_post($this->charityId);

		return $post;
	}

	/**
	 * Get current vote for current ip.
	 */
	public static function getCurrent() {
		$vote=Vote::findOneBy(array(
			"ip"=>$_SERVER["REMOTE_ADDR"],
			"valid"=>TRUE
		));

		return $vote;
	}

	/**
	 * Get current charity post.
	 */
	public static function getCurrentCharityPost() {
		$currentVote=Vote::getCurrent();
		if ($currentVote)
			return $currentVote->getCharityPost();

		else
			return NULL;
	}

	/**
	 * How long is this vote valid?
	 */
	public function getValidUntil() {
		return $this->stamp+Vote::getValidDays()*24*60*60;
	}

	/**
	 * Invalidate all votes for the ip.
	 */
	public static function invalidateIp($ip) {
		$votes=Vote::findAllBy("ip",$ip);
		foreach ($votes as $vote) {
			$vote->valid=FALSE;
			$vote->save();
		}
	}

	/**
	 * Fetch votes by chairty id.
	 */
	public static function fetchVotesByCharityId() {
		global $wpdb;

		if (self::$votesByCharityId)
			return;

		self::$votesByCharityId=array();

		$countRows=$wpdb->get_results(
			"SELECT   charityId, COUNT(ip) as count ".
			"FROM     {$wpdb->prefix}vote ".
			"WHERE    valid=1 ".
			"GROUP BY charityId ",
			ARRAY_A);

		if ($wpdb->last_error)
			throw new Exception($wpdb->last_error);

		foreach ($countRows as $countRow)
			self::$votesByCharityId[$countRow["charityId"]]=$countRow["count"];
	}

	/**
	 * Get number of votes for the charity.
	 */
	public static function getNumVotesForChairtyId($id) {
		self::fetchVotesByCharityId();

		if (!isset(self::$votesByCharityId[$id]))
			return 0;

		return self::$votesByCharityId[$id];
	}

	/**
	 * Get percentage of votes for the charity.
	 */
	public static function getPercentVotesForChairtyId($id) {
		$total=self::getVumValidVotes();
		if (!$total)
			return 0;

		return round(100*self::getNumVotesForChairtyId($id)/$total);
	}

	/**
	 * Get total number of valid votes.
	 */
	public static function getVumValidVotes() {
		global $wpdb;

		if (self::$numValidVotes)
			return self::$numValidVotes;

		$count=$wpdb->get_var("SELECT COUNT(*) from {$wpdb->prefix}vote WHERE valid=1");

		if ($wpdb->last_error)
			throw new Exception($wpdb->last_error);

		self::$numValidVotes=$count;

		return $count;
	}

	/**
	 * How long are votes valid?
	 */
	public static function getValidDays() {
		return 30;
	}
}