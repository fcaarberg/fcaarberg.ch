<?php
  include("/home/fcaarberg/public_html/mainfile.php");
  
  function matchbaelle($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT DISTINCT *
                          FROM Matchballspender, Spiele
                          WHERE Spiele.id = Matchballspender.spiel
                            AND Spiele.datum BETWEEN '".getMySQLDate(today())."' AND '".getMySQLDate(today(8))."'
                          ORDER BY Spiele.datum,Spiele.anspielzeit ASC;")
      or report_mysql_error(__FILE__, __LINE__);
    $text = "<h2 id=\"t_matchbaelle\">Matchballspender</h2><ul>";
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      if ($row[url] == "")
        $text .= "<li><strong>".replaceUml($row[inserat])."</strong></li>";
      else
        $text .= "<li><strong><a href=\"http://$row[url]\" target=\"_blank\">".replaceUml($row[inserat])."</a></strong></li>";
      $i++;
    }
    $text .= "</ul>";
    if ($i != 0) {
      echo $text;
    }
    return $i;
  }
  
  function show_spielberichte($database, $id, $saison = 0) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query2 = "SELECT DISTINCT * FROM Spielberichte,Spiele WHERE Spielberichte.spiel = Spiele.id ;";
    $result = mysql_query ($query2)
      or report_mysql_error(__FILE__,__LINE__);
    $saison = "20".sprintf("%02d",$saison)."/20".sprintf("%02d",($saison+1));
    echo "<h2>Spielberichte - Saison $saison</h2>";
    echo "<div align=\"center\">";
    echo "<table>";
    while ($row = mysql_fetch_array($result)) {
      echo "<tr><td>".formatDateCH($row[datum], true)."</td><td><a href=\"$SELF_PHP?action=spielbericht&id=$row[spiel]&teamid=$id\">";
      if ($row[spielort] == "H") echo replaceUml("FC Aarberg - $row[gegner]");
      else echo replaceUml("$row[gegner] - FC Aarberg");
      echo "</a></td><td>$row[resultat]</td></tr>";
    }
    echo "</table>";
  }
  
  function last_results($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT Spiele.*,Mannschaften.id AS mid, Mannschaften.name
                          FROM  Mannschaften, Spiele
                          WHERE mannschaft=Mannschaften.id
                            AND resultat <> ''
                            AND datum BETWEEN '".getMySQLDate(today(-7))."' AND '".getMySQLDate(today())."'
                          ORDER BY datum DESC, Mannschaften.name ASC;")
      or report_mysql_error(__FILE__, __LINE__);
    echo "<table cellspacing=\"0\" cellpadding=\"2\">";
    $i = 0;
    $old = 0;
    while($row = mysql_fetch_array($result)) {
      $query2 = "SELECT DISTINCT * FROM Spielberichte,Spiele WHERE Spielberichte.spiel = $row[id] ;";
      $result2 = mysql_query ($query2)
        or report_mysql_error(__FILE__,__LINE__);
      
      $bericht = "";
      while ($row2 = mysql_fetch_array($result2)) 
      {
        $bericht = "spielbetrieb/mannschaft.php?action=spielbericht&id=$row2[spiel]&teamid=$row[mannschaft]";
      }
      
      $datum = date_MySQL2shortCH($row["datum"], TRUE);
      $anspielzeit = time_trimSeconds($row["anspielzeit"]);
      if ($i++ % 2 == 0) $class = "first";
      else $class = "second";
      if (strcmp($old,$datum) != 0)
      {
        if ($old) echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";
        echo "<tr><th colspan=\"2\">$datum</th></tr>";
      }
      $gegner = replaceUml($row["gegner"]);
      echo "<tr valign=\"top\">";
      echo "<td colspan=\"2\"><a href=\"/spielbetrieb/mannschaft.php?teamid=".$row["mid"]."\">".$row["name"]."</a></td></tr>";
      if ($row[spielort] == "H") $game = "FC Aarberg - $gegner";
      else $game = "$gegner - FC Aarberg";
      if (strcmp($bericht,"") != 0)
      {
        $game .= "<br/>&nbsp;&nbsp;<i><a href=\"$bericht\">mehr...</a></i>";
      }
      echo "<tr valign=\"top\"><td colspan=\"1\">$game</td><td>$row[resultat]</td></tr>";
      $old = $datum;
    }
    if ($i == 0) {
      echo "<tr class=\"second\"><td colspan=\"2\">Keine aktuellen Resultate gefunden...</td></tr>";
    }
    /*echo "<tr class=\"second\"><td colspan=\"2\"><br />
          <a href=\"".FCA_URL."/spielbetrieb/\">zum Spielbetrieb ...</a></td></tr>";*/
    echo "</table>";
    return $i;
  }
  
  function next_home_matches($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT DISTINCT Spiele.spielort,
                                          Spiele.datum,
                                          Spiele.anspielzeit,
                                          Spiele.mannschaft,
                                          Spiele.gegner,
                                          Mannschaften.id,
                                          Mannschaften.name
                          FROM Mannschaften,
                                Spiele
                          WHERE Spiele.mannschaft = Mannschaften.id
                            AND spielort = 'H'
                            AND datum BETWEEN '".getMySQLDate(today(0))."' AND '".getMySQLDate(today(8))."'
                          ORDER BY Spiele.datum,Spiele.anspielzeit ASC;")
      or report_mysql_error(__FILE__, __LINE__);
    echo "<table cellspacing=\"0\" cellpadding=\"2\">";
    $i = 0;
    $old = 0;
    while($row = mysql_fetch_array($result)) {
      $datum = date_MySQL2shortCH($row["datum"], TRUE);
      $anspielzeit = time_trimSeconds($row["anspielzeit"]);
      if ($i++ % 2 == 0) $class = "first";
      else $class = "second";
      if (strcmp($old,$datum) != 0)
      {
        if ($old) echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";
        echo "<tr><th colspan=\"2\">$datum</th></tr>";
      }
      $gegner = replaceUml($row["gegner"]);
      echo "<tr valign=\"top\"><td>".$anspielzeit."</td>";
      echo "<td><a href=\"/spielbetrieb/mannschaft.php?teamid=".$row["id"]."\">".$row["name"]."</a></td></tr>";
      echo "<tr valign=\"top\"><td colspan=\"2\">FC Aarberg - $gegner</td></tr>";
      $old = $datum;
    }
    if ($i == 0) {
      echo "<tr class=\"second\"><td colspan=\"2\">Keine weiteren Heimspiele in Sicht...</td></tr>";
    }
    /*echo "<tr class=\"second\"><td colspan=\"2\"><br />
          <a href=\"".FCA_URL."/spielbetrieb/\">zum Spielbetrieb ...</a></td></tr>";*/
    echo "</table>";
    return $i;
  }

  function last_news($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query(" SELECT    datum,titel,inhalt,kategorie,autor
                            FROM      News
                            WHERE     mannschaft=0 AND titelseite='J'
                                  AND datum BETWEEN '".getMySQLDate(today(-14))."' AND '".getMySQLDate(today())."'
                            ORDER BY  datum DESC
                            LIMIT     0,1;")
      or report_mysql_error(__FILE__, __LINE__);

    echo "<table cellspacing=\"0\" cellpadding=\"2\">";

    $row = mysql_fetch_array($result);
    $datum = date_MySQL2shortCH($row["datum"], TRUE);
      if ($i++ % 2 == 0) $class = "firstsmall";
      else $class = "secondsmall";
    $titel = replaceUml($row["titel"]);
    $inhalt = replaceUml($row["inhalt"]);
    $inhalt = str_replace("\n", "<br />", $inhalt);
    if (strlen($inhalt) > 200) $inhalt = substr($inhalt, 0, 200)." ... ";
    echo "<tr><td>$datum - ".$row["kategorie"]."</td></tr>";
    echo "<tr><th>$titel</th></tr>";
    echo "<tr><td>$inhalt
          &nbsp;<a href=\"".FCA_URL."/news/\">[weiter]</a></td></tr>";
    echo "</table>";
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
  
<h1>Fussball Club Aarberg</h1>

<div align="center">
<table>
  <tr>
    <td align="left">
      10201 SEFV<BR />
      <B>FC Aarberg</B><BR />
      Postfach 145<BR />
      3270 Aarberg<BR />
      <BR />
      PC 25-5285-0<BR />
      Tel. 032/392 20 75<BR />
      VM-Rayon 53
    </td>
    <td align="right"><img src="/images/logo.png" alt="grosses Logo" height="200"></td>
  </tr>
</table>
<table>
  <tr valign="top">
    <td align="left">
<?php
      // letzte resultate
      echo "<h2 id=\"t_results\">Aktuelle Resultate</h2>";
      last_results($db);  
?>
    </td>
    <td align="right">
<?php 
      echo "<h2 id=\"t_results\">n&auml;chste Heimspiele</h2>";
      $matches = next_home_matches($db);
?>
     </td>
  </tr>
</table>
  

</div>
<?php
  endContent();
  // Left Block content ...
  startLeftBlock();
?>
<h2 id="h_news">Informationen</h2>
      <?php last_news($db); ?>

<?php
  endLeftBlock();
  // Right Block content ...
  startRightBlock();

  // matchballspender
  matchbaelle($db);
  

  // End blocks and document
  endRightBlock();
  closeBody();
  closeDocument();
?>  

