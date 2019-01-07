<!DOCTYPE html>
<html lang="<?php echo blog_lang(); ?>">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />
<title><?php echo load_title( $title ); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo assets_url( 'Css/style.css' ); ?>" />
</head>
<body id="cece" class="cece dashboard">

	<main class="dashboard">

		<a class="header-toggle"><i class="fas fa-bars"></i> Menu</a>

		<menu class="header">

			<div class="header__gutter">

				<a href="<?php echo home_url(); ?>" class="header__logo">

					<div class="blog__logo">Cece</div>

					<div class="blog__name"><?php echo blog_name(); ?></div>

				</a>

				<div class="header__search">

					<?php if ( is_author() ) : ?>

						<form action="<?php echo dashboard_url( 'search/' ); ?>" method="get">

							<label for="query" class="screen-reader-only">Search</label>
							<input type="search" name="query" id="query" placeholder="Search for posts..." <?php if ( get_search_query() ) : ?>value="<?php echo get_search_query(); ?>" <?php endif; ?>/>
							<button type="submit" id="submit" aria-label="Search posts"><i class="fas fa-search" aria-hidden="true"></i></button>

						</form>

					<?php endif; ?>

				</div>

				<div class="header__menu">

					<?php $menu_links = get_dashboard_links(); ?>

					<?php if ( ! empty( $menu_links ) ) : ?>

						<ul>

							<?php foreach ( $menu_links as $menu_link ) : ?>

								<?php if ( true === $menu_link[ 'auth' ] ) : ?>

									<?php if ( true === $menu_link[ 'spacer' ] ) : ?>

										<li class="spacer"></li>

									<?php else : ?>

										<li><a href="<?php echo $menu_link[ 'url' ]; ?>" class="menu-link--<?php echo $menu_link[ 'key' ]; ?>"><i class="fas fa-<?php echo $menu_link[ 'icon' ]; ?>" aria-hidden="true"></i> <?php echo $menu_link[ 'label' ]; ?></a></li>

									<?php endif; ?>

								<?php endif; ?>

							<?php endforeach; ?>

						</ul>

					<?php endif; ?>

				</div>

			</div>

		</menu>

		<section class="content">

			<div class="content__wrapper">

				<?php Controller::view( $view, $args ); ?>

			</div>

			<div class="content__footer">

				<div class="container">

					<div class="grid">

						<div class="row">

							<div class="col col--100">

								<p><a href="<?php echo dashboard_url( 'about/' ); ?>">Version <?php echo blog_version(); ?></a></p>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>

	</main>

<script type="text/javascript" src="<?php echo assets_url( 'Js/Vendor/jquery.min.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo assets_url( 'Js/editor.min.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo assets_url( 'Js/scripts.min.js' ); ?>"></script>
</body>
</html>