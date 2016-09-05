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
Der  FC AARBERG führt am Mittwoch, 28. April 2004 ab 16.00 Uhr auf den Sportanlagen Aarolina in Aarberg den Sponsorenlauf durch. Dieser Anlass ist für den FCA eine wichtige Einnahmequelle und für die Spieler gleichzeitig ein Konditionstest.<br />
Die Einnahmen aus dem Sponsorenlauf dienen grösstenteils der Nachwuchsförderung und helfen mit, einen langfristig gesunden Finanzhaushalt des Clubs zu gewährleisten.<br />
<br />
Was ist ein  Sponsorenlauf?<br />
Jeder Läufer versucht während max. 30 Minuten auf einem abgesteckten Rundkurs möglichst viele Runden zu laufen. Der Läufer sucht vor dem Lauf selber seine Sponsoren, die ihm für seine sportliche Leistung einen selbstgewählten Runden-Betrag (CHF 1.- / 2.- / 3.- oder mehr) bezahlen. <br />
Sponsoren können sein: Verwandte, Bekannte, Freunde, Mitarbeiter, Firmen etc. Der Sponsorenbetrag wird nach Beendigung des Laufes berechnet und die einzelnen Beträge werden vom Läufer eingezogen und in die Klubkasse bezahlt (Trainer). Der Läufer erhält die Abrechnung zu gegebener Zeit vom Trainer.<br />
<br />
Wieviel wird gelaufen? <br />
Die gelaufenen Runden werden von Funktionären gezählt und in das  persönliche Sponsorenblatt  des Läufers eingetragen. Die Laufzeit ist auf 30 Minuten limitiert.<br />
Eine Runde misst  300 m. Junioren - F laufen 20 Minuten. Gestartet wird in verschiedenen Altersklassen, und wir rechnen damit, dass die Läufer zwischen 10 - 25 Runden laufen werden.<br />
<br />
Die Teilnahme ist für alle Aktive und Junioren des FCA  obligatorisch.  Wir hoffen, dass alle Spieler versuchen werden, möglichst viele Sponsoren  zu suchen, und mit einer guten Laufleistung die eigene Fitness unter Beweis zu stellen! Die besten Leistungen (sportlich / finanziell) werden belohnt.<br />
<br />
Alle Sponsoren  sind herzlich eingeladen, sich den Sponsorenlauf persönlich anzusehen. Es würde uns freuen, wenn möglichst viele Sponsoren erscheinen und mithelfen die Läufer anzufeuern. Der Sponsorenlauf soll nämlich auch alle Freunde des FCA zusammenbringen und ein richtiges Sportfest werden. Der FC AARBERG offeriert zudem jedem Sponsor eine  Gratis-Bratwurst. 
</p>

<?php
  /*if ($action == "anmelden") addAnmeldung($db, $fields);

  if ($action == "anmeldung") anmeldung($db);
  else if ($action == "ok") organisation($db);
  else info($db);
*/
  endContent();
  // Left Block content ...
  startLeftBlock();
?>

<h2 id="h_verein">Sponsorenlauf</h2>
<ul>
<li><a href="/doc/sponsorenlauf_04_liste.pdf" target="_blank">
  <b>Sponsoren Liste</b></a>
</li>
</ul>

<h2 id="h_einteilung">Programm</h2>
<ul>
<li><a href="sponsorenlauf_04_zeiten.pdf"><b>Laufzeiten</b></a></li>
</ul>

<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>
