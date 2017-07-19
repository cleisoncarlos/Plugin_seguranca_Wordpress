<?php
/* 
Plugin Name: WP Seguro
Plugin URI: http://www.cleisoncarlos.com
Description: Plugin de segurança para wordpress
Version: 1.2
Author: Cleison Carlos
Author URI: http://www.cleisoncarlos.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// remove alguns menus da area administrativa ---------------------------
add_action( 'admin_menu', 'remove_links_menu' );
function remove_links_menu() {
//     remove_menu_page('index.php'); // Dashboard
//     remove_menu_page('edit.php'); // Posts
//     remove_menu_page('upload.php'); // Media
     remove_menu_page('link-manager.php'); // Links
//     remove_menu_page('edit.php?post_type=page'); // Pages
     remove_menu_page('edit-comments.php'); // Comments
//     remove_menu_page('themes.php'); // Appearance
 //   remove_menu_page('plugins.php'); // Plugins
//     remove_menu_page('users.php'); // Users    
	remove_menu_page('tools.php');  // Tools
	 // remove_menu_page('options-general.php'); // Settings
}
// --------------------------Customizar o Footer do WordPress -----------------------------------
    function remove_footer_admin () {
    	echo '© <a href="http://w3com.com.br/">Mentor - W3com </a> - Desenvolvimento web ';
    }
    add_filter('admin_footer_text', 'remove_footer_admin');

 // remove versão do rodapé do wordpress ---------------------------
function change_footer_version() {
  return 'Painel Administrativo';
}
add_filter( 'update_footer', 'change_footer_version', 9999 );

// remover a barra administrativa  do painel -------------------
add_filter('show_admin_bar', '__return_false');

// desativa edição do tema -------------------------------------
define('DISALLOW_FILE_EDIT', true);

// remover a aba de ajuda do wordpress -------------------------

function hide_help() {
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
          </style>';
}
add_action('admin_head', 'hide_help');

// ------------------------------ SEGURANÇA WORDPRESS-------------------------------

//Remove generator name and version from your Website pages and from the RSS feed.
function completely_remove_wp_version() {
return ''; //returns nothing, exactly the point.
}
add_filter('the_generator', 'completely_remove_wp_version');


/**
 * Remove a versão do WordPress dos parâmetros de URL
 *
 * @author Iniciativa #WordPressSeguro https://apiki.com/
 * @param string $src A URL do arquivo JavaScript/CSS a ser carregado
 * @return string Retorna a URL do arquivo sem a versão do WordPress
 */
function _remove_wp_version_from_url_param( $src )
{
 global $wp_version;
 
 $src = esc_url( $src );

 if ( !$src )
 return $src;

 $src_params = explode( '?ver=', $src );
 
 if ( !isset( $src_params[1] ) )
 return $src;

 if ( $src_params[1] !== $wp_version )	
 return $src;

 return $src_params[0];
}
add_filter( 'script_loader_src', '_remove_wp_version_from_url_param' );
add_filter( 'style_loader_src', '_remove_wp_version_from_url_param' );
//----------------REMOVE SCANNEAMENTO DE USUÁRIOS VIA ROBÔS -------------------------------------
// block WP enum scans
// https://m0n.co/enum
if (!is_admin()) {
	// default URL format
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
	add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
	// permalink URL format
	if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
	else return $redirect;
}
//-----------------------------------PREVINE ataque de força bruta -------------------------------
function shapeSpace_disable_xmlrpc_multicall($methods) {
	unset($methods['system.multicall']);
	return $methods;
}
add_filter('xmlrpc_methods', 'shapeSpace_disable_xmlrpc_multicall');
