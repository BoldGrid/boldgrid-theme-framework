<?php
/**
 * Class: BoldGrid_Framework_Comments
 *
 * The class responsible for comment display.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework/comments
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
class BoldGrid_Framework_Comments {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * BoldGrid Comments
	 *
	 * This tells wp_list_comments to use our custom callback, and also is
	 * providing the template and bootstrap styling for comments and comment
	 * forms.  Some of the styles do get applied with javascript in the
	 * boldgrid-bootstrap-shim.js file.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_comments() {

		if ( have_comments( ) ) : ?>
			<div id="comments" class="comments-area">
				<header>
					<h2 class="comments-title">
						<?php
							printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number( ), 'comments title', 'bgtfw' ),
							number_format_i18n( get_comments_number( ) ), '<span>' . get_the_title( ) . '</span>' );
						?>
					</h2>
				</header>

				<?php if ( get_comment_pages_count( ) > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<nav id="comment-nav-above" class="comment-navigation" role="navigation">
					<h1 class="sr-only"><?php _e( 'Comment navigation', 'bgtfw' ); ?></h1>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'bgtfw' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'bgtfw' ) ); ?></div>
				</nav><!-- #comment-nav-above -->
				<?php endif; // end comment navigation ?>

				<ol class="comment-list">
					<?php
						/**
						 * Loop through and list the comments. Tell wp_list_comments()
						 * to use boldgrid_bootstrap_comment(  ) to format the comments.
						 * If you want to overload this in a child theme then you can
						 * define boldgrid_bootstrap_comment(  ) and that will be used instead.
						 */
						wp_list_comments(
							array(
								'callback' => array( $this, 'boldgrid_bootstrap_comment' ),
								'avatar_size' => 50,
							)
						);
					?>
				</ol><!-- .comment-list -->

				<?php if ( get_comment_pages_count( ) > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<nav id="comment-nav-below" class="comment-navigation" role="navigation">
					<h1 class="sr-only"><?php _e( 'Comment navigation', 'bgtfw' ); ?></h1>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'bgtfw' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'bgtfw' ) ); ?></div>
				</nav><!-- #comment-nav-below -->
				<?php endif; // check for comment navigation ?>

			<?php endif; // have_comments() ?>

			<?php
				// If comments are closed and there are comments
			if ( ! comments_open( )
					&& '0' != get_comments_number( )
					&& post_type_supports( get_post_type( ), 'comments' ) ) :
			?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'bgtfw' ); ?></p>
		<?php endif; ?>

		<?php comment_form( $args = array(
			'id_form'           => 'commentform',                         // wp default value
			'id_submit'         => 'commentsubmit',                       // wp default value
			'title_reply'       => __( 'Leave a Reply',       'bgtfw' ),  // wp default value
			'title_reply_to'    => __( 'Leave a Reply to %s', 'bgtfw' ),  // wp default value
			'cancel_reply_link' => __( 'Cancel Reply',        'bgtfw' ),  // wp default value
			'label_submit'      => __( 'Post Comment',        'bgtfw' ),  // wp default value
			'class_submit' => 'button-primary',
			'comment_field' => '<p><textarea placeholder="Start typing..." id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'comment_notes_after' => '<p class="form-allowed-tags">' .
			__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:', 'bgtfw' ) .
			'</p><div class="alert alert-info">' . allowed_tags( ) . '</div>',
		) );

	}

	/**
	 * boldgrid_bootstrap_comment(  );
	 *
	 * This is the BoldGrid Bootstrap template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments(  ) for displaying the comments.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_bootstrap_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;

		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID( ); ?>" <?php comment_class( 'media' ); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:', 'bgtfw' ); ?> <?php comment_author_link( ); ?> <?php edit_comment_link( __( 'Edit', 'bgtfw' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		<?php else : ?>

		<li id="comment-<?php comment_ID( ); ?>"
			<?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID( ); ?>" class="comment-body media">

				<a class="pull-left" href="#">
					<?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
				</a>

				<div class="media-body">
					<div class="media-body-wrap panel panel-default">
						<div class="panel-heading">
							<h5 class="media-heading">
								<?php printf( __( '%s <span class="says">says:</span>', 'bgtfw' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link( ) ) ); ?>
							</h5>

							<div class="comment-meta">

								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">

									<time datetime="<?php comment_time( 'c' ); ?>">
										<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'bgtfw' ), get_comment_date( ), get_comment_time( ) ); ?>
									</time>

								</a>

								<?php edit_comment_link( __( '<span style="margin-left: 5px;" class="fa fa-edit"></span> Edit', 'bgtfw' ), '<span class="edit-link">', '</span>' ); ?>

							</div>
						</div>
						<?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'bgtfw' ); ?></p>
						<?php endif; ?>
						<div class="comment-content panel-body">
							<?php comment_text( ); ?>
						</div><!-- .comment-content -->
						<?php
						comment_reply_link(
							array_merge(
								$args, array(
									'add_below' => 'div-comment',
									'depth' 	=> $depth,
									'max_depth' => $args['max_depth'],
									'before' 	=> '<footer class="reply comment-reply panel-footer">',
									'after' 	=> '</footer><!-- .reply -->',
								)
							)
						); ?>

					</div><!-- .panel -->
				</div><!-- .media-body -->
			</article> <!-- .comment-body -->

		<?php
		endif;

	}

	/**
	 * Bootstrap styled Comment form.
	 */
	public function bootstrap_comment_form_defaults( $defaults ) {

		$commenter = wp_get_current_commenter( );

		$req = get_option( 'require_name_email' );

		$aria_req = ( $req ? " aria-required='true'" : '' );

		$defaults['fields'] = array(
			'author' => '<div class="form-group comment-form-author">' .
					'<label for="author" class="col-sm-3 control-label">' . __( 'Name', 'bgtfw' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					'<div class="col-sm-9">' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"  class="form-control"' . $aria_req . ' />' .
					'</div>' .
			'</div>',
			'email'  => '<div class="form-group comment-form-email">' .
					'<label for="email" class="col-sm-3 control-label">' . __( 'Email', 'bgtfw' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					'<div class="col-sm-9">' .
						'<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" class="form-control"' . $aria_req . ' />' .
					'</div>' .
				'</div>',
			'url'    => '<div class="form-group comment-form-url">
				<label for="url" class="col-sm-3 control-label"">' .
					__( 'Website', 'bgtfw' ) .
				'</label>
					<div class="col-sm-9">
						<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" class="form-control" />
					</div>
				</div>',
		);

		$defaults['comment_field'] = '<div class="form-group comment-form-comment">
			<label for="comment" class="col-sm-3 control-label">' .
			_x( 'Comment', 'noun', 'bgtfw' ) .
			'</label>
			<div class="col-sm-9">
				<textarea id="comment" name="comment" aria-required="true" class="form-control" rows="8"></textarea>
				<span class="help-block form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'bgtfw' ), ' <code>' . allowed_tags() . '</code>' ) . '</span>
			</div>
		</div>';

		$defaults['comment_notes_after'] = '<div class="form-group comment-form-submit">';

		return $defaults;
	}

	public function bootstrap_comment_form( $post_id ) {
		if ( have_comments( ) ) {
			// Closing tag for 'comment_notes_after'.
			echo '</div><!-- .form-group .comment-form-submit -->';
		}
	}
}
