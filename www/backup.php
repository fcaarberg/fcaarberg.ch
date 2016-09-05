<?php
  include("/home/fcaarberg/public_html/mainfile.php");
  
  function backup($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("LOCK TABLES Kontakte READ;")
      or report_mysql_error(__FILE__, __LINE__);
    $result = mysql_query("BACKUP TABLE Kontakte TO '/home/fcaarberg/public_html/backup';")
      or report_mysql_error(__FILE__, __LINE__);
    $result = mysql_query("UNLOCK TABLES;")
      or report_mysql_error(__FILE__, __LINE__);
    echo "<h2>Backupvorgang beendet!</h2>";
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
  backup($db);
  endContent();
  // Left Block content ...
  startLeftBlock();
  endRightBlock();
  closeBody();
  closeDocument();
?>  