<?php
  //*** include mainfile ***//
  include("/home/fcaarberg/public_html/mainfile.php");
  
  function thisYear() {
    return strftime("%Y", time());
  }

  init();
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
  $db_name = mysql_select_db("fcaarberg", $db);
  $result = mysql_query(" SELECT    *
                          FROM      gruempel_infos
                          WHERE     jahr=".thisYear()."
                          LIMIT     1;")
    or die ("Invalid query");
  $row = mysql_fetch_array($result);

  openDocument();
  fcaHead();
  openBody(__FILE__);
  // Main content (middle) ...
  startContent();
?>
  <P align=center>
    <a href="http://gruempel.fcaarberg.ch/index.php"><img src="doc/2012_flyer.jpg"/></a>
  </P>
<?php
  endContent();
  // Left Block content ...
  startLeftBlock();
?>

<h2 id="h_verein">Gr&uuml;mpelturnier</h2>
<ul>
<li><a href="index.php">Informationen</a></li>
<li><a href="flyer.php">Flyer</a></li>
<li><a href="index.php?action=ok">Organisation</a></li>
<!-- 
<li><a href="index.php?action=anmeldung">Anmeldung</a></li>
<li><a href="einzahlung.php">Einzahlung</a></li>
 -->
<li>Turnierinformationen<br>
  <ul>
    <!-- <li><a href="/doc/2010-07-30_gruempu-gruppeneinteilung.pdf">Gruppeneinteilung</a></li> -->
    <!-- <li><a href="/doc/2010-07-30_gruempu-turnierreglement.pdf">Turnierreglement</a></li> -->
    <!-- <li><a href="/doc/2010-07-30_gruempu-modusbeilage.pdf">Modusbeilage</a></li> -->
    <!-- <li><a href="/doc/2013_gruempu-spielplan.pdf">Gruppeneinteilung / Spielplan, Modusbeilage</a></li> -->
  </ul>
</li>
</ul>

<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>  