<?php
/****************************************************************************
 *                           www.fcaarberg.ch
 *
 * Beschreibung   Administrationsseite für die Links-Seite
 * Datei          admin_links.php
 *
 * Autor          patrick.zysset@fcaarberg.ch
 * Datum          03.01.2003
 *
 * $Header$
 ****************************************************************************/

//--- Funktionen ------------------------------------------------------------

  /**
   * zeigt die Liste der Links an (alphabetisch nach Kategorie).
   *
   * @param $database - geöffnete Datenbankverbindung
   */
  function showLinkList($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query  = "SELECT DISTINCT *
                          FROM Links
                      ORDER BY kategorie,titel ASC;";
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);

    echo "<table align=\"center\" border=0 cellpadding=5>\n";
    echo "<tr><td colspan=2></td>";
    echo "<th>Kategorie</th><th>Titel</th><th>URL</th></tr>\n"; // titel zeile
    $i = 0; // Zeilen - Zähler
    while ($row = mysql_fetch_array($result)) {
      $id = $row["id"];
      if ($i++ %2 == 1) $class = "first"; // färbt jede zweite Zeile grau an!
      else $class = "second";
      echo "<tr class=\"$class\">";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=edit\">Bearbeiten</a></td>";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=delete\">L&ouml;schen</a></td>";
      echo replaceUml("<td>".$row["kategorie"]."</td><td><b>".$row["titel"]."</b></td>"
          ."<td>".$row["url"]."</td></tr>\n");
    }
    echo "</table>\n";
    echo "<p align=\"center\"><a href=\"$PHP_SELF?action=new\">Neuer Link hinzuf&uuml;gen</a></p>";
  }

  /**
   * Löscht den Link mit der angegeben ID
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des zu löschenden Link
   */
  function deleteLink($database, $id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("DELETE FROM Links WHERE id = '$id' LIMIT 1;")
      or report_mysql_error();
  }

  /**
   * Fügt einen neuen Link hinzu. Der Eintrag wird mittels eines Arrays
   * übergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, url, kategorie, beschreibung
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   * @param $autor - ID des aktuellen Benutzers, der den Eintrag erstellt hat
   */
  function addLink($database, $f) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " INSERT INTO Links (titel,url,kategorie,beschreibung)
                    VALUES (  '".$f["titel"]."',
                              '".$f["url"]."',
                              '".$f["kategorie"]."',
                              '".$f["beschreibung"]."');";
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
  function updateLink($database, $f, $id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " UPDATE Links SET titel='".$f["titel"]."',
                                url='".$f["url"]."',
                                kategorie='".$f["kategorie"]."',
                                beschreibung='".$f["beschreibung"]."'
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
  function linkForm($action, $id = 0, $f = 0) {
    echo "<form method=\"post\" action=\"$PHP_SELF\" name=\"newsForm\">";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
    if ($id > 0) {
      echo "<input type=\"hidden\" name=\"id\" value=\"$id\"/>";
    }
    echo "<table align=\"center\" border=0><tr><th>Feld</th><th>Wert</th></tr>";
    echo "<tr><td align=\"center\">Titel:</td>";
    echo "<td><input type=\"text\" name=\"fields[titel]\" value=\"".replaceUml($f[titel])."\" size=\"40\" maxlength=\"50\"/></td></tr>";
    echo "<tr><td align=\"center\">URL:</td>";
    echo "<td><input type=\"text\" name=\"fields[url]\" value=\"".$f[url]."\" size=\"40\" maxlength=\"200\"/>(ohne http://)</td></tr>";
    echo "<tr><td align=\"center\">Beschreibung:</td>";
    echo "<td><textarea name=\"fields[beschreibung]\" rows=\"7\" cols=\"40\" wrap=\"virtual\">".replaceUml($f[beschreibung])."</textarea></td></tr>";
    echo "<tr><td align=\"center\">Kategorie:</td><td>";
    $kategorien = array ('Sponsoren', 'Fussball', 'Vereine', 'Software', 'Aarberg', 'Divers', 'Fun');
    printDropDownList($kategorien, "fields[kategorie]", $f[kategorie]);
    echo "</td></tr></table><p align=\"center\"><input type=\"submit\" value=\"Sichern\"/></p>";
    echo "</form>";
  }

  /**
   * Gibt die Felder eines vorhandenen News-Eintrages zurück.
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des Eintrages
   * @param alle Felder des Eintrages als Array
   */
  function getLinkFields($database,$id) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT * FROM Links WHERE id=$id LIMIT 1;")
      or report_mysql_error();
    $row = mysql_fetch_array($result);
    return $row;
  }
  
//--- Und hier gehts los! ----------------------------------------------------

  $requiredUserLevel = array(6, 10);
  // $minUserLevel = 8;
  $cfgProgDir = 'phpSecurePages/';
  include($cfgProgDir."secure.php");

  include("admin_mainfile.php");
  init();

  $db = mysql_pconnect(DB_HOST, DB_USER, DB_PWD);
  
  // action (parameter $action) ausführen, bevor die neue Seite angezeigt wird!
  if (0 == strcmp($action,"delete")) {
    deleteLink($db,$id);
  } else if (0 == strcmp($action,"insert")) {
    addLink($db,$fields);
  } else if (0 == strcmp($action,"update")) {
    updateLink($db,$fields,$id);
  }
  
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>

<h1>Links Administration</h1>

<div align="center">
<b>Achtung! Keine Werbelinks f&uuml;r kommerzielle Firmen/Produkte/Dienstleistungen, ausser FCA-Sponsoren.</b>

<?php
  // Zeige Seite an...
  if (0 == strcmp($action, "edit")) {
    $fields = getLinkFields($db, $id);
    linkForm("update", $id, $fields);
  } else if (0 == strcmp($action, "new")) {
    linkForm("insert");
  } else {
    showLinkList($db);
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
