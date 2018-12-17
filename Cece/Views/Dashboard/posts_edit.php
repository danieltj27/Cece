<form action="<?php echo dashboard_url( 'posts/save/' ); ?>" method="post">
<input type="hidden" name="id" id="id" value="<?php echo $post->ID; ?>" />

	<div class="content__banner">

		<div class="container">

			<div class="grid">

				<div class="row row--inline">

					<div class="col col--33 col-tab--50 col-mob--100">

						<h1 class="mob-only-margin">Edit Post</h1>

					</div>

					<div class="col col--66 col-tab--50 col-mob--100 text--right text-mob--left">

						<?php if ( 'publish' == $post->post_status ) : ?>

							<a href="<?php echo post_url( $post ); ?>" class="button" target="_blank">View</a>

						<?php endif; ?>

						<a class="button toolbar-settings-toggle" tabindex="0" role="link">Settings</a>

						<button type="submit" class="button button--primary">Save</button>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="editor">

		<div class="editor__settings">

			<p><i class="fas fa-times" aria-hidden="true"></i> <a class="toolbar-settings-toggle" tabindex="0" role="link">Hide editor settings</a></p>

			<fieldset>
				<label for="path">Path</label>
				<input type="text" name="path" id="path" value="<?php echo $post->post_path; ?>" />
				<p class="input-desc">Example: <code>hello-world</code></p>
			</fieldset>

			<fieldset>
				<label for="published_at">Publish Date</label>
				<input type="datetime-local" name="published_at" id="published_at" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}" value="<?php echo date( 'Y-m-d', strtotime( $post->published_at ) ) . 'T' . date( 'H:i', strtotime( $post->published_at ) ); ?>" />
			</fieldset>

			<fieldset>
				<label for="status">Status</label>
				<select name="status" id="status">
					<option value="publish"<?php if ( 'publish' == $post->post_status ) : ?> selected="selected"<?php endif; ?>>Published</option>
					<option value="draft"<?php if ( 'draft' == $post->post_status ) : ?> selected="selected"<?php endif; ?>>Draft</option>
				</select>
			</fieldset>

			<fieldset>
				<label for="tags">Tags</label>
				<input type="text" name="tags" id="tags" value="<?php if ( '' != $post->post_tags ) : ?><?php echo implode( ', ', $post->post_tags ); ?><?php endif; ?>" />
				<p class="input-desc">Separate tags with a comma.</p>
			</fieldset>

			<fieldset>
				<label for="author_id">Author</label>
				<select name="author_id" id="author_id">
					<?php foreach ( $users as $user ) : ?>
						<option value="<?php echo $user->ID; ?>"<?php if ( $user->ID == $post->post_author_ID ) : ?> selected="selected"<?php endif; ?>><?php echo $user->user_fullname; ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>

			<fieldset>
				<label for="type">Type</label>
				<select name="type" id="type">
					<?php foreach ( $post_types as $post_type ) : ?>
						<option value="<?php echo $post_type[ 'id' ]; ?>" <?php if ( $post_type[ 'id' ] == $post->post_type ) : ?> selected="selected"<?php endif; ?>><?php echo $post_type[ 'labels' ][ 'singular' ]; ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>

			<hr />

			<p><span id="editor__charcount"><?php echo strlen( $post->post_content ); ?></span> characters</p>

			<hr />

			<a href="<?php echo csrfify_url( dashboard_url( 'posts/delete/' . $post->ID . '/' ) ); ?>" class="button button--small button--warning js-delete-warn">Permanently Delete</a>

		</div>

		<div class="editor__media" data-media-count="<?php echo count( $media ); ?>">

			<div class="row">

				<div class="col col--50 col-tab--100">

					<h3 class="no-margin">Media Library</h3>

				</div>

				<div class="col col--50 col-tab--100 text--right text-mob--left">

					<a class="button js-media-more" tabindex="0" role="link">Load More</a>

				</div>

			</div>

			<div class="row">

				<div class="col col--100">

					<p><i class="fas fa-times" aria-hidden="true"></i> <a class="toolbar-media-toggle" tabindex="0" role="link">Hide media library</a></p>

					<?php if ( $media ) : ?>

						<ul class="media__gallery">

							<?php foreach ( $media as $file ) : ?>

								<li class="media__item">

									<a href="#" class="insert-media" data-syntax="![<?php echo $file->get_filename(); ?>](<?php echo $file->get_url(); ?>)" style="background-image: url('<?php echo $file->get_url(); ?>');" tabindex="0" role="link">

										<span><?php echo $file->get_filename(); ?></span>
										
										<br />
										
										<span><?php echo $file->get_size(); ?></span>

									</a>

								</li>

							<?php endforeach; ?>

						</ul>

					<?php else : ?>

						<h2 class="h4">No files found</h2>

						<p>There aren't any files to show you right now.</p>

					<?php endif; ?>

				</div>

			</div>

		</div>

		<div class="editor__container">

			<?php echo do_notices(); ?>

			<div class="editor__toolbar">

				<ul>

					<li><button type="button" class="toolbar-item toolbar-heading" id="toolbar-heading" data-syntax="# " aria-label="Add heading text"><i class="fas fa-heading" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-item toolbar-bold" id="toolbar-bold" data-syntax="****" aria-label="Add bold text"><i class="fas fa-bold" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-item toolbar-italic" id="toolbar-italic" data-syntax="**" aria-label="Add italic text"><i class="fas fa-italic" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-media-toggle toolbar-image" id="toolbar-image" data-syntax="![]()" aria-label="Add an image"><i class="fas fa-image" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-item toolbar-quote" id="toolbar-quote" data-syntax="> " aria-label="Add a quote"><i class="fas fa-quote-left" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-item toolbar-list" id="toolbar-list" data-syntax="- " aria-label="Add a list"><i class="fas fa-list" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-item toolbar-link" id="toolbar-link" data-syntax="[]()" aria-label="Add a link"><i class="fas fa-link" aria-hidden="true"></i></button></li>
					<li><button type="button" class="toolbar-item toolbar-code" id="toolbar-code" data-syntax="``" aria-label="Add code"><i class="fas fa-code" aria-hidden="true"></i></button></li>

				</ul>

			</div>

			<div class="editor__content">

				<div class="editor__inputs">

					<input type="text" name="title" id="title" class="editor__title" placeholder="Your post title..." value="<?php echo $post->post_title; ?>" />

					<textarea name="content" id="editor__textarea" class="editor__textarea" placeholder="Start writing your post..."><?php echo $post->post_content; ?></textarea>

				</div>

			</div>

		</div>

	</div>

</form>

