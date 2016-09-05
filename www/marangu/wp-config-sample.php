<?php
// ** MySQL Einstellungen ** //
define('DB_NAME', 'putyourdbnamehere');    // Der Name der Datenbank, die du benutzt.
define('DB_USER', 'usernamehere');     // Dein MySQL-Datenbank-Benutzername.
define('DB_PASSWORD', 'yourpasswordhere'); // Dein MySQL-Passwort.
define('DB_HOST', 'localhost');    // 99% Chance, dass du hier nichts ändern musst.
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');


// Ändere den SECRET_KEY in eine beliebiege, möglichst einzigartige Phrase. Du brauchst dich später
// nicht mehr daran erinnern, also mache ihn am besten möglichst lang und kompliziert.
// Auf der Seite https://www.grc.com/passwords.htm kannst du dir einen Ausdruck generieren lassen.
define('SECRET_KEY', 'put your unique phrase here'); // Trage hier eine beliebige, möglichst zufällige Phrase ein.


// Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
// verschiedene WordPress-Installationen betreiben.
$table_prefix  = 'wp_';   // Nur Zahlen, Buchstaben und Unterstriche bitte!


// Hier kannst du einstellen, welche Sprachdatei benutzt werden soll. Die entsprechende
// Sprachdatei mu§ im Ordner wp-content/languages vorhanden sein, beispielsweise de_DE.mo
// Wenn du nichts einträgst, wird Englisch genommen.
define ('WPLANG', 'de_DE');


/* Das war`s schon, ab hier bitte nichts mehr editieren! Viel Spaß beim bloggen. */
define('ABSPATH', dirname(__FILE__).'/');
require_once(ABSPATH.'wp-settings.php');
?>
