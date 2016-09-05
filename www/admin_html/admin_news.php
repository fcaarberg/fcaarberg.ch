<?php
/****************************************************************************
 *                           www.fcaarberg.ch
 *
 * Beschreibung   Administrationsseite für die News
 * Datei          admin_news.php
 *
 * Autor          patrick.zysset@fcaarberg.ch
 * Datum          25.08.2002
 *
 * $Header$
 ****************************************************************************/
 
  $requiredUserLevel = array(6,7,10);
  $cfgProgDir = 'phpSecurePages/';
  include($cfgProgDir."secure.php");  // Sicherheitscheck einfügen
  include("admin_mainfile.php");      // diverse FCA Funktionen einfügen

//--- Funktionen ------------------------------------------------------------

  /**
   * zeigt die Liste der News an (letzte zuerst).
   *
   * @param $database - geöffnete Datenbankverbindung zu der News-DB
   */
  function showNewsList($database, $userLevel, $user) {
    $filter = "";
    if ($userLevel == 2) 
    {
      $filter = " AND kategorie = 'Mannschaften'";
    }
    if ($userLevel < 10)
    {
      $filter .= " AND autor = '$user'";
    }
    $db_name = mysql_select_db("fcaarberg", $database);
    $query  = "SELECT DISTINCT *
                          FROM News, phpSP_users
                         WHERE autor = primary_key $filter
                      ORDER BY datum DESC
                         LIMIT 0,15;"; // !!! nur die letzten 15 werden angezeigt!!!
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);

    echo "<table align=\"center\" border=0 cellpadding=5>\n";
    echo "<tr><td colspan=2></td>";
    echo "<th>Datum</th><th>Titel</th><th>Mannschaft</th><th>Autor</th></tr>\n"; // titel zeile
    $i = 0; // Zeilen - Zähler
    while ($row = mysql_fetch_array($result)) {
      $id = $row["id"];
      if ($i++ %2 == 1) $class = "first"; // färbt jede zweite Zeile grau an!
      else $class = "second";
      echo "<tr class=\"$class\">";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=edit\">Bearbeiten</a></td>";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=delete\">L&ouml;schen</a></td>";
      echo "<td>".formatDateCH($row["datum"])."</td><td><b>".replaceUml($row["titel"])."</b></td>";
      echo "<td>$row[mannschaft]</td>";
      echo "<td>".$row["user"]."</td></tr>\n";
    }
    echo "</table>\n";
    echo "<p align=\"center\"><a href=\"$PHP_SELF?action=new\">Neue News hinzuf&uuml;gen</a></p>";
  }

  /**
   * Löscht den News-Eintrag mit der angegeben ID
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des zu löschenden News-Eintrages
   */
  function deleteNews($database, $id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("DELETE FROM News WHERE id = '$id' LIMIT 1;")
      or report_mysql_error();
  }

  /**
   * Fügt einen neuen NewsEintrag hinzu. Der Eintrag wird mittels eines Arrays
   * übergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, inhalt, kategorie
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   * @param $autor - ID des aktuellen Benutzers, der den Eintrag erstellt hat
   */
  function addNews($database, $f, $autor) {
    if (0 == strcmp($f["kategorie"],"Mannschaften") && 0 == $f["mannschaft"] ) return;
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " INSERT INTO News (datum,titelseite,titel,inhalt,kategorie,mannschaft,autor)
                    VALUES (  '".formatDateUS($f["datum"])."',
                              '".$f["titelseite"]."',
                              '".mysql_real_escape_string($f["titel"])."',
                              '".mysql_real_escape_string($f["inhalt"])."',
                              '".$f["kategorie"]."',
                              '".$f["mannschaft"]."',
                              $autor);";
    $result = mysql_query($query)
      or report_mysql_error();
  }

  /**
   * Ändert einen vorhandenen NewsEintrag ab. Der neue Eintrag wird mittels eines Arrays
   * übergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, inhalt, kategorie
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   * @param $id - ID des zu ändernden News-Eintrages
   * @param $autor - ID des aktuellen Benutzers, der den Eintrag erstellt hat
   */
  function updateNews($database, $f, $id, $autor) {
    if (0 == strcmp($f["kategorie"],"Mannschaften") && 0 == $f["mannschaft"] ) return;
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " UPDATE News SET  datum='".formatDateUS($f["datum"])."',
                                titelseite='".mysql_real_escape_string($f["titelseite"])."',
                                titel='".mysql_real_escape_string($f["titel"])."',
                                inhalt='".mysql_real_escape_string($f["inhalt"])."',
                                kategorie='".mysql_real_escape_string($f["kategorie"])."',
                                mannschaft='".$f["mannschaft"]."',
                                autor=$autor
               WHERE id=$id;";
    $result = mysql_query($query)
      or report_mysql_error();
  }
  
  /**
   * Gibt ein Formular zum editieren / erstellen eines News-Eintrages aus.
   * Mit dem Parameter action kann angegeben werden, ob der Eintrag neu
   * erstellt oder abgeändert werden soll. Falls der Eintrag bereits existiert
   * muss noch die ID und die üblichen Felder mitgeliefert werden.
   *
   * @param $action - "insert" | "update"$
   * @param $id - ID des zu ändernden Eintrages
   * @param $f - die ursprünglichen Werte des zu ändernden Eintrages
   */
  function newsForm($database, $userLevel, $action, $id = 0, $f = 0) {
    if ($userLevel == 2)
    {
      $catOptions = "<option>Mannschaften</option>";
    }
    else
    {
      $catOptions .= addOption("Spielbetrieb", $f[kategorie]) 
                  .  addOption("Verein", $f[kategorie]) 
                  .  addOption("Internet", $f[kategorie]) 
                  .  addOption("Mannschaften", $f[kategorie]) 
                  .  addOption("Sonstige", $f[kategorie]);
    }
    $content = "<form method=\"post\" action=\"$PHP_SELF\" name=\"newsForm\">"
      ."<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
    if ($id > 0) {
      $content .= "<input type=\"hidden\" name=\"id\" value=\"$id\"/>";
    }

    $content .= "<table align=\"center\" border=0><tr><th>Feld</th><th>Wert</th></tr>"
      ."<tr><td align=\"center\">Datum:</td>";
    
    if (0 == strcmp($f[datum],"")) {
        $content .= "<td><input type=\"text\" name=\"fields[datum]\" value=\"".today()."\" size=\"30\" maxlength=\"30\"/>dd.mm.YYYY</td></tr>";
    } else {
    	$content .= "<td><input type=\"text\" name=\"fields[datum]\" value=\"".formatDateCH($f[datum])."\" size=\"30\" maxlength=\"30\"/>dd.mm.YYYY</td></tr>";
    }      
    $content .= "<tr><td align=\"center\">Titel:</td>"
      ."<td><input type=\"text\" name=\"fields[titel]\" value=\"".$f[titel]."\" size=\"60\" maxlength=\"60\"/></td></tr>"
      ."<tr><td align=\"center\">Inhalt:</td>"
      ."<td><textarea name=\"fields[inhalt]\" rows=\"7\" cols=\"60\" wrap=\"virtual\">".$f[inhalt]."</textarea></td></tr>"
      ."<tr><td align=\"center\">Kategorie:</td>"
      ."<td><select name=\"fields[kategorie]\">$catOptions</select></td></tr>";
    echo replaceUml($content);
    echo "<tr><td align=\"center\">Mannschaft:</td><td>";
    printTeamDropDownList($database,"fields[mannschaft]",$f[mannschaft]);
    echo "</td></tr><tr><td>Titelthema:</td><td>";
    printYesNo("fields[titelseite]", $f[titelseite]);
    echo "</td></tr></table><p align=\"center\"><input type=\"submit\" value=\"Sichern\"/></p>"
        ."</form>";
  }
  
  /**
   * Add option $option for select. Mark as selected if $currentCategorie match with the $option.
   */
  function addOption($option, $currentCategorie = "") {
    $opt = "<option ";
    if ($option == $currentCategorie) {
      $opt .= " selected "; // mark as selected option
    }
    $opt .= ">" . $option . "</option>";
    return $opt;
  }

  /**
   * Gibt die Felder eines vorhandenen News-Eintrages zurück.
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des Eintrages
   * @param alle Felder des Eintrages als Array
   */
  function getNewsFields($database,$id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT * FROM News WHERE id=$id LIMIT 1;")
      or report_mysql_error();
    $row = mysql_fetch_array($result);
    return $row;
  }

//--- Und hier gehts los! ----------------------------------------------------

  init(); // initialisiert die DB Konstanten HOST, USER, PWD
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD); // Verbindung zur Datenbank herstellen

  // action (parameter $action) ausführen, bevor die neue Seite angezeigt wird!
  if (0 == strcmp($action,"delete")) {
    deleteNews($db,$id);
  } else if (0 == strcmp($action,"insert")) {
    addNews($db,$fields,$ID);
  } else if (0 == strcmp($action,"update")) {
    updateNews($db,$fields,$id,$ID);
  }
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>

    <h1>Mitteilungen Administration</h1>
    <div align="center">
    <?php
      // Zeige Seite an...
      if (0 == strcmp($action, "edit")) {
        $fields = getNewsFields($db, $id);
        newsForm($db, $userLevel, "update", $id, $fields);
      } else if (0 == strcmp($action, "new")) {
        newsForm($db, $userLevel, "insert");
      } else {
        showNewsList($db,$userLevel,$ID);
      }
    ?>
    </div>

<?php
  endContent();
  
  // Left Block content ...
  startLeftBlock();

  adminSecureInformation($login, $userLevel, $ID);
  adminNavigation($userLevel);
  
  endRightBlock();
  closeBody();
  closeDocument();
?>  
