<div class="wrap">
	<h2>Charity Gaming</h2>

	<form method="post" action="options.php">
	    <?php settings_fields( 'charity' ); ?>
	    <?php do_settings_sections( 'charity' ); ?>

	    <p>
	    	The revenue account will be checked on a regular schedule. The contents
	    	from that account will be distributed according to the rules set up
	    	below.
	    </p>
	    <table class="form-table">
	        <tr valign="top">
	            <th scope="row">Operational Expenses</th>
	            <td>
	                <input type="text"
	                    name="charity_operational_percentage"
	                    value="<?php echo esc_attr(get_option("charity_operational_percentage")); ?>" 
	                    class="regular-text"/>

	                <p class="description">
		                This percentage of the revenues will be moved to the operational account.
	                </p>
	            </td>
	        </tr>

	        <tr valign="top">
	            <th scope="row">Withdraw to Charities</th>
	            <td>
	                <input type="text"
	                    name="charity_withdraw_when"
	                    value="<?php echo esc_attr(get_option("charity_withdraw_when")); ?>" 
	                    class="regular-text"/>

	                <p class="description">
		                Send funds to the charity bitcoin account if the local account has a larger
		                btc amount than this.
	                </p>
	            </td>
	        </tr>

	        <tr valign="top">
	            <th scope="row">Distribution Schedule</th>
	            <td>
	            	<select name="charity_distribute_revenue">
		            	<?php charity\Template::options($schedules,$currentSchedule); ?>
	            	</select>
	                <p class="description">
	                    How often should the revenue be distributed.<br/>
                        <a href="<?php echo $collectUrl; ?>">Distribute now</a>
	                </p>
	            </td>
	        </tr>
	    </table>

	    <?php submit_button(); ?>
	</form>

</div>