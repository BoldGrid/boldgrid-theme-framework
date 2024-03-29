<?php
/**
 * File: class-boldgrid-framework-comments.php
 *
 * The class responsible for comment display.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework/comments
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid Comments
 *
 * The class responsible for the comments display in a bgtfw theme.
 *
 * @since 1.0.0
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
			<header>
				<h2 class="comments-title">

					<?php
						$comments_number = get_comments_number();
						if ( 1 === $comments_number ) {
							printf(
								/* translators: %s: comments title */
								esc_html_x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'bgtfw' ),
								'<span>' . esc_html( get_the_title() ) . '</span>'
							);
						} else {
							printf(
								/* translators: 1: number of comments, 2: post title */
								esc_html( _nx(
									'%1$s thought on &ldquo;%2$s&rdquo;',
									'%1$s thoughts on &ldquo;%2$s&rdquo;',
									$comments_number,
									'comments title',
									'bgtfw'
								) ),
								esc_html( number_format_i18n( $comments_number ) ),
								'<span>' . esc_html( get_the_title() ) . '</span>'
							);
						}
					?>
				</h2>
			</header>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<div id="comment-nav-above" class="comment-navigation" role="navigation">
				<?php
					$paginate = new BoldGrid_Framework_Pagination();
					$paginate->comments();
				?>
			</div><!-- #comment-nav-above -->
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

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<div id="comment-nav-below" class="comment-navigation">
				<?php
					$paginate = new BoldGrid_Framework_Pagination();
					$paginate->comments();
				?>
			</div><!-- #comment-nav-below -->
			<?php endif; // check for comment navigation ?>

		<?php endif; // have_comments() ?>

		<?php
			// If comments are closed and there are comments.
			if ( ! comments_open()
					&& '0' != get_comments_number()
					&& post_type_supports( get_post_type(), 'comments' ) ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'bgtfw' ); ?></p>
		<?php endif; ?>

		<?php comment_form( apply_filters( 'comment_form_defaults', $args = array(
			'id_form'           => 'commentform',
			'id_submit'         => 'commentsubmit',
			'title_reply'       => __( 'Leave a Reply', 'bgtfw' ),
			/* translators: %s: the author of the comment being replied to */
			'title_reply_to'    => __( 'Leave a Reply to %s', 'bgtfw' ),
			'cancel_reply_link' => __( 'Cancel Reply', 'bgtfw' ),
			'label_submit'      => __( 'Post Comment', 'bgtfw' ),
			'class_submit' => 'button-primary',
			'comment_field' => '<p><textarea placeholder="Start typing..." id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'comment_notes_after' => '<p class="form-allowed-tags">' .
			__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:', 'bgtfw' ) .
			'</p><div class="alert alert-info">' . allowed_tags() . '</div>',
		) ) );
	}

	/**
	 * Get bootstrap formatted comment
	 *
	 * This is the BoldGrid Bootstrap template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments(  ) for displaying the comments.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_bootstrap_comment( $comment, $args, $depth ) {
		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'media' ); ?>>
			<div class="comment-body">
				<?php esc_html_e( 'Pingback:', 'bgtfw' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'bgtfw' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
		<?php else : ?>
		<li id="comment-<?php comment_ID(); ?>"
			<?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body media">
				<a class="pull-left" href="#">
					<?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
				</a>
				<div class="media-body">
					<div class="media-body-wrap panel panel-default">
						<div class="panel-heading">
							<div class="media-heading">
							<?php
								printf(
									'<cite class="fn">%1$s</cite> <span class="says">%2$s:</span>',
									get_comment_author_link(),
									/* translators: this displays as $author says: */
									esc_html__( 'says', 'bgtfw' )
								);
							?>
							</div>
							<div class="comment-meta">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
									<time datetime="<?php esc_attr( comment_time( 'c' ) ); ?>">
									<?php
										/* translators: 1: date of comment, 2: time of comment */
										printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'bgtfw' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) );
									?>
									</time>
								</a>
								<?php edit_comment_link( __( '<span style="margin-left: 5px;" class="fa fa-edit"></span> Edit', 'bgtfw' ), '<span class="edit-link">', '</span>' ); ?>
							</div>
						</div>
						<?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'bgtfw' ); ?></p>
						<?php endif; ?>
						<div class="comment-content panel-body">
							<?php comment_text(); ?>
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
}
