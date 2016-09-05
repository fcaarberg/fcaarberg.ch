<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">

<head>
<title>FCA mobile</title>

<meta name="verify-v1" content="pdYcvSgNtIxNnTtS1ejAwFRCKdKQlxsTXwcVGTK4hQw=" />

<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Style-Type" content="text/css" />

<meta name="description" content="FC Aarberg Homepage, fcaarberg.ch" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="robots" content="all" />

<link rel="shortcut icon" href="http://www.fcaarberg.ch/images/fca_small.ico" />
<link rel="apple-touch-icon" href="http://www.fcaarberg.ch/images/logo.png" />

<link rel="stylesheet" media="screen" type="text/css" title="Mobile: FCA Farben" 
      href="http://www.fcaarberg.ch/style/iphone.css" />
</head>
<!-- ********************************************************************** -->
<body>
  <a id="top"/>
  <div class="logo">
    <a href="http://www.fcaarberg.ch">
      <img src="http://www.fcaarberg.ch/images/logo_mobile.png" alt="FCA mobile" width="300" height="70"/>
    </a>
  </div>

  <div class="align-center">
    <a href="" class="news-indexpage-title-text">
      FCA Mobile
    </a>
  </div>
  <div class="vert-space"><img src="images/dummyw.gif" alt="" width="1" height="4"/></div>

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
    $text = "<ul>";
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
    else
    {
      echo "Keine Matchballspender in den n&auml;chsten 8 Tagen ...";
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
      echo "<tr class=\"first\"><td colspan=\"2\">Keine aktuellen Resultate in der letzten Woche ...</td></tr>";
    }

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
      echo "<tr class=\"first\"><td colspan=\"2\">Keine Heimspiele in den n&auml;chsten 8 Tagen ...</td></tr>";
    }

    echo "</table>";
    return $i;
  }

  function last_news($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query(" SELECT    datum,titel,inhalt,kategorie,autor
                            FROM      News
                            WHERE     mannschaft=0 AND titelseite='J'
                                  AND datum BETWEEN '".getMySQLDate(today(-30))."' AND '".getMySQLDate(today())."'
                            ORDER BY  datum DESC
                            LIMIT     0,1;")
      or report_mysql_error(__FILE__, __LINE__);
    $trim = 0;

    echo "<table cellspacing=\"0\" cellpadding=\"0\">";

    if ($row = mysql_fetch_array($result))
    {
      $datum = date_MySQL2shortCH($row["datum"], TRUE);
      $titel = replaceUml($row["titel"]);
      $inhalt = replaceUml($row["inhalt"]);
      $inhalt = str_replace("\n", "<br />", $inhalt);
      if (strlen($inhalt) > 200) {
        $inhalt = substr($inhalt, 0, 200)." ... ";
        $trim = 1;
      }
      echo "<tr><td>$datum - ".$row["kategorie"]."</td></tr>";
      echo "<tr><td><b>$titel</b></td></tr>";
      echo "<tr><td>$inhalt";
      if ($trim == 1) echo "&nbsp;<a href=\"".FCA_URL."/news/\">[weiter]</a>";
      echo "</td></tr>";
    }
    else
    {
      echo "<tr><td>Keine News in den letzten 30 Tagen ...</td></tr>";
    }
    echo "</table>";
  }

  function next_events($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query(" SELECT    *
                            FROM      Anlaesse
                            WHERE     startdatum>now()
                            ORDER BY  startdatum ASC, enddatum ASC ,name DESC
                            LIMIT     0,3;")
      or die ("Invalid query");

    while($row = mysql_fetch_array($result)) {
      $date = formatDateCH($row["startdatum"]);
      if ($row["enddatum"] != "0000-00-00") $date .= " - ".formatDateCH($row["enddatum"]);

      echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td width=\"80\">$date</td></tr>";
      echo "<tr><td><b>".replaceUml($row["name"])."</b></td></tr>";
      echo "<tr><td>".replaceUml($row["beschreibung"])."</td></tr>";
      if ($row["info"] != "")
        echo "<tr><td>&raquo; ".replaceUml($row["info"])."</td></tr><tr><td>&nbsp;</td></tr>";
      echo "</table>";
    }
  }

  init();
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
?>

<!-- START CONTENT HERE -->

  <?php /*--- NEWS ---*/ ?>

  <div class="news-indexpage-title">
    <a href="http://www.fcaarberg.ch/news" class="news-indexpage-title-text">News</a>
  </div>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
        <?php last_news($db); ?>
      </td>
    </tr>
  </table>

  <?php /*--- Resultate ---*/ ?>

  <div class="news-indexpage-title">
    <a href="http://www.football.ch/fvbj/de/verein.aspx?v=1282&a=rr" class="news-indexpage-title-text">Resultate</a>
  </div>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
        <?php last_results($db); ?>
      </td>
    </tr>
  </table>

  <?php /*--- Naechste Spiele ---*/ ?>

  <div class="news-indexpage-title">
    <a href="http://www.fcaarberg.ch/spielbetrieb/index.php?action=aufgebot" class="news-indexpage-title-text">N&auml;chste Heimspiele</a>
  </div>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
        <?php next_home_matches($db); ?>
      </td>
    </tr>
  </table>

  <?php /*--- Matchbaelle ---*/ ?>

  <div class="news-indexpage-title">
    <a href="http://www.fcaarberg.ch/spielbetrieb/index.php?action=matchbaelle" class="news-indexpage-title-text">Matchballspender</a>
  </div>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
        <?php matchbaelle($db); ?>
      </td>
    </tr>
  </table>

  <?php /*--- AGENDA ---*/ ?>

  <div class="news-indexpage-title">
    <a href="http://www.fcaarberg.ch/verein/index.php?action=anlaesse" class="news-indexpage-title-text">Agenda</a>
  </div>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
        <?php next_events($db); ?>
      </td>
    </tr>
  </table>

  <?php /*--- Go Back ---*/ ?>

  <div class="news-indexpage-title">
    <a href="http://www.fcaarberg.ch" class="news-indexpage-title-text">Klassische FCA Webseite</a>
  </div>

</body>
</html>
