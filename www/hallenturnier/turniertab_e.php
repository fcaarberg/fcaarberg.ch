<?php
  include("/home/fcaarberg/public_html/mainfile.php");
  include("/home/fcaarberg/public_html/hallenturnier/menu.php");

  init();

  openDocument();
  fcaHead();
  openBody(__FILE__);
  // Main content (middle) ...
  startContent();
?>

<h1>Turniertabelle E-Junioren</h1>
<ol type="1">
    <li>SV Lyss a</li>
    <li>FC Walperswil</li>
    <li><b>FC Aarberg a</b></li>
    <li>FC Ins b</li>
    <li>FC Grünstern (12 Punkte , +8 Tore, Viertelfinal-Out)</li>
    <li>FC Schönbühl b (9 Punkte , +3 Tore, Viertelfinal-Out)</li>
    <li>FC Biel (9 Punkte , +1 Tor, Viertelfinal-Out)</li>
    <li>FC Schönbühl a (7 Punkte , +1 Tor, Viertelfinal-Out)</li>
    <li>FC Bethlehem (6 Punkte, +1&nbsp;Tore, Vorrunden-Out)</li>
    <li>SC Jegenstorf (6 Punkte, +0&nbsp;Tore, Vorrunden-Out)</li>
    <li>FC Breitenrain (4 Punkte, +0&nbsp;Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg b (4 Punkte, +0&nbsp;Tore, Vorrunden-Out)</b></li>
    <li>FC Goldstern (4 Punkte, -2&nbsp;Tore, Vorrunden-Out)</li>
    <li>SV Lyss b (3 Punkte, -3&nbsp;Tore, Vorrunden-Out)</li>
    <li>FC Aurore (3 Punkte, -4&nbsp;Tore, Vorrunden-Out)</li>
    <li>SC Bümpliz (3 Punkte, -6&nbsp;Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg c (3 Punkte, -11&nbsp;Tore, Vorrunden-Out)</b></li>
    <li>FC Ins a (1 Punkt, -6&nbsp;Tore, Vorrunden-Out)</li>
    <li>SC Wohlensee (0 Punkte, -12&nbsp;Tore, Vorrunden-Out)</li>
    <li>SC Burgdorf (0 Punkte, -19&nbsp;Tore, Vorrunden-Out)</li>
</ol>

<?php
  endContent();
  startLeftBlock();
  menu();
  endRightBlock();
  closeBody();
  closeDocument();
?>