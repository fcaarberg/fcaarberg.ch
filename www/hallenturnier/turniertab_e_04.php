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
    <li><b>FC Aarberg b</b></li>
    <li>FC Bözingen</li>
    <li>FC Langnau</li>
    <li>SV Lyss a</li>
    <li>FC Schönbühl (12 Punkte , +17 Tore, Viertelfinal-Out)</li>
    <li>FC Walperswil (9 Punkte , +3 Tore, Viertelfinal-Out)</li>
    <li><b>FC Aarberg e (9 Punkte , +1 Tor, Viertelfinal-Out)</b></li>
    <li>SC Wohlensee b (6 Punkte , -2 Tore, Viertelfinal-Out)</li>
    <li>SC Radelfingen (6 Punkte, +10 Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg a (6 Punkte, +3 Tore, Vorrunden-Out)</b></li>
    <li>SC Jegenstorf (6 Punkte, +2 Tore, Vorrunden-Out)</li>
    <li>SC Wohlensee a (4 Punkte, +1 Tor, Vorrunden-Out)</li>
    <li><b>FC Aarberg c (4 Punkte, -2 Tore, Vorrunden-Out)</b></li>
    <li>FC Bözingen a (4 Punkte, -10 Tore, Vorrunden-Out)</li>
    <li>FC Bethlehem (3 Punkte, -3 Tore, Vorrunden-Out)</li>
    <li>SV Lyss b (3 Punkte, -11 Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg d (1 Punkt, -7 Tore, Vorrunden-Out)</b></li>
    <li>FC Nidau (0 Punkte, -8 Tore, Vorrunden-Out)</li>
    <li>FC Madretsch (0 Punkte, -12 Tore, Vorrunden-Out)</li>
    <li>FC Täuffelen (0 Punkte, -16 Tore, Vorrunden-Out)</li>
</ol>

<?php
  endContent();
  startLeftBlock();
  menu();
  endRightBlock();
  closeBody();
  closeDocument();
?>