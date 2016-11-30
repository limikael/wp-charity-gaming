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