<div class="content__banner">

	<div class="container">

		<div class="grid">

			<div class="row row--inline">

				<div class="col col--50 col-mob--100">

					<h1 class="mob-only-margin">Media Details</h1>

				</div>

				<div class="col col--50 col-mob--100 text--right text-mob--left">

					<a href="<?php echo csrfify_url( dashboard_url( 'media/delete/' . $media->ID . '/' ) ); ?>" class="button button--warning js-delete-warn">Delete</a>

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

					<p><a href="<?php echo dashboard_url( 'media/' ); ?>" class="button"><i class="fas fa-chevron-left" aria-hidden="true"></i> All Media</a></p>

				</div>

			</div>

			<div class="row">

				<div class="col col--25 col-tab--50 col-mob--100">

					<?php if ( $media->is_image() ) : ?>

						<img src="<?php echo $media->get_url(); ?>" class="image" alt="<?php echo $media->media_name; ?>" />

					<?php else : ?>

						<p><em>No preview available.</em></p>

					<?php endif; ?>

				</div>

				<div class="col col--75 col-tab--50 col-mob--100">

					<ul>

						<li><strong>ID:</strong> <?php echo $media->ID; ?></li>

						<li><strong>Name:</strong> <?php echo $media->media_name; ?></li>

						<li><strong>Type:</strong> <?php echo $media->media_type; ?></li>

						<li><strong>Size:</strong> <?php echo $media->get_size(); ?></li>

						<li><strong>Directory:</strong> <?php echo $media->media_dir; ?></li>

						<li><strong>Permalink:</strong> <a href="<?php echo $media->get_url(); ?>" target="_blank"><?php echo $media->get_filename(); ?></a></li>

						<li><strong>Uploaded at:</strong> <abbr title="<?php echo $media->uploaded_at; ?>"><?php echo date( 'jS F Y, H:i', strtotime( $media->uploaded_at ) ); ?></abbr></li>

					</ul>

				</div>

			</div>

		</div>

	</div>

</div>

