<?php
/****************************************************************************
 *                           www.fcaarberg.ch
 *
 * Beschreibung   Administrationsseite fï¿½r die News
 * Datei          admin_news.php
 *
 * Autor          patrick.zysset@fcaarberg.ch
 * Datum          25.08.2002
 *
 * $Header$
 ****************************************************************************/
//--- Init ------------------------------------------------------------------
  $requiredUserLevel = array(2,6,7,10); // Spielberichteverfasser,Redaktion,Spiko/Juko,Administrator
  $cfgProgDir = 'phpSecurePages/';
  include($cfgProgDir."secure.php");  // Sicherheitscheck einfï¿½gen
  include("admin_mainfile.php");      // diverse FCA Funktionen einfï¿½gen
 
//--- Funktionen ------------------------------------------------------------

  /**
   * zeigt die Liste der News an (letzte zuerst).
   *
   * @param $database - geöffnete Datenbankverbindung zu der News-DB
   */
  function showSpielberichteList($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $saison = getSaison(today());
    $query  = "SELECT DISTINCT *
                          FROM Spielberichte,Spiele
                         WHERE Spielberichte.spiel = Spiele.id AND saison = '$saison'
                      ORDER BY datum DESC";
//                         LIMIT 0,15;"; // !!! nur die letzten 15 werden angezeigt!!!
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);

    echo "<table align=\"center\" border=0 cellpadding=5>\n";
    echo "<tr><td colspan=2></td>";
    echo "<th>Datum</th><th>Spiel</th><th>Autor</th></tr>\n"; // titel zeile
    $i = 0; // Zeilen - Zähler
    while ($row = mysql_fetch_array($result)) {
      $id = $row["id"];
      if ($i++ %2 == 1) $class = "first"; // färbt jede zweite Zeile grau an!
      else $class = "second";
      echo "<tr class=\"$class\">";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=edit\">Bearbeiten</a></td>";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=delete\">L&ouml;schen</a></td>";
      echo "<td>".$row["datum"]."</td><td><b>".replaceUml($row["gegner"])."</b></td>";
      echo "<td>".$row["autor"]."</td></tr>\n";
    }
    echo "</table>\n";
  }
  
  function showSpieleList($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " SELECT Mannschaften.id,Mannschaften.name,Spiele.*
                 FROM Spiele, Mannschaften
                WHERE mannschaft = Mannschaften.id
                  AND datum BETWEEN '".formatDateUS(today(-21))."' AND '".formatDateUS(today())."'
             ORDER BY datum DESC,anspielzeit ASC;";
    
    $result = mysql_query($query)
      or report_error(__FILE__,__LINE__,"Invalid query: $query");
      
    echo "<table align=\"center\" border=0 cellpadding=5>\n<tr><td></td>";
    echo "<th>Datum</th><th>Spiel-Nr.</th><th>Mannschaft</th><th>Gegner</th></tr>\n";
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
      $id = $row["id"];
      if ($i++ %2 == 1) $class = "first";
      else $class = "second";
      echo "<tr class=\"$class\"><td>";
      echo "<a href=\"$PHP_SELF?id=$id&action=new\">Bericht schreiben</a></td>";
      echo "<td>".formatDateCH($row["datum"])."</td>";
      echo "<td>".$row["spielnr"]."</td>";
      echo "<td>".replaceUml($row["name"])."</td>";
      echo "<td>".replaceUml($row["gegner"])."</td>";
      echo "</tr>\n";
    }
    echo "</table>\n";
  }

  /**
   * Lï¿½scht den News-Eintrag mit der angegeben ID
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des zu lï¿½schenden News-Eintrages
   */
  function deleteSpielbericht($database, $id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("DELETE FROM Spielberichte WHERE spiel = '$id' LIMIT 1;")
      or report_mysql_error();
  }

  /**
   * Fï¿½gt einen neuen NewsEintrag hinzu. Der Eintrag wird mittels eines Arrays
   * ï¿½bergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, inhalt, kategorie
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   * @param $autor - ID des aktuellen Benutzers, der den Eintrag erstellt hat
   */
  function addSpielbericht($database, $f, $id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " INSERT INTO Spielberichte (spiel,titel,kurz,lang,telegramm,fvbjLink,autor)
                    VALUES (  $id, 
                              '".formatMySQLQuery($f["titel"])."',
                              '".formatMySQLQuery($f["kurz"])."',
                              '".formatMySQLQuery($f["lang"])."',
                              '".formatMySQLQuery($f["telegramm"])."',
                              '".formatMySQLQuery($f["fvbjLink"])."',
                              ".$f["autor"].");";
    $result = mysql_query($query)
      or report_mysql_error();

    $query = " UPDATE Spiele SET resultat='".$f["resultat"]."'
               WHERE id=$id;";
    $result = mysql_query($query)
      or report_mysql_error();
  }

  /**
   * ï¿½ndert einen vorhandenen NewsEintrag ab. Der neue Eintrag wird mittels eines Arrays
   * ï¿½bergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, inhalt, kategorie
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   * @param $id - ID des zu ï¿½ndernden News-Eintrages
   * @param $autor - ID des aktuellen Benutzers, der den Eintrag erstellt hat
   */
  function updateSpielbericht($database, $f, $id, $autor) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " UPDATE Spielberichte SET  
                                titel='".$f["titel"]."',
                                kurz='".$f["kurz"]."',
                                lang='".$f["lang"]."',
                                telegramm='".$f["telegramm"]."',
                                fvbjLink='".$f["fvbjLink"]."',
                                autor=".$f["autor"]."
               WHERE spiel=$id;";
    $result = mysql_query($query)
      or report_mysql_error();

    $query = " UPDATE Spiele SET resultat='".$f["resultat"]."'
               WHERE id=$id;";
    $result = mysql_query($query)
      or report_mysql_error();
  }

  /**
   * Gibt ein Formular zum editieren / erstellen eines News-Eintrages aus.
   * Mit dem Parameter action kann angegeben werden, ob der Eintrag neu
   * erstellt oder abgeï¿½ndert werden soll. Falls der Eintrag bereits existiert
   * muss noch die ID und die ï¿½blichen Felder mitgeliefert werden.
   *
   * @param $action - "insert" | "update"$
   * @param $id - ID des zu ï¿½ndernden Eintrages
   * @param $f - die ursprï¿½nglichen Werte des zu ï¿½ndernden Eintrages
   */
  function spielberichteForm($action, $id, $f = 0) {
  	if ($action == "insert")
  	{
  	  $telegramm =
            "Sportplatz\tSpielfeld,Ort\n" .
            "SR\tName,Ort\n" .
            "\n" .
            "Tore\n" .
            "00. 0-0 Name (Assist)\n" .
            "\n" .
  	  		"FC Aarberg\n" .
  	  		"\n" .
  	  		"Bemerkungen\n" .
  	  		"\n";
  	}
  	else
  	{
  	  $telegramm = $f[telegramm];
  	}
  	
    $content = "<form method=\"post\" action=\"$PHP_SELF\" name=\"spielberichteForm\">"
      ."<input type=\"hidden\" name=\"action\" value=\"$action\"/>"
      ."<input type=\"hidden\" name=\"id\" value=\"$id\"/>"
      ."<table align=\"center\" border=0><tr><th>Feld</th><th>Wert</th></tr>"
      ."<tr><td align=\"center\">Titel:</td>"
      ."<td><input type=\"text\" name=\"fields[titel]\" value=\"".$f[titel]."\" size=\"60\" maxlength=\"50\"/></td></tr>"
      ."<tr><td align=\"center\">Resultat:</td>"
      ."<td><input type=\"text\" name=\"fields[resultat]\" value=\"".$f[resultat]."\" size=\"60\" maxlength=\"10\"/></td></tr>"
      ."<tr><td align=\"center\">Einleitung:</td>"
      ."<td><textarea name=\"fields[kurz]\" rows=\"5\" cols=\"60\" wrap=\"virtual\">".$f[kurz]."</textarea></td></tr>"
      ."<tr><td align=\"center\">Bericht:</td>"
      ."<td><textarea name=\"fields[lang]\" rows=\"10\" cols=\"60\" wrap=\"virtual\">".$f[lang]."</textarea></td></tr>"
      ."<tr><td align=\"center\">Link zum FVBJ Telegramm:</td>"
      ."<td><input type=\"text\" name=\"fields[fvbjLink]\" value=\"".$f[fvbjLink]."\" size=\"60\" maxlength=\"120\"/></td></tr>"
      ."<tr><td align=\"center\">Telegramm:</td>"
      ."<td><textarea name=\"fields[telegramm]\" rows=\"15\" cols=\"60\" wrap=\"virtual\">".$telegramm."</textarea></td></tr>"
      ."<tr><td align=\"center\">Autor:</td><td>";
    echo replaceUml($content);
    printAddressDropDownList($database,"fields[autor]",$f[autor]);
    echo "</td></tr>"
      ."</table><p align=\"center\"><input type=\"submit\" value=\"Sichern\"/></p>"
      ."</form>";
  }

  /**
   * Gibt die Felder eines vorhandenen News-Eintrages zurï¿½ck.
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des Eintrages
   * @param alle Felder des Eintrages als Array
   */
  function getSpielberichtFields($database,$id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT * FROM Spielberichte WHERE spiel=$id LIMIT 1;")
      or report_mysql_error();
    $row = mysql_fetch_array($result);

	$result2 = mysql_query("SELECT * FROM  Spiele WHERE id=$id LIMIT 1;")
      or report_mysql_error(__FILE__, __LINE__);
    $row2 = mysql_fetch_array($result2);
    $row[resultat] = $row2[resultat];
    return $row;
  }

//--- Und hier gehts los! ----------------------------------------------------

  init(); // initialisiert die DB Konstanten HOST, USER, PWD
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD); // Verbindung zur Datenbank herstellen

  // action (parameter $action) ausfï¿½hren, bevor die neue Seite angezeigt wird!
  if (0 == strcmp($action,"delete")) {
    deleteSpielbericht($db,$id);
  } else if (0 == strcmp($action,"insert")) {
    addSpielbericht($db,$fields,$id);
  } else if (0 == strcmp($action,"update")) {
    updateSpielbericht($db,$fields,$id);
  }
  
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>

    <h1>Spielberichte Administration</h1>
    <div align="center">
    <?php
      // Zeige Seite an...
      if (0 == strcmp($action, "edit")) {
        $fields = getSpielberichtFields($db, $id);
        spielberichteForm("update", $id, $fields);
      } else if (0 == strcmp($action, "spiele"))  {
        showSpieleList($db);
      } else if (0 == strcmp($action, "new")) {
      	$fields = getSpielberichtFields($db, $id);
        spielberichteForm("insert",$id, $fields);
      } else {
        showSpielberichteList($db);
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
  <h2 id="h_new">Neuer Spielbericht</h2>
  <a href="admin_spielberichte?action=spiele">Neuer Spielbericht hinzuf&uuml;gen</a>
  
<?php
  
  endRightBlock();
  closeBody();
  closeDocument();
?>  
