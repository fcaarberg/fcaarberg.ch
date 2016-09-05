  <?php
  //*** include mainfile ***//
  include("/home/fcaarberg/public_html/mainfile.php");

  //*** functions ***/

  function thisYear() {
    return strftime("%Y", time());
  }

  init();
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
?>

<?php
  openDocument();
  fcaHead();
  openBody(__FILE__);
  // Main content (middle) ...
  startContent();
?>

<h1 align="center">Sponsorenlauf <?php echo thisYear(); ?></h1>
<p>
Liebe Fussballfreunde<br />
<br />
Der  FC AARBERG führt am <strong>2. Mai <?php echo thisYear(); ?></strong> ab 17.00 Uhr auf den Sportanlagen Aarolina in Aarberg den Sponsorenlauf durch. Dieser Anlass ist für den FCA eine wichtige Einnahmequelle und für die Spieler gleichzeitig ein Konditionstest.<br />
Die Einnahmen aus dem Sponsorenlauf dienen grösstenteils der Nachwuchsförderung und helfen mit, einen langfristig gesunden Finanzhaushalt des Clubs zu gewährleisten.<br />
<br />
<strong>Was ist ein  Sponsorenlauf?</strong><br />
Jeder Läufer versucht während max. 30 Minuten auf einem abgesteckten Rundkurs möglichst viele Runden zu laufen. Der Läufer sucht vor dem Lauf selber seine Sponsoren, die ihm für seine sportliche Leistung einen selbstgewählten Runden-Betrag (CHF 1.- / 2.- / 3.- oder mehr) bezahlen. <br />
Sponsoren können sein: Verwandte, Bekannte, Freunde, Mitarbeiter, Firmen etc. Der Sponsorenbetrag wird nach Beendigung des Laufes berechnet und die einzelnen Beträge werden vom Läufer eingezogen und in die Klubkasse bezahlt (Trainer). Der Läufer erhält die Abrechnung zu gegebener Zeit vom Trainer.<br />
<br />
<strong>Wieviel wird gelaufen?</strong> <br />
Die gelaufenen Runden werden von Funktionären gezählt und in das  persönliche Sponsorenblatt  des Läufers eingetragen. Die Laufzeit ist auf 30 Minuten limitiert.<br />
Eine Runde misst  300 m. Die Junioren - F und G laufen 20 Minuten. Gestartet wird in verschiedenen Altersklassen, und wir rechnen damit, dass die Läufer zwischen 10 - 25 Runden laufen werden.<br />
<br />
<strong>Die Teilnahme ist für alle Aktive und Junioren des FCA  <u>obligatorisch</u>.</strong>  Wir hoffen, dass alle Spieler versuchen werden, möglichst viele Sponsoren  zu suchen, und mit einer guten Laufleistung die eigene Fitness unter Beweis zu stellen! Die besten Leistungen (sportlich / finanziell) werden belohnt.<br />
<br />
Alle Sponsoren  sind herzlich eingeladen, sich den Sponsorenlauf persönlich anzusehen. Es würde uns freuen, wenn möglichst viele Sponsoren erscheinen und mithelfen die Läufer anzufeuern. Der Sponsorenlauf soll nämlich auch alle Freunde des FCA zusammenbringen und ein richtiges Sportfest werden. Der FC AARBERG offeriert zudem jedem Sponsor eine  Gratis-Bratwurst. 
</p>
<?php
  endContent();
  // Left Block content ...
  startLeftBlock();
?>

<h2 id="h_verein">Sponsorenlauf</h2>
<ul>
<li><a href="http://www.fcaarberg.ch/doc/sponsorenlauf_infos.pdf" target="_blank">
  <b>Sponsoren Infos</b></a>
</li>
<li><a href="http://www.fcaarberg.ch/doc/sponsorenlauf_liste.pdf" target="_blank">
  <b>Sponsoren Liste</b></a>
</li>
</ul>

<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>
