<?php
  include("/home/fcaarberg/public_html/mainfile.php");

  function printVorstand($db) {
    $db_name = mysql_select_db("fcaarberg", $db);
    $result = mysql_query(" SELECT    *
                            FROM      borromini
                            ORDER BY  amt ASC;")
      or die ("Invalid query");

    echo "<h1>Borromini Vorstand</h1>";
    echo "<div align=\"center\"><table border=0>";
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $i++;
      if ($i%2 == 0) $class = "first";
      else $class = "second";
      echo "<tr valign=\"top\" class=\"$class\">";
      echo "<td><h2>".replaceUml($row["amt"])."</h2></td>";
      echo "<td>";
      show_address($db,$row["adresse"]);
      echo "</td></tr>";
    }

    echo "</table></div>";
  }

  function addAnmeldung($db, $f) {
    if ($f[name]    != "" &&
        $f[vorname] != "" &&
        $f[adresse] != "" &&
        $f[plz]     != "" &&
        $f[ort]     != "" &&
        $f[email]   != "") { 
        	
      $db_name = mysql_select_db("fcaarberg", $db);
      $query = " INSERT INTO borromini_anmeldung (datum,name,vorname,adresse,plz,ort,tel,bemerkungen,email)
                      VALUES (  now(),
                                '".formatMySQLQuery($f["name"])."',
                                '".formatMySQLQuery($f["vorname"])."',
                                '".formatMySQLQuery($f["adresse"])."',
                                '".formatMySQLQuery($f["plz"])."',
                                '".formatMySQLQuery($f["ort"])."',
                                '".formatMySQLQuery($f["tel"])."',
                                '".formatMySQLQuery($f["bemerkungen"])."', 
                                '".$f["email"]."');";
      $result = mysql_query($query)
        or report_mysql_error();
      
      mail("$f[email]", "Beitritt Borromini Klub FC Aarberg",
          "Ihre Anmeldung ist erfolgreich entgegengenommen worden.\n\n"
          ."----------------------------\n"
          ."http://borromini.fcaarberg.ch",
          "From: no_reply@fcaarberg.ch\nReply-To: $heinz.nobs@fcaarberg.ch\nX-Mailer: PHP/" . phpversion());

      mail("heinz.nobs@fcaarberg.ch","Borromini - neue Anmeldung",
           "Name:        $f[name]\n"
          ."Vorname:     $f[vorname]\n"
          ."Adresse:     $f[adresse]\n"
          ."PLZ/Ort:     $f[plz] $f[ort]\n"
          ."eMail:       $f[email]\n"
          ."Bemerkungen: $f[bemerkungen]",
          "From: no_reply@fcaarberg.ch\nReply-To: $f[email]\nX-Mailer: PHP/" . phpversion());
	
      $message = "Ihre Borrominianmeldung wurde erfolgreich &uuml;bermittelt!<br/>
                  Die Anmeldung wird in K&uuml;rze per Email an $f[email] best&auml;tigt!<br/>
                  Sollten Sie in den n&auml;chsten Minuten keine Email erhalten, melden Sie sich<br/>
                  bitte bei <a href=\"mailto:heinz.nobs@fcaarberg.ch\">Heinz Nobs</a> - Besten Dank!";
    } 
    else {
      $message = "Ihre Anmeldung wurde nicht komplett ausgef&uuml;llt und deshalb <u>nicht</u> registriert!<br>Bitte f&uuml;llen Sie mindestens alle Formularfelder mit einem * aus.";
    }
    echo "<div align=\"center\"><table border=\"1\"><tr><td>$message</td></tr></table></div>";
  }
  
  function anmeldung() { 
?>

  <h2 align="center">Anmeldung</h2>
  <div align="center">
    F&uuml;lle bitte folgende Felder aus, um dem Borromini Klub als G&ouml;nner beizutreten:
    <form method="post" action="<?php echo "$PHP_SELF"; ?>" name="anmeldungsForm">
      <input type="hidden" name="action" value="anmelden"/>
      
      <table>
        <TR>
          <TD>Name*</TD>
          <TD><INPUT TYPE="text" NAME="fields[name]" MAXLENGTH=50 SIZE=40></TD>
        </TR><TR>
        <TR>
          <TD>Vorname*</TD>
          <TD><INPUT TYPE="text" NAME="fields[vorname]" MAXLENGTH=50 SIZE=40></TD>
        </TR><TR>
          <TD>Adresse*</TD>
          <TD><INPUT TYPE="text" NAME="fields[adresse]" MAXLENGTH=50 SIZE=40></TD>
        </TR><TR>
          <TD>PLZ*</TD>
          <TD><INPUT TYPE="text" NAME="fields[plz]" MAXLENGTH=5 SIZE=10></TD>
        </TR><TR>
          <TD>Ort*</TD>
          <TD><INPUT TYPE="text" NAME="fields[ort]" MAXLENGTH=50 SIZE=40></TD>
        </TR><TR>
          <TD>Telefon</TD>
          <TD><INPUT TYPE="text" NAME="fields[tel]" MAXLENGTH=20 SIZE=40></TD>
        </TR><TR>
          <TD>Email*</TD>
          <TD><INPUT TYPE="text" NAME="fields[email]" MAXLENGTH=50 SIZE=40></TD>
        </TR><TR>
        <TD>Bemerkungen</TD>
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
  
<?php
  if (0 == strcmp($action, "anmelden")) {
    addAnmeldung($db, $fields);
  } else if (0 == strcmp($action, "anmeldung")) {
    anmeldung();
  } else if (0 == strcmp($action, "vorstand")) {
    printVorstand($db);
  } else {
?>
    <h1>Borromini Klub FC Aarberg</h1>
    Der Borromini Klub des FC Aarberg ist eine vom Verein getrennte und unabh&auml;ngig gef&uuml;hrte Vereinigung von G&ouml;nnern, die sich verpflichtet, den Fussballclub Aarberg finanziell zu unterst&uuml;tzen.
    <p>
    Der j&auml;hrlich zu entrichtende G&ouml;nnerbeitrag betr&auml;gt mindestens 100 Franken. Daf&uuml;r hat jedes Mitglied des Borromini Klubs unter anderem das Recht (Auszug aus den Statuten):
    <ul>
    <li>an allen vom Borromini Klub organisierten sportlichen und geselligen Anl&auml;ssen teilzunehmen</li>
    <li>auf einen Borromini Klub Mitgliederausweis</li>
    <li>in den Rechten und Pflichten einem Supporter des FC Aarberg gleichgestellt zu werden,<br>d.h. <b>Gratis-Eintritt</b> zu allen vom FC Aarberg organisierten Veranstaltungen, Meisterschafts- und Trainingsspielen</li>
    <li>durch den FC Aarberg periodisch &uuml;ber das Vereinsgeschehen orientiert zu werden (Erhalt FCA Nachrichten)</li>
    </ul>
    <p>
    Interessierte k&ouml;nnen sich gleich <a href="http://www.fcaarberg.ch/borromini/index.php?action=anmeldung">online anmelden</a> oder <a href="http://www.fcaarberg.ch/doc/borromini_beitrittserklaerung.pdf">das Beitrittsformular</a> herunterladen.
<?php
  }
  
  endContent();
  // Left Block content ...
  startLeftBlock();
?>
<h2 id="h_borromini">Borromini Klub</h2>
<ul>

<li><a href="http://www.fcaarberg.ch/doc/borromini_statuten.pdf"><b>Statuten</b></a></li>
<li><a href="http://www.fcaarberg.ch/borromini/index.php?action=vorstand"><b>Vorstand</b></a></li>
<li><a href="http://www.fcaarberg.ch/borromini/index.php?action=anmeldung"><b>Anmeldeformular</b></a></li>
</ul>
<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>  

