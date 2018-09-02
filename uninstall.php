<?php
/*
* If the uniinstall file is not called from the wordpress output
* Se o arquivo uniinstall não for chamado a partir da saída wordpress
*/
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit ();
}
	/*
	* Clearing data from the options table
	* Limpando os dados da tabela options
	*/
	delete_option( 'crrq_wp_slug_post_type_custom_language' );
?>