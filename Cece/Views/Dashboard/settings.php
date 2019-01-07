<form action="<?php echo dashboard_url( 'settings/save/' ); ?>" method="post">

	<div class="content__banner">

		<div class="container">

			<div class="grid">

				<div class="row row--inline">

					<div class="col col--100">

						<h1 class="no-margin">Settings</h1>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="content__main">

		<div class="container">

			<div class="grid">

				<div class="row">

					<div class="col col--100">

						<?php echo do_notices(); ?>

					</div>

				</div>

				<div class="row row--centered">

					<div class="col col--50 col-tab--75 col-tab--100">

						<fieldset>
							<label for="name">Blog name <span class="required">*</span></label>
							<?php $settings->fetch( 'name', 'setting_key' ); ?>
							<input type="text" name="name" id="name" value="<?php echo $settings->setting_value; ?>" />
						</fieldset>

						<fieldset>
							<label for="domain">Blog domain <span class="required">*</span></label>
							<?php $settings->fetch( 'domain', 'setting_key' ); ?>
							<input type="text" name="domain" id="domain" value="<?php echo $settings->setting_value; ?>" />
							<p class="input-desc">Example: <code>example.com</code></p>
						</fieldset>

						<?php if ( $themes ) : ?>

							<fieldset>
								<label for="theme">Theme <span class="required">*</span></label>
								<?php $settings->fetch( 'theme', 'setting_key' ); ?>
								<select name="theme" id="theme">
									<?php foreach ( $themes as $theme ) : ?>
										<option value="<?php echo $theme[ 'domain' ]; ?>"<?php if ( theme_domain() == $theme[ 'domain' ] ) : ?> selected="selected"<?php endif; ?>><?php echo $theme[ 'name' ]; ?> by <?php echo $theme[ 'author_name' ]; ?></option>
									<?php endforeach; ?>
								</select>
							</fieldset>

						<?php endif; ?>

						<fieldset>
							<label for="email">Email address <span class="required">*</span></label>
							<?php $settings->fetch( 'email', 'setting_key' ); ?>
							<input type="email" name="email" id="email" value="<?php echo $settings->setting_value; ?>" />
							<p class="input-desc">The <em>from</em> email address for all blog emails.</p>
						</fieldset>

						<fieldset>
							<label for="per_page">Per page <span class="required">*</span></label>
							<?php $settings->fetch( 'per_page', 'setting_key' ); ?>
							<input type="number" name="per_page" id="per_page" value="<?php echo $settings->setting_value; ?>" min="1" steps="1" />
							<p class="input-desc">The number of posts, users and other items per page.</p>
						</fieldset>

						<fieldset>
							<?php $settings->fetch( 'register', 'setting_key' ); ?>
							<label for="register-check"><input type="checkbox" name="register-check" id="register-check" data-check-input="register"<?php if ( 'on' == $settings->setting_value ) : ?> checked="checked"<?php endif; ?> value="<?php echo $settings->setting_value; ?>" /> Let anyone register a new account.</label>
							<input type="hidden" name="register" id="register" value="<?php echo $settings->setting_value; ?>" />
						</fieldset>

						<fieldset>
							<?php $settings->fetch( 'https', 'setting_key' ); ?>
							<label for="https-check"><input type="checkbox" name="https-check" id="https-check" data-check-input="https"<?php if ( 'on' == $settings->setting_value ) : ?> checked="checked"<?php endif; ?> value="<?php echo $settings->setting_value; ?>" /> Access blog over a HTTPS (secure) connection.</label>
							<input type="hidden" name="https" id="https" value="<?php echo $settings->setting_value; ?>" />
						</fieldset>

						<fieldset>
							<?php $settings->fetch( 'hsts', 'setting_key' ); ?>
							<label for="hsts-check"><input type="checkbox" name="hsts-check" id="hsts-check" data-check-input="hsts"<?php if ( 'on' == $settings->setting_value ) : ?> checked="checked"<?php endif; ?> value="<?php echo $settings->setting_value; ?>" /> Force HTTPS connections with extreme prejudice.</label>
							<input type="hidden" name="hsts" id="hsts" value="<?php echo $settings->setting_value; ?>" />
						</fieldset>

						<fieldset>
							<?php $settings->fetch( 'auto_check', 'setting_key' ); ?>
							<label for="updates-check"><input type="checkbox" name="updates-check" id="updates-check" data-check-input="updates"<?php if ( 'on' == $settings->setting_value ) : ?> checked="checked"<?php endif; ?> value="<?php echo $settings->setting_value; ?>" /> Automatically check for system updates each day.</label>
							<input type="hidden" name="updates" id="updates" value="<?php echo $settings->setting_value; ?>" />
						</fieldset>

						<fieldset>
							<label for="language">Language</label>
							<?php $settings->fetch( 'language', 'setting_key' ); ?>
							<select name="language" id="language">
								<option value="en_gb">British English</option>
							</select>
						</fieldset>

						<fieldset>
							<label for="timezone">Timezone</label>
							<?php $settings->fetch( 'timezone', 'setting_key' ); ?>
							<select name="timezone" id="timezone">
								<?php foreach ( $timezones as $timezone ) : ?>
									<option value="<?php echo $timezone; ?>"<?php if ( $timezone == $settings->setting_value ) : ?> selected="selected"<?php endif; ?>><?php echo $timezone; ?></option>
								<?php endforeach; ?>
							</select>
						</fieldset>

						<fieldset>
							<button type="submit" class="button button--primary">Save Changes</button>
						</fieldset>

					</div>

				</div>

			</div>

		</div>

	</div>

</form>

