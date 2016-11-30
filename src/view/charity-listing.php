<?php if ($showVoteCastInfo) { ?>
	<div class="charity-vote-cast-info">
		<b>Thank you!</b><br><br>
		Your vote from ip number <b><?php echo $voteIp; ?></b>
		has been counted for <?php echo $voteName; ?>.<br><br>
		This vote will be active and affect the revenue sharing on this site
		for <?php echo $voteDays; ?> days, until <?php echo $voteUntil; ?>.
		After this time you will need to cast the vote again.
	</div>
<?php } ?>

<?php foreach ($charities as $charity) { ?>
	<div class="charity-listing">
		<img src="<?php echo $charity["logoUrl"]; ?>"/>
		<div class="title">
			<?php echo $charity["title"]; ?>
		</div>
		<div class="description">
			<?php echo $charity["description"]; ?>
			<br/><br/>
			<b>More info:</b>
			<a href="<?php echo $charity["url"]; ?>">
				<?php echo $charity["url"]; ?>
			</a>
		</div>
		<div class="info">
			<b>Votes:</b> 123 (20%)<br>
			<form>
				<?php if ($charity["isCurrentVote"]) { ?>
					<input type="submit" value="Your Vote" class="charity-listing-vote current"/>
				<?php } else { ?>
					<input type="submit" value="Vote" class="charity-listing-vote"/>
				<?php } ?>
			</form>
		</div>
	</div>
<?php } ?>