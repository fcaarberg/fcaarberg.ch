<?php
define('WP_INSTALLING', true);
if (!file_exists('../wp-config.php'))
	die("Die Datei <code>wp-config.php</code> scheint nicht zu existieren. Sie wird aber ben&ouml;tigt, bevor wir anfangen k&ouml;nnen. Brauchst Du weitere Hilfe? Bei <a href='http://wordpress-deutschland.org/'>WordPress Deutschland</a> findest du eine <a href='http://wordpress-deutschland.org/installation'>deutschsprachige Anleitung</a>. Eine <a href='http://codex.wordpress.org/Editing_wp-config.php'>englischsprachige Anleitung</a> findest Du bei <a href='http://wordpress.org/'>WordPress.org</a>. Du kannst die Datei <code>wp-config.php</code> auch <a href='setup-config.php'>online erstellen</a>, das funktioniert jedoch nicht mit allen Servern. Die sicherste Methode ist es, die Datei manuell zu erstellen.</p><p><a href='setup-config.php' class='button'>Konfigurationsdatei erstellen</a>");

require('../wp-config.php');
timer_start();
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

if (isset($_GET['step']))
	$step = (int) $_GET['step'];
else
	$step = 0;
@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<title><?php _e('WordPress &rsaquo; Upgrade'); ?></title>
	<?php wp_admin_css( 'css/install' ); ?>
</head>
<body>
<h1 id="logo"><img alt="WordPress" src="images/wordpress-logo.png" /></h1>

<?php if ( get_option('db_version') == $wp_db_version ) : ?>

<h2><?php _e('No Upgrade Required'); ?></h2>
<p><?php _e('Your WordPress database is already up-to-date!'); ?></p>
<h2 class="step"><a href="<?php echo get_option('home'); ?>/"><?php _e('Continue'); ?></a></h2>

<?php else :
switch($step) :
	case 0:
		$goback = stripslashes(wp_get_referer());
		$goback = clean_url($goback, null, 'url');
		$goback = urlencode($goback);
?>
<h2><?php _e('Database Upgrade Required'); ?></h2>
<p><?php _e('Your WordPress database is out-of-date, and must be upgraded before you can continue.'); ?></p>
<p><?php _e('The upgrade process may take a while, so please be patient.'); ?></p>
<h2 class="step"><a href="upgrade.php?step=1&amp;backto=<?php echo $goback; ?>"><?php _e('Upgrade WordPress'); ?></a></h2>
<?php
		break;
	case 1:
		wp_upgrade();

		if ( empty( $_GET['backto'] ) )
			$backto = __get_option('home') . '/';
		else {
			$backto = stripslashes(urldecode($_GET['backto']));
			$backto = clean_url($backto, null, 'url');
		}
?>
<h2><?php _e('Upgrade Complete'); ?></h2>
	<p><?php _e('Your WordPress database has been successfully upgraded!'); ?></p>
	<h2 class="step"><a href="<?php echo $backto; ?>"><?php _e('Continue'); ?></a></h2>

<!--
<pre>
<?php printf(__('%s queries'), $wpdb->num_queries); ?>

<?php printf(__('%s seconds'), timer_stop(0)); ?>
</pre>
-->

<?php
		break;
endswitch;
endif;
?>
</body>
</html>
