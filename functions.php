<?php

if ( ! function_exists( 'pqfwGetPreLoader' ) ) {
	function pqfwGetPreLoader() {
		ob_start();
		?>
			<div class="pqfw-initial-preloader"><?php esc_html_e( 'Loading...', 'pqfw' ); ?></div>
		<?php
		return ob_get_clean();
	}
}