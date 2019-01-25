<form action="<?php echo auth_url( 'forgot/send/' ); ?>" method="post">

	<h1 class="h6">Forgot Password</h1>

	<fieldset>
		<label for="email">Email address</label>
		<input type="email" name="email" id="email" />
		<p class="input-desc"><a href="<?php echo auth_url( 'login/' ); ?>">I know my password.</a></p>
	</fieldset>

	<fieldset>
		<button type="submit" class="button button--primary">Reset</button>
	</fieldset>

</form>

