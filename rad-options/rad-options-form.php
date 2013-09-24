<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Company Information</h2>

	<form method="post" action="options.php">
		<?php //connect this form to the settings group we registered in the plugin
		settings_fields('rad_options_group');
		//get the current values out of the DB. this makes the form "sticky"
		$values = get_option( 'rad_options' );
		?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label>Company Phone Number</label>
					</th>
					<td>
						<input type="tel" name="rad_options[phone]" class="regular-text" value="<?php echo $values['phone']; ?>" >
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label>Customer Support Email Address</label>
					</th>
					<td>
						<input type="email" name="rad_options[email]" class="regular-text" value="<?php echo $values['email']; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label>Company Mailing Address</label>
					</th>
					<td>
						<textarea name="rad_options[address]" class="code" rows="5" cols="45"><?php echo $values['address']; ?></textarea>
					</td>
				</tr>	
			</tbody>
		</table>
		<?php submit_button(); ?>


	</form>
</div>