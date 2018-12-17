<form action="<?php echo dashboard_url( 'posts/save/' ); ?>" method="post">

	<div class="content__banner">

		<div class="container">

			<div class="grid">

				<div class="row row--inline">

					<div class="col col--33 col-tab--50 col-mob--100">

						<h1 class="mob-only-margin">New Post</h1>

					</div>

					<div class="col col--66 col-tab--50 col-mob--100 text--right text-mob--left">

						<a class="button toolbar-settings-toggle" tabindex="0" role="link">Settings</a>

						<button type="submit" class="button button--primary">Create</button>

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
				<input type="text" name="path" id="path" />
				<p class="input-desc">Example: <code>hello-world</code></p>
			</fieldset>

			<fieldset>
				<label for="published_at">Publish Date</label>
				<input type="datetime-local" name="published_at" id="published_at" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}" />
			</fieldset>

			<fieldset>
				<label for="status">Status</label>
				<select name="status" id="status">
					<option value="publish">Published</option>
					<option value="draft" selected="selected">Draft</option>
				</select>
			</fieldset>

			<fieldset>
				<label for="tags">Tags</label>
				<input type="text" name="tags" id="tags" />
				<p class="input-desc">Separate tags with a comma.</p>
			</fieldset>

			<fieldset>
				<label for="author_id">Author</label>
				<select name="author_id" id="author_id">
					<?php foreach ( $users as $user ) : ?>
						<option value="<?php echo $user->ID; ?>"<?php if ( $user->ID == current_user_id() ) : ?> selected="selected"<?php endif; ?>><?php echo $user->user_fullname; ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>

			<fieldset>
				<label for="type">Type</label>
				<select name="type" id="type">
					<?php foreach ( $post_types as $post_type ) : ?>
						<option value="<?php echo $post_type[ 'id' ]; ?>"><?php echo $post_type[ 'labels' ][ 'singular' ]; ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>

			<hr />

			<p><span id="editor__charcount">0</span> characters</p>

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

					<input type="text" name="title" id="title" class="editor__title" placeholder="Your post title..." />

					<textarea name="content" id="editor__textarea" class="editor__textarea" placeholder="Start writing your post..."></textarea>

				</div>

			</div>

		</div>

	</div>

</form>

