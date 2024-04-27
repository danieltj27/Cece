<div class="content__banner">

	<div class="container">

		<div class="grid">

			<div class="row row--inline">

				<div class="col col--100">

					<h1 class="no-margin">Extension Manager</h1>

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

			<div class="row">

				<div class="col col--100">

					<p><a href="<?php echo dashboard_url( 'extensions/' ); ?>" class="button"><i class="fas fa-chevron-left" aria-hidden="true"></i> All Extensions</a></p>

				</div>

			</div>

			<div class="row">

				<div class="col col--100">

					<h2 class="h4"><?php echo $extension->ext_name; ?></h2>

					<p><?php echo $extension->ext_description; ?></p>

					<hr />

					<ul>

						<li><strong>Status:</strong> <?php if ( $extension->is_installed() && 'on' != blog_setting( 'flag_ext_safe' ) ) : ?>Active<?php else : ?>Inactive<?php endif; ?></li>

						<li><strong>Version:</strong> <?php echo $extension->ext_version; ?></li>

						<li><strong>Author:</strong> <?php echo $extension->ext_author_name; ?></li>

						<li><strong>Website:</strong> <a href="<?php echo $extension->ext_author_url; ?>"><?php echo $extension->ext_author_url; ?></a></li>

						<li><strong>Licence:</strong> <a href="<?php echo $extension->ext_licence_url; ?>"><?php echo $extension->ext_licence_name; ?></a></li>

					</ul>

					<form action="<?php echo dashboard_url( 'extensions/save/' ); ?>" method="post">

						<input type="hidden" name="domain" id="domain" value="<?php echo $extension->ext_domain; ?>" />

						<?php if ( $extension->is_installed() ) : ?>

							<button type="submit" class="button button--warning">Uninstall</button>

						<?php else : ?>

							<button type="submit" class="button button--primary">Install</button>

						<?php endif; ?>

					</form>

				</div>

			</div>

		</div>

	</div>

</div>

