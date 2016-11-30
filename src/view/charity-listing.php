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

<form method="post">
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
				<b>Votes:</b>
				<?php echo $charity["votes"]; ?>
				(<?php echo $charity["votePercent"]; ?>%)<br>
				<?php if ($charity["isCurrentVote"]) { ?>
					<button type="submit" 
							name="charityId"
							value="<?php echo $charity["id"]; ?>"
							name="charityId"
							class="charity-listing-vote current">
						Your Vote
					</button>
				<?php } else { ?>
					<button type="submit"
							name="charityId"
							value="<?php echo $charity["id"]; ?>"
							name="charityId"
							class="charity-listing-vote">
						Vote
					</button>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</form>