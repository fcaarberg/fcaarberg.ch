<?php
  include("/home/fcaarberg/public_html/mainfile.php");
  
  init();
?>

<?php
  openDocument();
  fcaHead();
  openBody(__FILE__);
  // Main content (middle) ...
  startContent();
?>
  
<h1>Nicht gefunden (Fehler 404)</h1>

<div align="center">
Die URL <code>
<?php
  echo $REQUEST_URI;
?>
</code> konnte auf diesem Server nicht gefunden werden!
</div>

<?php
  endContent();
  // Left Block content ...
  startLeftBlock();

  endRightBlock();
  closeBody();
  closeDocument();
?>  

