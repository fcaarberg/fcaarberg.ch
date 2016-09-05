<?PHP
  $logout = true;
  $cfgProgDir = 'phpSecurePages/';
  include($cfgProgDir."secure.php");

  include("admin_mainfile.php");
 
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>
    <h1>Auf Wiedersehen!</h1>
    <p align="center">
    Sie haben Sich soeben erfolgreich abgemeldet, falls Sie weitere administrative Arbeiten
    erledigen müssen, oder Sich als neuer Benutzer anmelden wollen - klicken Sie <a href="admin.php">HIER</a>.
    </p>

<?php
  endContent();
  
  // Left Block content ...
  startLeftBlock();

  // adminSecureInformation($login, $userLevel, $ID);
  adminNavigation();
  
  endRightBlock();
  closeBody();
  closeDocument();
?>  