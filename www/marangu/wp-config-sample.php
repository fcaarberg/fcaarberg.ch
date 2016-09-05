<?php
// ** MySQL Einstellungen ** //
define('DB_NAME', 'putyourdbnamehere');    // Der Name der Datenbank, die du benutzt.
define('DB_USER', 'usernamehere');     // Dein MySQL-Datenbank-Benutzername.
define('DB_PASSWORD', 'yourpasswordhere'); // Dein MySQL-Passwort.
define('DB_HOST', 'localhost');    // 99% Chance, dass du hier nichts �ndern musst.
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');


// �ndere den SECRET_KEY in eine beliebiege, m�glichst einzigartige Phrase. Du brauchst dich sp�ter
// nicht mehr daran erinnern, also mache ihn am besten m�glichst lang und kompliziert.
// Auf der Seite https://www.grc.com/passwords.htm kannst du dir einen Ausdruck generieren lassen.
define('SECRET_KEY', 'put your unique phrase here'); // Trage hier eine beliebige, m�glichst zuf�llige Phrase ein.


// Wenn du verschiedene Pr�fixe benutzt, kannst du innerhalb einer Datenbank
// verschiedene WordPress-Installationen betreiben.
$table_prefix  = 'wp_';   // Nur Zahlen, Buchstaben und Unterstriche bitte!


// Hier kannst du einstellen, welche Sprachdatei benutzt werden soll. Die entsprechende
// Sprachdatei mu� im Ordner wp-content/languages vorhanden sein, beispielsweise de_DE.mo
// Wenn du nichts eintr�gst, wird Englisch genommen.
define ('WPLANG', 'de_DE');


/* Das war`s schon, ab hier bitte nichts mehr editieren! Viel Spa� beim bloggen. */
define('ABSPATH', dirname(__FILE__).'/');
require_once(ABSPATH.'wp-settings.php');
?>
