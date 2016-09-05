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
    <li>FC Nidau</li>
    <li>FC Walperswil</li>
    <li>SV Jegenstorf</li>
    <li><b>FC Aarberg d</b></li>
    <li>FC Biel a (12 Punkte , +17 Tore, Viertelfinal-Out)</li>
    <li>SC Worb (12 Punkte, +7 Tore, Viertelfinal-Out)</li>
    <li>FC Schönbühl (7 Punkte, +4 Tore, Viertelfinal-Out)</li>
    <li><b>FC Aarberg f (7 Punkte, +2 Tore, Viertelfinal-Out)</b></li>
    <li><b>FC Aarberg a (7 Punkte, +8 Tore, Vorrunden-Out)</b></li>
    <li><b>FC Aarberg e (6 Punkte, +0&nbsp;Tore, Vorrunden-Out)</b></li>
    <li>FC Belp b (6 Punkte, -2&nbsp;Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg c (4 Punkte, -3&nbsp;Tore, Vorrunden-Out)</b></li>
    <li>FC Ins a (4 Punkte, -7&nbsp;Tore, Vorrunden-Out)</li>
    <li>FC Ins b (3 Punkte, -1&nbsp;Tor, Vorrunden-Out)</li>
    <li>FC Bethlehem (3 Punkte, -2&nbsp;Tore, Vorrunden-Out)</li>
    <li>FC Belp a (3 Punkte, -4&nbsp;Tore, Vorrunden-Out)</li>
    <li>SV Lyss a (3 Punkte, -9&nbsp;Tore, Vorrunden-Out)</li>
    <li><b>FC Aarberg b (2 Punkte, -5&nbsp;Tore, Vorrunden-Out)</b></li>
    <li>FC Biel b (1 Punkt, -6&nbsp;Tore, Vorrunden-Out)</li>
    <li>SV Lyss b (0 Punkte, -26&nbsp;Tore, Vorrunden-Out)</li>
</ol>

<?php
  endContent();
  startLeftBlock();
  menu();
  endRightBlock();
  closeBody();
  closeDocument();
?>