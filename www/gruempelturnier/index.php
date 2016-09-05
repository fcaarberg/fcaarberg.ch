<?php
  //*** include mainfile ***//
  include("/home/fcaarberg/public_html/mainfile.php");
  
  //*** functions ***/
  
  function thisYear() {
    return strftime("%Y", time());
  }
  
  function info($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query(" SELECT    *
                            FROM      gruempel_infos
                            WHERE     jahr=".thisYear()."
                            LIMIT     1;")
      or die ("Invalid query");
    $row = mysql_fetch_array($result);
?>
    <h2 align="center">
<?php
    echo formatDateCH($row[datum_start],true)." - ".formatDateCH($row[datum_ende],true)."<br />";
    echo replaceUml($row["ort"]);
    $infos = $row["bemerkungen"];
    if ($infos != null) {
        echo "<br /><br />".replaceUml($infos);
    }
?>
    </h2>
    <h3 align="center">Kategorien</h3>
    <div align="center">
    <table border="1"><tr><td>
    <table border="0">
    
<?php
    $categories = mysql_query(" SELECT * FROM gruempel_kategorien ORDER BY id;")
      or die ("Invalid query");
      
    while ($cat = mysql_fetch_array($categories)) {
      $notes = "";
      if ($cat[bemerkungen] != null) {
      	$notes = " (".$cat[bemerkungen].")";
      }
      echo replaceUml("<tr><td><b>$cat[id]</b></td><td>$cat[bezeichnung]".$notes."</td><td>$cat[spieltag]</td><td>sFr. $cat[preis].--</td></tr>");
    }
?>
    </table></td></tr></table>
    </div>
    <br />
    <br />
    <div align="center">
    <table>
      <tr>
        <TD valign="top"><B>Mannschaften:</B></TD>
        <TD></TD>
        <TD valign="top">6 SpielerInnen plus 2 AuswechselspielerInnen</TD>
        <TD></TD>
      </tr><tr>
        <td colspan="4">&nbsp;</td>
      </tr><tr>
            <TD valign="top"><B>Preise:</B></TD>
            <TD></TD>
            <TD valign="top">Es winken wie immer sch&ouml;ne Preise! Jede Mannschaft<BR>
            erh&auml;lt einen Preis.</TD>
            <TD></TD>
      </tr><tr>
        <td colspan="4">&nbsp;</td>
      </tr><tr>
            <TD valign="top"><B>Anmeldeschluss:</B></TD>
            <TD></TD>
            <TD valign="top"><b><?php echo formatDateCH($row["datum_anmeldung"], true); ?></b>
              <p>
	              Den Einsatz bitte bis am <b><?php echo formatDateCH($row["datum_einzahlung"], true); ?></b> einzahlen.<br>
	              Mannschaften, welche den Einsatz bis zu diesem Zeitpunkt nicht eingezahlt haben, k&ouml;nnen<br/>
	              f&uuml;r den Spielplan und somit f&uuml;r das Turnier nicht mehr ber&uuml;cksichtigt werden.
              </p>
              
              <p>
	              Das Programm wird ca. 5 Tage vor Turnierbeginn per E-Mail
	              verschickt.<br />Der Spielplan wird etwa 1 Woche vor Turnierbeginn
	              <!-- 
	              <a href="/doc/2011-07-31_gruempu-spielplan.pdf">hier</a> ver&ouml;ffentlicht.
	              -->
	              hier ver&ouml;ffentlicht.
              </p>
            </td>
            <td></td>
      </tr>
    </table>
    </div>
<?php
  }
  
  function organisation($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query(" SELECT    *
                            FROM      gruempel_ok
                            ORDER BY  amt ASC;")
      or die ("Invalid query");

    echo "<h2 align=\"center\">OK Gr&uuml;mpelturnier</h2>";
    echo "<div align=\"center\"><table border=0>";
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $i++;
      if ($i%2 == 0) $class = "first";
      else $class = "second";
      echo "<tr valign=\"top\" class=\"$class\">";
      echo "<td><h2>".replaceUml($row["amt"])."</h2></td>";
      echo "<td>";
      show_address($database,$row["adresse"]);
      echo "</td></tr>";
    }

    echo "</table></div>";
  }
  
  function addAnmeldung($database, $f) {
    if ($f[kategorie]    != "" &&
        $f[teamname]     != "" && 
        $f[name_vorname] != "" &&
        $f[adresse]      != "" &&
        $f[plz_ort]      != "" &&
        $f[tel]          != "")
    { 
      $db_name = mysql_select_db("fcaarberg", $database);
      $query = " INSERT INTO gruempel_anmeldungen (datum,kategorie,info,teamname,bemerkungen,name_vorname,adresse,plz_ort,tel,email)
                      VALUES (  now(),'".$f["kategorie"]."',
                                ".thisYear().",
                                '".formatMySQLQuery($f["teamname"])."', 
                                '".formatMySQLQuery($f["bemerkungen"])."', 
                                '".formatMySQLQuery($f["name_vorname"])."',
                                '".formatMySQLQuery($f["adresse"])."',
                                '".formatMySQLQuery($f["plz_ort"])."',
                                '".formatMySQLQuery($f["tel"])."',
                                '".$f["email"]."');";
      $result = mysql_query($query)
        or report_mysql_error();
    
      // sucht den spielbetriebs-verantwortlichen und generiert die fcaarberg-email-adresse
      $result  = mysql_query(" SELECT    *
                              FROM      gruempel_ok,Adressen
                              WHERE     amt='Spielbetrieb'
                                AND     Adressen.id=gruempel_ok.adresse
                                AND     emailalias='J';")
        or die ("Invalid query");
  
      if ($row = mysql_fetch_array($result)) 
      {
        $alias = strtolower($row["vorname"]).".".strtolower($row["name"])."@fcaarberg.ch";
        $alias = str_replace("ä", "ae", $alias);
        $alias = str_replace("ö", "oe", $alias);
        $alias = str_replace("ü", "ue", $alias);
        $alias = str_replace(" ", "", $alias);
        $alias = str_replace("-", "", $alias);
      }
  
      mail("$f[email]", "Anmeldung Grümpelturnier FC Aarberg",
          "Ihre Anmeldung ist erfolgreich entegegen genommen worden.\nBei Fragen zum Spielbetrieb gibt Ihnen $alias Auskunft."
          ."$f[teamname] (Kategorie $f[kategorie])\n"
          ."$f[name_vorname]\n"
          ."----------------------------\n"
          ."http://gruempel.fcaarberg.ch",
          "From: no_reply@fcaarberg.ch\nReply-To: $alias\nX-Mailer: PHP/" . phpversion());

      mail("$alias","Grümpelturnier ".thisYear()." - Neue Anmeldung",
          "Teamname:    $f[teamname]\n"
          ."Kategorie:   $f[kategorie]\n"
          ."Spielführer: $f[name_vorname],$f[adresse],$f[plz_ort]\n"
          ."Bemerkungen: $f[bemerkungen]",
          "From: no_reply@fcaarberg.ch\nReply-To: $f[email]@fcaarberg.ch\nX-Mailer: PHP/" . phpversion());

      $message = "<strong><i>$f[teamname]</i></strong> wurde erfolgreich angemeldet!<br />
                  Die Anmeldung wird in K&uuml;rze per Email an $f[email] best&auml;tigt!<br />
                  Sollten Sie in den naechsten Minuten keine Email erhalten, melden Sie sich<br />
                  bitte bei <a href=\"mailto:$alias\">$alias</a> - Besten Dank!";
    } 
    else 
    {
      $message = "Ihre Anmeldung wurde nicht komplett ausgef&uuml;llt und deshalb <u>nicht</u> registriert!";
    }
    echo "<div align=\"center\"><table border=\"1\"><tr><td>$message</td></tr></table></div>";
  }
  
  function anmeldung($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT datum_anmeldung FROM gruempel_infos WHERE jahr=".thisYear()." LIMIT 1;");
    $row = mysql_fetch_array($result);
    $today = formatDateUS(today(-2));
    if ($today <= $row["datum_anmeldung"]) {
?>
  <h2 align="center">Anmeldung</h2>
  <div align="center">
    <form method="post" action="<?php echo "$PHP_SELF"; ?>" name="anmeldungsForm">
      <input type="hidden" name="action" value="anmelden"/>
      
      <table>
        <TR>
        <TD>Name, Vorname:</TD>
        <TD>
          <INPUT TYPE="text" NAME="fields[name_vorname]" MAXLENGTH=50 SIZE=40>
        </TD>
      </TR><TR>
        <TD>Postadresse:</TD>
        <TD><INPUT TYPE="text" NAME="fields[adresse]" MAXLENGTH=50 SIZE=40></TD>
      </TR><TR>
        <TD>PLZ, Ort:</TD>
        <TD>
          <INPUT TYPE="text" NAME="fields[plz_ort]" MAXLENGTH=50 SIZE=40>
        </TD>
      </TR><TR>
        <TD>Telefon:</TD>
        <TD><INPUT TYPE="text" NAME="fields[tel]" MAXLENGTH=20 SIZE=40></TD>
      </TR><TR>
        <TD>Email:</TD>
        <TD><INPUT TYPE="text" NAME="fields[email]" MAXLENGTH=50 SIZE=40></TD>
      </TR><TR>
        <TD>Mannschaftsname:</TD>
        <TD><INPUT TYPE="text" NAME="fields[teamname]" MAXLENGTH=50 SIZE=40></TD>
      </TR><TR>
        <td valign="top">Kategorie:</td>
        <td>
<?php
    $categories = mysql_query(" SELECT * FROM gruempel_kategorien ORDER BY id ASC;")
      or die ("Invalid query");
      
    while ($cat = mysql_fetch_array($categories)) {
      echo replaceUml("<input type=\"radio\" name=\"fields[kategorie]\" value=\"$cat[id]\" /><b>$cat[id]</b> $cat[bezeichnung] ($cat[spieltag])<br />");
    }  
?>
        </td>
      </tr><TR>
        <TD>Bemerkungen:</TD>
        <TD><INPUT TYPE="text" NAME="fields[bemerkungen]" MAXLENGTH=50 SIZE=40></TD>
      </TR><tr>
        <td colspan=2>
          <input type="SUBMIT" value="Anmelden!">
          <input type="RESET" value="Formular l&ouml;schen">
        </td>
      </tr>
      </TABLE>
    </FORM>
  </div>
<?php
    } else {
?>
  <h2 align="center">Anmeldungs-Datum vorbei!</h2>
  <div align="center">
    Bei Fragen, setzen Sie Sich mit dem Verantwortlichen des OK in Verbindung ...
  </div>
<?php
    } 
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

<h1 align="center">Gr&uuml;mpelturnier <?php echo thisYear(); ?></h1>

<?php
  if (0 == strcmp($action, "anmelden"))
  {
    addAnmeldung($db, $fields);
  } 

  if (0 == strcmp($action, "anmeldung")) 
  {
    anmeldung($db);
  }
  else if (0 == strcmp($action, "ok"))
  {
    organisation($db);
  }
  else 
  {
    info($db);
  }
  
  endContent();
  // Left Block content ...
  startLeftBlock();
?>

<h2 id="h_verein">Gr&uuml;mpelturnier</h2>
<ul>
<li><a href="index.php">Informationen</a></li>
<!-- <li><a href="flyer.php">Flyer</a></li> -->
<li><a href="index.php?action=ok">Organisation</a></li>
<li><a href="index.php?action=anmeldung">Anmeldung</a></li>
<li><a href="einzahlung.php">Einzahlung</a></li>
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
