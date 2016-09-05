<?php

if (! isset($wp_did_header)):
if ( !file_exists( dirname(__FILE__) . '/wp-config.php') ) {
	if (strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) $path = '';
	else $path = 'wp-admin/';

	require_once( dirname(__FILE__) . '/wp-includes/classes.php');
	require_once( dirname(__FILE__) . '/wp-includes/functions.php');
	require_once( dirname(__FILE__) . '/wp-includes/plugin.php');
	wp_die("Die Datei <code>wp-config.php</code> scheint nicht zu existieren. Sie wird aber ben&ouml;tigt, bevor wir mit der Installation beginnen k&ouml;nnen. Brauchst Du weitere Hilfe? Bei <a href='http://wordpress-deutschland.org/'>WordPress Deutschland</a> findest Du eine <a href='http://wordpress-deutschland.org/installation'>deutschsprachige Anleitung</a>. Eine <a href='http://codex.wordpress.org/Editing_wp-config.php'>englischsprachige Anleitung</a> findest Du bei <a href='http://wordpress.org/'>WordPress.org</a>. Du kannst die Datei <code>wp-config.php</code> auch online erstellen, das funktioniert jedoch nicht mit allen Servern. Die sicherste Methode ist es, die Datei manuell zu erstellen.</p><p><a href='{$path}setup-config.php' class='button'>Konfigurationsdatei erstellen</a>", "WordPress &rsaquo; Fehler");
}

$wp_did_header = true;

require_once( dirname(__FILE__) . '/wp-config.php');

wp();

require_once(ABSPATH . WPINC . '/template-loader.php');

endif;

?>
