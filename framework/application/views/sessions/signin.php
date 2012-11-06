<?php echo $html->includeCss('ui.signIn'); ?>
<div id="ui-signIn-container">
	<div id="ui-signIn-title">
		<h2>Sign In</h2>
	</div>
	<div id="ui-signIn-body">
		<?php
		if (isset($error)) {
			echo '<span class="ui-framework-error-message">'.$error.'</span>';
		}
		?>
		<form action="" method="post" id="loginForm">
			<span class="ui-signIn-label">Email</span>
			<input type="text" name="loginForm_email" value="<?php echo (isset($_POST['loginForm_email'])) ? $_POST['loginForm_email'] : ''; ?>" />
			<span class="ui-signIn-label">Password</span>
			<input type="password" name="loginForm_password" />
			<input type="submit" name="loginForm_submit" id="loginForm-submit" value="Sign In" />
		</form>
		
		<span id="ui-signIn-signUp">Don't have an account? <a href="<?php echo BASE_PATH; ?>/">Get started for free</a></span>
	</div>
</div>
