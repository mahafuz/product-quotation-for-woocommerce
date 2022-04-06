<?php
/**
 * This class contains utilities methods for managing entries table.
 *
 * @since 1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for Entries Table.
 *
 * @package PQFW
 * @since   1.0.0
 */
class Table {

	/**
	 * Various information needed for displaying the pagination.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $pagination_args = [];

	/**
	 * Retrives entries per page count.
	 *
	 * @return int | entries_per_page
	 * @since 1.0.0
	 * @todo get data from options page in the future.
	 */
	public function get_per_page() {
		return 10;
	}

	/**
	 * Retrives entries offset for the next query.
	 *
	 * @return int | offset
	 * @since 1.0.0
	 */
	public function get_offset() {
		$paged = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;

		return absint( ( $this->get_per_page() * $paged ) - $this->get_per_page() );
	}

	/**
	 * Displays the list of views available on this table.
	 *
	 * @since 1.0.0
	 */
	protected function views() {
		$before_list = '<div><div><ul class="subsubsub">';
		$after_list  = '</ul></div></div>';
		$sub_links   = [];

		$sub_links[] = sprintf(
			'<li class="all"><a href="edit.php?post_type=pqfw_quotations&page=pqfw-entries-page" %s>%s <span class="count">(%s)</span></a> | </li>',
			pqfw()->utils->get_status( $_REQUEST ) === 'publish' ? ' class="current"' : '',
			esc_html__( 'All', 'pqfw' ),
			esc_attr( pqfw()->utils->count_entries() )
		);

		$sub_links[] = sprintf(
			'<li class="trash"><a href="%s" %s>%s <span class="count">(%s)</span></a></li>',
			pqfw()->get_url_with_nonce( 'edit.php?post_type=pqfw_quotations&page=pqfw-entries-page&pqfw-entries=trash' ),
			pqfw()->utils->get_status( $_REQUEST ) === 'trash' ? ' class="current"' : '',
			esc_html__( 'Trash', 'pqfw' ),
			esc_attr( pqfw()->utils->count_entries( 'trash' ) )
		);

		echo $before_list . join( "\n", $sub_links ) . $after_list;
	}

	/**
	 * Displays the bulk actions dropdown.
	 *
	 * @since 1.0.0
	 * @param string $which The location of the bulk actions: 'top' or 'bottom'.
	 */
	protected function bulk_actions( $which ) {
		echo '<div class="alignleft actions bulkactions">';
		echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . __( 'Select bulk action' ) . '</label>';
		echo '<select name="action" id="bulk-action-selector-' . esc_attr( $which ) . "\">\n";
		echo '<option value="-1">' . esc_html__( 'Bulk actions', 'pqfw' ) . "</option>\n";

		$actions = [
			'delete' => __( 'Delete Entries', 'pqfw' )
		];

		if ( 'trash' === pqfw()->utils->get_status( $_REQUEST ) ) {
			$actions['restore'] = __( 'Restore', 'pqfw' );
		}

		foreach ( $actions as $name => $title ) {
			echo "\t" . '<option value="' . esc_attr( $name ) . '">' . esc_attr( $title ) . "</option>\n";
		}

		echo "</select>\n";

		echo '<input type="hidden" name="_wpnonce" value="' . esc_attr( pqfw()->get_url_with_nonce() ) . '" />';

		submit_button( __( 'Apply' ), 'action', '', false );
		echo '</div>';
		echo "\n";
	}

	/**
	 * Gets the current page number.
	 *
	 * @since  1.0.0
	 * @return int
	 */
	protected function get_pagenum() {
		$pagenum = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 0;

		if ( isset( $this->pagination_args['total_pages'] ) && $pagenum > $this->pagination_args['total_pages'] ) {
			$pagenum = $this->pagination_args['total_pages'];
		}

		return max( 1, $pagenum );
	}

	/**
	 * An internal method that sets all the necessary pagination arguments
	 *
	 * @since 1.0.0
	 */
	protected function set_pagination_args() {

		$args = [
			'total_items' => pqfw()->utils->count_entries(),
			'total_pages' => 0,
			'per_page'    => $this->get_per_page(),
		];

		if ( pqfw()->utils->get_status( $_REQUEST ) === 'trash' ) {
			$args['total_items'] = pqfw()->utils->count_entries( 'trash' );
		}

		if ( ! $args['total_pages'] && $args['per_page'] > 0 ) {
			$args['total_pages'] = ceil( $args['total_items'] / $args['per_page'] );
		}

		// Redirect if page number is invalid and headers are not already sent.
		if ( ! headers_sent() && ! wp_doing_ajax() && $args['total_pages'] > 0 && $this->get_pagenum() > $args['total_pages'] ) {
			wp_redirect( add_query_arg( 'paged', $args['total_pages'] ) );
			exit;
		}

		$this->pagination_args = $args;
	}

	/**
	 * Displays the pagination.
	 *
	 * @since  1.0.0
	 * @param  string $which Which position to show the pagination.
	 * @return void
	 */
	protected function pagination( $which ) {
		if ( empty( $this->pagination_args ) ) {
			return;
		}

		$total_items = $this->pagination_args['total_items'];
		$total_pages = $this->pagination_args['total_pages'];

		$output = '<span class="displaying-num">' . sprintf(
			/* translators: %s: Number of items. */
			_n( '%s item', '%s items', $total_items ),
			number_format_i18n( $total_items )
		) . '</span>';

		$current              = $this->get_pagenum();
		$removable_query_args = wp_removable_query_args();

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		$current_url = remove_query_arg( $removable_query_args, $current_url );


		$page_links = [];

		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';

		$disable_first = false;
		$disable_last  = false;
		$disable_next  = false;
		$disable_prev  = false;

		if ( 1 === $current ) {
			$disable_first = true;
			$disable_prev  = true;
		}

		if ( 2 === $current ) {
			$disable_first = true;
		}

		if ( $total_pages === $current ) {
			$disable_last = true;
			$disable_next = true;
		}

		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='first-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				__( 'First page' ),
				'&laquo;'
			);
		}

		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='prev-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
				__( 'Previous page' ),
				'&lsaquo;'
			);
		}

		if ( 'bottom' === $which ) {
			$html_current_page  = $current;
			$total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
		} else {
			$html_current_page = sprintf(
				"%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
				'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
				$current,
				strlen( $total_pages )
			);
		}
		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
		$page_links[]     = $total_pages_before . sprintf(
			/* translators: %1$s: pagination number */
			_x( '%1$s of %2$s', 'paging' ),
			$html_current_page,
			$html_total_pages
		) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
				__( 'Next page' ),
				'&rsaquo;'
			);
		}

		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='last-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
				__( 'Last page' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class .= ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}

		echo "<div class='tablenav-pages{$page_class}'>$output</div>";
	}

	/**
	 * Displays the pagination and stuffs.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which Which position to show the pagination.
	 * @return void
	 */
	public function display( $which ) {
		if ( 'top' === $which ) {
			$this->views();
		}

		echo '<div class="tablenav ' . esc_attr( $which ) . '">';
		$this->bulk_actions( $which );
		$this->set_pagination_args();
		$this->pagination( $which );
		echo '</div>';
	}
}