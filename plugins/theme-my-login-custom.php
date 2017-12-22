<?php 

	function tml_user_register( $user_id ) {
		if ( !empty( $_POST['client'] ) )
			update_user_meta( $user_id, 'cdgd_client', $_POST['client'] );
	}

	add_action( 'user_register', 'tml_user_register' );

?>