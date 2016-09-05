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

<h1>Turniertabelle F-Junioren</h1>
<ol type="1">
    <li>FC Bethlehem</li>
    <li>FC Ostermundigen</li>
    <li>FC Bözingen</li>
    <li><b>FC Aarberg b</b></li>
    <li>SV Lyss b (12 Punkte , +8 Tore, Viertelfinal-Out)</li>
    <li>FC Grünstern (7 Punkte , +6 Tore, Viertelfinal-Out)</li>
    <li>FC Belp b (7 Punkte , +2 Tore, Viertelfinal-Out)</li>
    <li>FC Walperswil (6 Punkte, +3 Tore, Viertelfinal-Out)</li>
    <li>FC Schönbühl c (6 Punkte, +3 Tore, Vorrunden-Out)</li>
    <li>FC Ins (6 Punkte, +1 Tor Vorrunden-Out)</li>
    <li><b>FC Aarberg a (6 Punkte, +0 Tore, Vorrunden-Out)</b></li>
    <li>FC Täuffelen (6 Punkte, -3 Tore, Vorrunden-Out)</li>
    <li>SV Lyss d (5 Punkte, +0 Tore, Vorrunden-Out)</li>
    <li>FC Belp a (4 Punkte, +4 Tore, Vorrunden-Out)</li>
    <li>FC Schönbühl a (4 Punkte, +0 Tore, Vorrunden-Out)</li>
    <li>FC Wyler Bern (3 Punkte, -4 Tore, Vorrunden-Out</li>
    <li>FC Länggasse (2 Punkt, -5 Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg d (1 Punkt, -7 Tore, Vorrunden-Out)</b></li>
    <li><b>FC Aarberg c (0 Punkte, -14 Tore, Vorrunden-Out)</b></li>
    <li>FC Nidau (0 Punkte, nicht angetreten!)</li>
</ol>

<?php
  endContent();
  startLeftBlock();
  menu();
  endRightBlock();
  closeBody();
  closeDocument();
?>