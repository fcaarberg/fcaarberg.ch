<?php
/****************************************************************************
 *                           www.fcaarberg.ch
 *
 * Beschreibung   Uploadseite für verschiedene Dateien
 * Datei          admin_upload.php
 *
 * Autor          patrick.zysset@fcaarberg.ch
 * Datum          03.01.2003
 *
 * $Header$
 ****************************************************************************/

//--- Konstanten ------------------------------------------------------------

  define("PUBLIC_PATH", "/home/fcaarberg/public_html");
  //How big do you want size limit to be in bytes
  define("SIZE_LIMIT", "1500000"); // 1.5 MB

//--- Funktionen ------------------------------------------------------------

  function uploadFile ($database, $f, $doc_folder)
  {
    global $file, $file_name, $file_size;
    // include "config.php";
    $db_name = mysql_select_db("fcaarberg", $database);

    if ($doc_folder == "")
    {
      return "Kein Verzeichnis ausgew&auml;hlt!";
    }
    if ($file_name == "") {
      return "Keine Datei ausgew&auml;hlt!";
    }
    if (file_exists(PUBLIC_PATH."/$doc_folder/$file_name")) {
      return "Datei existiert bereits!";
    }
    if (SIZE_LIMIT < $file_size) {
      return "Datei zu gross!";
    }

    $ext = strrchr($file_name,'.');
    $extensions = array(".pdf");
    if (!in_array($ext,$extensions)) {
      return "Falscher Datei-typ!";
    }

    if (!copy($file, PUBLIC_PATH."/$doc_folder/$file_name")) {
      return PUBLIC_PATH."/$doc_folder/$file_name <br />Datei konnte nicht auf den Server kopiert werden!";
    }
      
    $query  = "INSERT INTO Dokumente (titel,kategorie,link,beschreibung,datum)
                    VALUES ('$f[titel]',
                            '$f[kategorie]',
                            'http://www.fcaarberg.ch/$doc_folder/$file_name',
                            '$f[beschreibung]',
                            now() );";
    $result = mysql_query($query)
      or report_mysql_error(__FILE__,__LINE__);

    return "Datei erfolgreich hochgeladen!";
  }

  function showFolder ($folder)
  {
    $d = dir($folder);
    echo "<h2>".$d->path."</h2>";
    while($entry=$d->read()) {
      echo $entry."<br>\n";
    }
    $d->close();
  }

  function deleteFile ()
  {
  }

  function showUploadForm ()
  {
    $categories = array('Verein', 'Spielbetrieb', 'SFV', 'Junioren', 'Borromini', 'Diverse');
    echo "<form method=\"POST\" action=\"admin_dokumente.php?action=upload\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=".SIZE_LIMIT.">
            <table align=\"center\"><tr><td>Kategorie:</td><td>";
    printDropDownList($categories, "fields[kategorie]", "Verein");
    echo "    </td></tr><tr><td>PDF-Datei</td><td><input type=file name=file size=30 ></td></tr>
              <tr><td>Titel</td><td><input type=\"text\" name=fields[titel] size=\"40\" maxlength=\"50\" /></td></tr>
              <tr><td>Beschreibung</td><td><textarea name=fields[beschreibung]></textarea></td></tr>".
              "<tr><td colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Hochladen\" /></td></tr>".
//              "<tr><td colspan=\"2\"><button name=\"submit\" type=\"submit\" value=\"Hochladen\">Hochladen</button></td></tr>".
         "  </table>
          </form>";
  }

//--- Und hier gehts los! ----------------------------------------------------

  $minUserLevel = 7;                  // minimaler User Level
  $cfgProgDir = 'phpSecurePages/';
  include($cfgProgDir."secure.php");  // Sicherheitscheck einfügen
  include("admin_mainfile.php");      // diverse FCA Funktionen einfügen

  init(); // initialisiert die DB Konstanten HOST, USER, PWD
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD); // Verbindung zur Datenbank herstellen

  // action (parameter $action) ausführen, bevor die neue Seite angezeigt wird!
  if (0 == strcmp($action,"delete")) {
    deleteFile();
  } else if (0 == strcmp($action,"upload")) {
    $result = uploadFile($db, $fields, "doc");
  }
  
  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();
?>

<h1>Administration - Dateien Hochladen!</h1>
<div align="center">

<?php
  // Zeige Seite an...
  if (0 == strcmp($action, "upload")) {
    echo "$result";
  } else {
    showUploadForm();
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
