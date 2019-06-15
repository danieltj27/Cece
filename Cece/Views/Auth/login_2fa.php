<form action="<?php echo auth_url( 'login/2fa/' ); ?>" method="post">

	<h1 class="h6">Two Factor Authentication</h1>

	<fieldset>
		<label for="code">Code</label>
		<input type="email" name="code" id="code" />
		<p class="input-desc"><a href="<?php echo auth_url( 'login/2fa/resend/' ); ?>">Didn't get a code?</a></p>
	</fieldset>

	<fieldset>
		<button type="submit" class="button button--primary">Log in</button>
	</fieldset>

</form>

