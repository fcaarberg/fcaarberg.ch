<?php
/****************************************************************************
 *                           www.fcaarberg.ch
 *
 * Beschreibung   Administrationsseite f�r die Vorstandsmitglieder
 * Datei          admin_vorstand.php
 *
 * Autor          patrick.zysset@fcaarberg.ch
 * Datum          02.01.2003
 *
 * $Header$
 ****************************************************************************/

//--- Funktionen ------------------------------------------------------------

  /**
   * zeigt die Liste aller Mitglieder an
   *
   * @param $database - ge�ffnete Datenbankverbindung zu der News-DB
   */
  function showOKList($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query  = "SELECT DISTINCT *
                          FROM gruempel_ok,Adressen
                         WHERE gruempel_ok.adresse = Adressen.id
                      ORDER BY amt ASC;";
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);

    echo "<table align=\"center\" border=0 cellpadding=5>\n";
    echo "<tr><td colspan=2></td>";
    echo "<th>Amt</th><th>Name</th></tr>\n"; // titel zeile
    $i = 0; // Zeilen - Z�hler
    while ($row = mysql_fetch_array($result)) {
      $id = $row["amt"];
      if ($i++ %2 == 1) $class = "first"; // f�rbt jede zweite Zeile grau an!
      else $class = "second";
      echo "<tr class=\"$class\">";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=edit\">Bearbeiten</a></td>";
      echo "<td><a href=\"$PHP_SELF?id=$id&action=delete\">L&ouml;schen</a></td>";
      echo replaceUml("<td>".$row["amt"]."</td><td><b>".$row["name"]." ".$row["vorname"]."</b></td></tr>\n");
    }
    echo "</table>\n";
    echo "<p align=\"center\"><a href=\"$PHP_SELF?action=new\">Neues OK-Mitglied hinzuf&uuml;gen</a></p>";
  }

  /**
   * L�scht den News-Eintrag mit der angegeben ID
   *
   * @param $database - aktuelle Datenbankverbindung
   */
  function deleteOK($database, $amt) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("DELETE FROM gruempel_ok WHERE amt='$amt' LIMIT 1;")
      or report_mysql_error();
  }

  /**
   * F�gt einen neuen NewsEintrag hinzu. Der Eintrag wird mittels eines Arrays
   * �bergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, inhalt, kategorie
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   */
  function addOK($database, $f) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " INSERT INTO gruempel_ok (adresse,amt)
                    VALUES (  '".$f["adresse"]."',
                              '".$f["amt"]."');";
    $result = mysql_query($query)
      or report_mysql_error();
  }

  /**
   * �ndert einen vorhandenen NewsEintrag ab. Der neue Eintrag wird mittels eines Arrays
   * �bergeben, welcher folgende Text-Felder besitzen muss:
   *  titel, inhalt, kategorie
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $f - Array mit den Feldern des neuen News-Eintrages
   * @param $id - ID des zu �ndernden News-Eintrages
   */
  function updateOK($database, $f) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = " UPDATE gruempel_ok  SET  adresse='".$f["adresse"]."'
               WHERE amt='".$f["amt"]."';";
    $result = mysql_query($query)
      or report_mysql_error();
  }

  /**
   * Gibt ein Formular zum editieren / erstellen eines News-Eintrages aus.
   * Mit dem Parameter action kann angegeben werden, ob der Eintrag neu
   * erstellt oder abge�ndert werden soll. Falls der Eintrag bereits existiert
   * muss noch die ID und die �blichen Felder mitgeliefert werden.
   *
   * @param $db - Datenbank-Handle
   * @param $action - "insert" | "update"$
   * @param $id - ID des zu �ndernden Eintrages
   * @param $f - die urspr�nglichen Werte des zu �ndernden Eintrages
   */
  function OKForm($db, $action, $id = 0, $f = 0) {
    echo "<form method=\"post\" action=\"$PHP_SELF\" name=\"gruempelOkForm\">";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
    if ($id > 0) {
      echo "<input type=\"hidden\" name=\"adresse\" value=\"$id\"/>";
    }
    echo "<table align=\"center\" border=0><tr><th>Feld</th><th>Wert</th></tr>";
    echo "<tr><td align=\"center\">Amt:</td><td>";
    $aemter = array ('Praesident', 'Kassier', 'Spielbetrieb', 'Barbetrieb', 'Festwirtschaft', 'Elektrik', 'Bauten', 'Sponsoring');
    printDropDownList($aemter, "fields[amt]", $f[amt]);

    echo "</td></tr><tr><td align=\"center\">Mitglied:</td><td>";
    printAddressDropDownList($db,"fields[adresse]",$f[adresse]);

    echo "</td></tr></table><p align=\"center\"><input type=\"submit\" value=\"Sichern\"/></p>";
    echo "</form>";
  }

  /**
   * Gibt die Felder eines vorhandenen Vorstand-Eintrages zur�ck.
   *
   * @param $database - aktuelle Datenbankverbindung
   * @param $id - ID des Eintrages
   * @param alle Felder des Eintrages als Array
   */
  function getOKFields($database,$amt) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT * FROM gruempel_ok WHERE amt='$amt' LIMIT 1;")
      or report_mysql_error();
    $row = mysql_fetch_array($result);
    return $row;
  }

//--- Und hier gehts los! ----------------------------------------------------

  $requiredUserLevel = array(1,10);      // Gruppe "Gr�mpel","Administrator"
  $cfgProgDir = 'phpSecurePages/';
  include($cfgProgDir."secure.php");  // Sicherheitscheck einf�gen
  
  include("admin_mainfile.php");      // diverse FCA Funktionen einf�gen

  init(); // initialisiert die DB Konstanten HOST, USER, PWD
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD); // Verbindung zur Datenbank herstellen

  // action (parameter $action) ausf�hren, bevor die neue Seite angezeigt wird!
  if (0 == strcmp($action,"delete")) {
    deleteOK($db,$id);
  } else if (0 == strcmp($action,"insert")) {
    addOK($db,$fields);
  } else if (0 == strcmp($action,"update")) {
    updateOK($db,$fields);
  }
  
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>
  <h1>Gr&uuml;mpelturnier OK Administration</h1>
  <div align="center">

<?php
  // Zeige Seite an...
  if (0 == strcmp($action, "edit")) {
    $fields = getOKFields($db, $id);
    OKForm($db,"update", $id, $fields);
  } else if (0 == strcmp($action, "new")) {
    OKForm($db,"insert");
  } else {
    showOKList($db);
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
