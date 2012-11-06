<?php echo $html->includeCss('ui.signUp'); ?>
<div id="ui-signUp-container">
	<div id="ui-signUp-title">
		<h2>Sign Up</h2>
	</div>
	<div id="ui-signUp-body">
		<?php
		if (isset($error)) {
			echo '<span class="ui-framework-error-message"><ul>';
			foreach($error as $err) {
				echo '<li>'.$err.'</li>';
			}
			echo '</ul></span>';
		}
		?>
		<form action="" method="post" id="signUpForm">
			<table id="signUpForm-container">
				<?php if ($session_array['user_type']) : ?>
					<tr>
						<td></td>
							<td>
								<span id="ui-signUp-text">
									<b><?php echo $session_array['name']; ?><br />
										<?php
											switch($session_array['license_status']) {
												case 0 : echo 'Licensed Real Estate Agent'; break;
												case 1 : echo 'Licensed Associate Broker'; break;
												case 2 : echo 'Licensed Real Estate Broker'; break;
											}
										?></b>
										<br /><b><?php echo $session_array['company']; ?><br />
										<?php echo $session_array['email']; ?></b>
								</span>
							</td>
						</tr>
				<?php endif; ?>
				<?php if ($session_array['user_type']) : ?>
					<tr><td><input type="checkbox" name="agree_info" value="1"<?php echo (isset($_POST['agree_info']) && !empty($_POST['agree_info'])) ? ' checked="CHECKED"' : ''; ?> /></td><td>I confirm the above information is correct</td></tr>
				<?php endif; ?>
				<?php if ($session_array['user_type']==0) : ?>
					<tr>
						<td></td>
						<td>
							<label>Name</label>
							<input type="text" name="name" />
						</td>
					</tr>	
					<tr>
						<td></td>
						<td>
							<label>Email</label>
							<input type="text" name="email" />
						</td>
					</tr>	
					<tr>
						<td></td>
						<td>
							<label>Password</label>
							<input type="password" name="password" />
						</td>
					</tr>	
				<?php endif; ?>
				<tr>
					<td><input type="checkbox" name="agree_tos" value="1"<?php echo (isset($_POST['agree_tos']) && !empty($_POST['agree_tos'])) ? ' checked="CHECKED"' : ''; ?> /></td><td>I agree to the <a href="<?php echo BASE_PATH; ?>">Terms of Service</a></td>
				</tr>	
				<tr>
					<td><input type="checkbox" name="agree_pp" value="1"<?php echo (isset($_POST['agree_pp']) && !empty($_POST['agree_pp'])) ? ' checked="CHECKED"' : ''; ?> /></td><td>I agree to the <a href="<?php echo BASE_PATH; ?>">Privacy Policy</a></td>
				</tr>
				<tr>
					<td><input type="checkbox" name="agree_ffh" value="1"<?php echo (isset($_POST['agree_ffh']) && !empty($_POST['agree_ffh'])) ? ' checked="CHECKED"' : ''; ?> /></td><td>I agree to the <a href="<?php echo BASE_PATH; ?>">Federal Fair Housing Laws</a></td>
				</tr>
			</table>
			
			<input type="hidden" name="user_type" value="<?php echo $session_array['user_type']; ?>" />
			
			<?php if ($session_array['user_type']) : ?>
			<input type="hidden" name="company" value="<?php echo $session_array['company']; ?>" />
			<input type="hidden" name="license_status" value="<?php echo $session_array['license_status']; ?>" />
			<input type="hidden" name="name" value="<?php echo $session_array['name']; ?>" />
			<input type="hidden" name="email" value="<?php echo $session_array['email']; ?>" />
			<input type="hidden" name="md5Password" value="<?php echo $session_array['password']; ?>" />
			<input type="submit" name="signUpForm_submit" id="signUpForm-submit" value="Start 30-day Free Trial" />
			<?php endif; ?>
			<?php if ($session_array['user_type']==0) : ?>
				<input type="hidden" name="desired_locale" value="<?php echo $session_array['desired_locale']; ?>" />
				<input type="hidden" name="max_rent" value="<?php echo $session_array['max_rent']; ?>" />
				<input type="hidden" name="occupants_adults" value="<?php echo $session_array['occupants_adults']; ?>" />
				<input type="hidden" name="desired_bedrooms" value="<?php echo $session_array['desired_bedrooms']; ?>" />
				<input type="hidden" name="credit_snapshot" value="<?php echo $session_array['credit_snapshot']; ?>" />
				<input type="submit" name="signUpForm_submit" id="signUpForm-submit" value="Sign Up" />
			<?php endif; ?>
		</form>
		
		<span id="ui-signUp-signIn">Already have an account? <a href="<?php echo BASE_PATH.'/signin'; ?>/">Sign In</a></span>
	</div>
</div>
