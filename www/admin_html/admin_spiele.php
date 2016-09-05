<?php
  $minUserLevel = 7;
  $cfgProgDir = 'phpSecurePages/';
  
  include($cfgProgDir."secure.php");
  include("admin_mainfile.php");
  include("/home/fcaarberg/public_html/pdf_funktionen.php");
  
  function setDefaultFilter()
  {
    $filter = array("Xmannschaft","Xdatum","Xsaison","Xspielort","Xspielart","mannschaft","datumvon","datumbis","saison");
    $filter[Xmannschaft] = 0;
    $filter[Xdatum] = 0;
    $filter[Xsaison] = 1;
    $filter[Xspielort] = 0;
    $filter[Xspielart] = 0;
    $filter[datumvon] = today();
    $filter[datumbis] = today(14);
    $filter[saison] = getSaison(today());
    return $filter;
  }
  
  function get_number_of_entries($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = "SELECT count(*) FROM Spiele";
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);
    $row = $row = mysql_fetch_array($result);
    return $row["count(*)"];
  }

  function showGamePreselection($db, $filter = 0) {
    if ($filter == 0 or $filter == "") $filter = setDefaultFilter();
    echo "<form action=\"admin_spiele.php\" method=\"post\">
          <table align=\"center\">
          <tr><td>Mannschaft:</td><td>"; // TEAM - FILTER
    printCheckbox("preselect[Xmannschaft]", 1, $filter[Xmannschaft] == 1);
    echo "</td><td>";
    printTeamDropDownList($db,"preselect[mannschaft]", $filter[mannschaft]);

    echo "</td></tr><tr><td>Datum:</td><td>"; // DATUM - FILTER
    printCheckbox("preselect[Xdatum]", 1, $filter[Xdatum] == 1);
    if (0 == strcmp($filter[datumvon],"")) {
      $filter[datumvon] = today();
      $filter[datumbis] = today(14);
    }
    echo "</td><td><input type=\"text\" name=\"preselect[datumvon]\" value=".$filter[datumvon]." size=10 maxlength=10> -
                   <input type=\"text\" name=\"preselect[datumbis]\" value=".$filter[datumbis]." size=10 maxlength=10>
            </td></tr>";

    echo "<tr><td>Saison:</td><td>"; // SAISON - FILTER
    printCheckbox("preselect[Xsaison]", 1, $filter[Xsaison] == 1);
    if (0 == strcmp($filter[saison],"")) {
      $filter[saison] = sprintf("%02d",getSaison(today()));
    }
    echo "</td><td>20<input type=\"text\" name=\"preselect[saison]\" value='".$filter[saison]."' size=15 maxlength=2></td></tr>";

    echo "<tr><td>Spielort:</td><td>"; // SPIELORT - FILTER
    printCheckbox("preselect[Xspielort]", 1, $filter[Xspielort] == 1);
    echo "</td><td>";
    $spielorte = array ("H","A");
    printDropDownList($spielorte, "preselect[spielort]", $filter[spielort]);
    echo "</td></tr>";

    echo "<tr><td>Spielart:</td><td>"; // SPIELART - FILTER
    printCheckbox("preselect[Xspielart]", 1, $filter[Xspielart] == 1);
    echo "</td><td>";
    $spielarten = array ("M","C","F","T","Fi");
    printDropDownList($spielarten, "preselect[spielart]", $filter[spielart]);
    echo "</td></tr>";

    echo "<tr><td colspan=3 align=\"center\"><input type=\"submit\"></td></tr>
          <tr><td colspan=3>&nbsp;</td></tr></table></form>";
  }

  function showGameList($database, $pre = 0) {
    if ($pre == 0 or $pre == "") $pre = setDefaultFilter();
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " SELECT Mannschaften.id,Mannschaften.name,Spiele.*
                            FROM    Spiele, Mannschaften
                            WHERE   mannschaft = Mannschaften.id";
    if ($pre[Xmannschaft] != 0)
      $query .= " AND mannschaft = $pre[mannschaft]";
    if ($pre[Xspielort] != 0)
      $query .= " AND spielort = '$pre[spielort]'";
    if ($pre[Xspielart] != 0)
      $query .= " AND spielart = '$pre[spielart]'";
    if ($pre[Xsaison] != 0)
      $query .= " AND saison = '$pre[saison]'";
    if ($pre[Xdatum] != 0)
      $query .= " AND datum BETWEEN '".formatDateUS($pre["datumvon"])."' AND '".formatDateUS($pre["datumbis"])."'";

    $query .= "             ORDER BY datum,anspielzeit ASC;";
    debug_echo($query);
    $result = mysql_query($query)
      or report_error(__FILE__,__LINE__,"Invalid query: $query");
    echo "<table align=\"center\" border=0 cellpadding=5>\n<tr><td colspan=2></td>";
    echo "<th>Datum</th><th>Zeit</th><th>Spiel-Nr.</th><th>Mannschaft</th><th>Gegner</th><th>Ort</th><th>Art</th></tr>\n";
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
      $id = $row["id"];
      if ($i++ %2 == 1) $class = "first";
      else $class = "second";
      echo "<tr class=\"$class\"><td>";
      echo "<a href=\"$PHP_SELF?id=$id&action=edit\"><img alt=\"Bearbeiten\" title=\"Bearbeiten\" src=\"/images/edit.png\"></a>";
      echo "</td><td><a href=\"$PHP_SELF?id=$id&action=delete\"><img alt=\"L&ouml;schen\" title=\"L&ouml;schen\" src=\"images/delete.png\"></a></td>";
      echo "<td>".formatDateCH($row["datum"])."</td>";
      $anspielzeit = time_trimSeconds($row["anspielzeit"]);
      if ((0 == strcmp($row["spielort"],"H")) && (0 == strcmp($anspielzeit,"00:00"))) {
      	echo "<td><font color='red'>".$anspielzeit."</font></td>";
      } else {
      	echo "<td>".$anspielzeit."</td>";
      }
      echo "<td>".$row["spielnr"]."</td>";
      echo "<td>".replaceUml($row["name"])."</td>";
      echo "<td>".replaceUml($row["gegner"])."</td>";
      echo "<td>".$row["spielort"]."</td>";
      echo "<td>".$row["spielart"]."</td>";
      echo "</tr>\n";
    }
    echo "</table>\n";
  }

  function deleteGame($database, $id) {
    /*$db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT * FROM Spiele WHERE id = '$id' LIMIT 1;")
      or report_error(__FILE__,__LINE__,"Invalid query");*/
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("DELETE FROM Spiele WHERE id = '$id' LIMIT 1;")
      or report_error(__FILE__,__LINE__,"Invalid query");
    /*mail("info@fcaarberg.ch", "TEST MAIL", "Dies ist nur ein Test Mail",
     "From: webmaster@$SERVER_NAME\nReply-To: webmaster@$SERVER_NAME\nX-Mailer: PHP/" . phpversion());*/
  }

  function addGame($database, $f) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " INSERT INTO Spiele (mannschaft,garderobe,gegner,spielort,klubhaus,spielart,spielnr,datum,saison,anspielzeit,verantwortlicher,sr)
                    VALUES ('".$f["mannschaft"]."',
                            '".$f["garderobe"]."',
                            '".$f["gegner"]."',
                            '".$f["spielort"]."',
                            '".$f["klubhaus"]."',
                            '".$f["spielart"]."',
                            '".$f["spielnr"]."',
                            '".formatDateUS($f["datum"])."',
                            '".getSaison($f["datum"])."',
                            '".$f["anspielzeit"]."',
                            '".$f["verantwortlicher"]."',
                            '".$f["sr"]."');";
    debug_echo($query);
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);
  }

  function updateGame($database, $f, $id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " UPDATE Spiele
                  SET mannschaft='".$f["mannschaft"]."',
                      gegner='".$f["gegner"]."',
                      garderobe='".$f["garderobe"]."',
                      spielort='".$f["spielort"]."',
                      klubhaus='".$f["klubhaus"]."',
                      spielart='".$f["spielart"]."',
                      spielnr='".$f["spielnr"]."',
                      sr='".$f["sr"]."',
                      resultat='".$f["resultat"]."',
                      verantwortlicher='".$f["verantwortlicher"]."',
                      datum='".formatDateUS($f["datum"])."',
                      saison='".getSaison($f["datum"])."',
                      anspielzeit='".$f["anspielzeit"]."'
                WHERE id=$id;";
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);
  }

  function getGameFields($database,$id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = "SELECT * FROM Spiele WHERE id=$id LIMIT 1;";
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);
    $row = mysql_fetch_array($result);
    return $row;
  }

  function gameForm($database, $action, $id = 0, $f = 0, $filter = 0) {
    echo "<form method=\"post\" action=\"$PHP_SELF\" name=\"teamForm\">";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";

    if ($id > 0) echo "<input type=\"hidden\" name=\"id\" value=\"$id\"/>";

    echo "<table align=\"center\" border=0><tr><th>Feld</th><th>Wert</th></tr>";

    echo "<tr><td align=\"center\">Datum:</td>";
    if ($f != 0)
      echo "<td><input type=\"text\" name=\"fields[datum]\" value=\"".formatDateCH($f[datum])."\" size=\"40\"
       maxlength=\"50\"/>dd.mm.YYYY</td></tr>";
    else
      echo "<td><input type=\"text\" name=\"fields[datum]\" size=\"40\" maxlength=\"50\"/>dd.mm.YYYY</td></tr>";

    echo "<tr><td align=\"center\">Anspielzeit:</td>";
    echo "<td><input type=\"text\" name=\"fields[anspielzeit]\" value=\"".time_trimSeconds($f[anspielzeit])."\" 
          size=\"40\" maxlength=\"50\"/>hh:mm</td></tr>";

    echo "<tr><td align=\"center\">Spiel-Nr:</td>";
    echo "<td><input type=\"text\" name=\"fields[spielnr]\" value=\"".$f[spielnr]."\" size=\"40\" maxlength=\"50\"/></td></tr>";
    
    echo "<tr><td align=\"center\">Resultat:</td>";
    echo "<td><input type=\"text\" name=\"fields[resultat]\" value=\"".$f[resultat]."\" size=\"40\" maxlength=\"50\"/></td></tr>";

    echo "<tr><td align=\"center\">Spiel-Ort:</td><td>";
    $spielorte = array ("H","A");
    printDropDownList($spielorte, "fields[spielort]", $f[spielort]);

    echo "<tr><td align=\"center\">Klubhaus geöffnet:</td><td>";
    printYesNo("fields[klubhaus]", $f==0?'J':$f[klubhaus]);

    echo "</td></tr><tr><td align=\"center\">Garderobe:</td><td>";
    $garderoben = array ("FCA1","FCA2","Sek","Prim","ZRA","Bargen");
    printDropDownList($garderoben, "fields[garderobe]", $f[garderobe]);

    echo "</td></tr><tr><td align=\"center\">Spiel-Art:</td><td>";
    $spielarten = array ("M","C","F","T","Fi");
    printDropDownList($spielarten, "fields[spielart]", $f==0?'M':$f[spielart]);

    echo "</td></tr><tr><td align=\"center\">Mannschaft:</td><td>";
    printTeamDropDownList($database,"fields[mannschaft]", ($f==0?$filter[mannschaft]:$f[mannschaft]));

    echo "</td></tr><tr><td align=\"center\">Gegner:</td>";
    echo "<td><input type=\"text\" name=\"fields[gegner]\" value=\"".replaceUml($f[gegner])
        ."\" size=\"40\" maxlength=\"50\"/></td></tr>";

    echo "</td></tr><tr><td align=\"center\">Verantwortlicher:</td><td>";
    printAddressDropDownList($database,"fields[verantwortlicher]",$f[verantwortlicher]);

    echo "</td></tr><tr><td align=\"center\">Schiedsrichter:</td>";
    echo "<td><input type=\"text\" name=\"fields[sr]\" value=\"".replaceUml($f[sr])."\" size=\"40\" maxlength=\"50\"/></td></tr>";
/*
    echo "</td></tr><tr><td align=\"center\">Assistent 1:</td>";
    echo "<td><input type=\"text\" name=\"fields[sra1]\" value=\"".$f[sra1]."\" size=\"40\" maxlength=\"50\"/></td></tr>";

    echo "</td></tr><tr><td align=\"center\">Assistent 2:</td>";
    echo "<td><input type=\"text\" name=\"fields[sra2]\" value=\"".$f[sra2]."\" size=\"40\" maxlength=\"50\"/></td></tr>";
*/
    echo "</table><p align=\"center\"><input type=\"submit\" value=\"Sichern\"/></p>";
    echo "</form>";
  }

  function create_spielplan($db) {
    $db_name = mysql_select_db("fcaarberg", $db);
    $result = mysql_query("SELECT DISTINCT Spiele.spielort,
                                           Spiele.datum,
                                           Spiele.anspielzeit,
                                           Spiele.mannschaft,
                                           Spiele.gegner,
                                           Spiele.spielart,
                                           Mannschaften.id,
                                           Mannschaften.name
                          FROM Mannschaften,
                                Spiele
                          WHERE Spiele.mannschaft = Mannschaften.id
                            AND Spiele.saison = ".getSaison(today())."
                          ORDER BY Spiele.datum,Spiele.anspielzeit ASC;")
      or report_mysql_error(__FILE__,__LINE__);
    $saison = getSaison(today());
    $titel = sprintf("20%02d/20%02d",$saison,$saison+1);
    pdf_spielplan("spielplan", $titel, $result);
  }

  init();
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);

  if (!empty($preselect)) {
    $_SESSION['ses_preselect'] = $preselect;
    // debug_echo("STORE PRESELECT: $preselect");
  }

  if (0 == strcmp($action,"delete")) {
    deleteGame($db,$id);
    /* TEMPORARY REMOVED: create_spielplan($db); */
  } else if (0 == strcmp($action,"insert")) {
    addGame($db,$fields);
    /* TEMPORARY REMOVED: create_spielplan($db); */
  } else if (0 == strcmp($action,"update")) {
    updateGame($db,$fields,$id);
    /* TEMPORARY REMOVED: create_spielplan($db); */
  } else if (0 == strcmp($action,"plan")) {
    create_spielplan($db);
  }
  
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>

  <h1>Spiele Administration</h1>
  <div align="center">

<?php
  if (0 == strcmp($action, "edit")) {
    $game = getGameFields($db, $id);
    gameForm($db, "update", $id, $game);
  } else if (0 == strcmp($action, "new")) {
    gameForm($db, "insert", 0, 0, $_SESSION['ses_preselect']);
  } else {
    showGameList($db, $_SESSION['ses_preselect']);
  } 

  
  ?>
  </div>
<?php
  endContent();
  
  // Left Block content ...
  startLeftBlock();

  adminSecureInformation($login, $userLevel, $ID);
  adminNavigation($userLevel);
  
  endLeftBlock();
  startRightBlock();
?>
  <h2 id="t_newadr">Neues Spiel</h2>
  <a href="admin_spiele.php?action=new">Neues Spiel hinzuf&uuml;gen</a>
  
  <h2 id="t_filter">Filter</h2>
<?php
  showGamePreselection($db, $_SESSION['ses_preselect']);
?>
  
  <h2 id="t_help">Hilfe</h2>
  <b><u>Spielort</u></b>
  <ul>
   <li><b>H</b> Heimspiel </li>
   <li><b>A</b> Auswärtsspiel </li>
  </ul>
  <b><u>Spielarten</u></b>
  <ul>
   <li><b>M</b> Meisterschaft </li>
   <li><b>C</b> Cup </li>
   <li><b>F</b> Freundschaft </li>
   <li><b>T</b> Turnier (KiFu) </li>
   <li><b>Fi</b> Firmenfussball</li>
 </ul>
 <b><u>Gegner</u></b>
 <ul>
  <li><b>Bei Turnieren in Aarberg</b> Anzahl Mannschaften</li>
  <li><b>Bei Turnieren auswärts</b> Turnierort</li>
  <li><b>Sonstige Spiele</b> Gegnerische Mannschaft</li>
 </ul>
 
<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>