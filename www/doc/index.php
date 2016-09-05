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
Der  FC AARBERG f�hrt am Mittwoch, 28. April 2004 ab 16.00 Uhr auf den Sportanlagen Aarolina in Aarberg den Sponsorenlauf durch. Dieser Anlass ist f�r den FCA eine wichtige Einnahmequelle und f�r die Spieler gleichzeitig ein Konditionstest.<br />
Die Einnahmen aus dem Sponsorenlauf dienen gr�sstenteils der Nachwuchsf�rderung und helfen mit, einen langfristig gesunden Finanzhaushalt des Clubs zu gew�hrleisten.<br />
<br />
Was ist ein  Sponsorenlauf?<br />
Jeder L�ufer versucht w�hrend max. 30 Minuten auf einem abgesteckten Rundkurs m�glichst viele Runden zu laufen. Der L�ufer sucht vor dem Lauf selber seine Sponsoren, die ihm f�r seine sportliche Leistung einen selbstgew�hlten Runden-Betrag (CHF 1.- / 2.- / 3.- oder mehr) bezahlen. <br />
Sponsoren k�nnen sein: Verwandte, Bekannte, Freunde, Mitarbeiter, Firmen etc. Der Sponsorenbetrag wird nach Beendigung des Laufes berechnet und die einzelnen Betr�ge werden vom L�ufer eingezogen und in die Klubkasse bezahlt (Trainer). Der L�ufer erh�lt die Abrechnung zu gegebener Zeit vom Trainer.<br />
<br />
Wieviel wird gelaufen? <br />
Die gelaufenen Runden werden von Funktion�ren gez�hlt und in das  pers�nliche Sponsorenblatt  des L�ufers eingetragen. Die Laufzeit ist auf 30 Minuten limitiert.<br />
Eine Runde misst  300 m. Junioren - F laufen 20 Minuten. Gestartet wird in verschiedenen Altersklassen, und wir rechnen damit, dass die L�ufer zwischen 10 - 25 Runden laufen werden.<br />
<br />
Die Teilnahme ist f�r alle Aktive und Junioren des FCA  obligatorisch.  Wir hoffen, dass alle Spieler versuchen werden, m�glichst viele Sponsoren  zu suchen, und mit einer guten Laufleistung die eigene Fitness unter Beweis zu stellen! Die besten Leistungen (sportlich / finanziell) werden belohnt.<br />
<br />
Alle Sponsoren  sind herzlich eingeladen, sich den Sponsorenlauf pers�nlich anzusehen. Es w�rde uns freuen, wenn m�glichst viele Sponsoren erscheinen und mithelfen die L�ufer anzufeuern. Der Sponsorenlauf soll n�mlich auch alle Freunde des FCA zusammenbringen und ein richtiges Sportfest werden. Der FC AARBERG offeriert zudem jedem Sponsor eine  Gratis-Bratwurst. 
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
