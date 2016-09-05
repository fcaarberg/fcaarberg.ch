<?php
/******************************************************************************
 * Copyright 2002-2010 Patrick Zysset, FC Aarberg
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *****************************************************************************/
 
  /**
   * XHTML 1.0 Transitional Doctype
   */
  function openDocument() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<?php
  }
  
  function closeDocument() {
    echo "</html>";
  }
  
  function openBody($file = 0) {
    global $printview, $QUERY_STRING, $REQUEST_URI;
    if ($printview == 0) {
?>
<body id="wwwfcaarbergch">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr id="header">
      <td id="headerpath">
        <!-- <span class="invisible"><a href="#main" accesskey="2">Skip to Content</a> |</span> -->
        <!-- Ort: <a href="http://www.fcaarberg.ch/" accesskey="1">FCA Homepage</a> -->
	FC Aarberg
        <?php 
          /* $location = basename(dirname($file));
          echo " / <a href=\"http://www.fcaarberg.ch/$location/\">$location</a>"; */
        ?>
      </td>
      <td id="headerpath">- Aarolina News online </td>
      <td id="headermenu">
        <?php
          echo today()." - ";
        ?>
        <a href="http://iphone.fcaarberg.ch">IPHONE</a> |
        <?php
          $url_array = parse_url($REQUEST_URI);
          echo "<a href=\"$url_array[path]?printview=1&$url_array[query]\" target=\"_FCAPRINTVIEW\">Drucken</a>";
         ?>
         | <a href="http://www.fcaarberg.ch/sitemap/">Sitemap</a> | <a href="javascript:history.go(-1)">Zur&uuml;ck</a>
      </td>
    </tr>
    <tr id="logo">
      <td valign="top" background="http://www.fcaarberg.ch/images/logo_klein_3_fill.png" width="140">
        <img src="http://www.fcaarberg.ch/images/logo_klein_3.png" alt="kleines logo" height="100" width="140">
      </td>
      <td></td>
      <td id ="werbebanner">
      <?php
		  if (mktime(0,0,0,8,4,2013) > time()) {  // 04.08.2013, 00:00h ?>
		    <a href="http://gruempel.fcaarberg.ch" target="_blank">
           	  <img alt="images/werbung.jpg" src="http://www.fcaarberg.ch/images/banner/2013_gruempu-banner.jpg" border="0">
       		</a>
		  <?php
		  } else { ?>
		    <a href="mailto:<?php echo noSpamMail("info@fcaarberg.ch"); ?>"> 
              <img alt="images/werbung.jpg" src="http://www.fcaarberg.ch/images/banner_rauchfrei.png" border="0"> 
            </a>
		  <?php 
		  }
	  ?>
      </td>
    </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?php
  } else {
?>
  <table border="1" cellpadding="0" cellspacing="0" width="100%">
<?php
    }
  }
  
  function closeBody() {
    $ltime = localtime(time(), 1); // get system time
    // $monat = $ltime["tm_mon"] + 1;
    $jahr  = $ltime["tm_year"] + 1900;

    global $printview;
    if ($printview == 0) {
?>
      </td>
      <td valign="top" class="menuheader" height="0"></td>
    </tr>
    <tr>
      <td colspan="3">
        <div id="footer">
          &copy; 2002-<?php echo "$jahr";?> FC Aarberg, <a href="mailto:<?php echo noSpamMail("info@fcaarberg.ch"); ?>">info(at)fcaarberg.ch</a>, 
          <a href="https://twitter.com/fcaarberg" class="twitter-follow-button" data-show-count="false" data-lang="de">@fcaarberg folgen</a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
      </td>
    </tr>
  </table>
<?php
    } else {
?>
      </td>
      <td valign="top" class="menuheader" height="0"></td>
    </tr>
  </table>
  <div align="right">&copy; 2002-<?php echo "$jahr";?> FC Aarberg<br />Datum: <?php echo today(); ?></div>
<?php
    }
?>
      
</body>
<?php
  }
  
  function startContent() {
    global $printview;
    if ($printview == 0) {
?>
<tr>
  <td valign="top" class="menuheader" height="0"></td>
  <td id="contentcolumn" valign="top" rowspan="2" >
    <div id="contentheader">&nbsp;</div>
    <div class="invisible"><a href="#navigation" accesskey="5">Skip to Link Menu</a></div>
    <div id="content"><div style="width:100%;">
    <a name="main" />
<?php
    } else {
?>
<tr>
  <td valign="top" id="contentcolumn">
    <div id="content"><div style="width:100%;">
    <a name="main" />
    <table width="100%" border="0"><tr>
      <td><img src="http://www.fcaarberg.ch/images/logo_klein.jpg" width=35 height=50 border=0 /></td>
      <td>
        <div align="right">
          10201 SEFV<br />
          <strong>FC Aarberg</strong><br />
          <code>www.fcaarberg.ch</code>
        </div>
      </td>
    </tr></table>
    
<?php
    }
  }
  
  function endContent() {
    global $printview;
    if ($printview == 0) {
?>
  </td>
  <td valign="top" class="menuheader" height="0"></td>
</tr>
<?php
    } else {
?>
  </td>
</tr>
<?php
    }
  }
  
  function startLeftBlock() {
    global $printview;
    if ($printview == 0) {
?>
<tr>
<td valign="top" id="leftmenu" width="25%">
  <!-- Start Navigation -->
  <a name="navigation"></a>
  <h2 id="h_inform">Navigation</h2>
  <ul>
    <li><a href="http://www.fcaarberg.ch/">Startseite</a></li>
    <li><a href="http://www.fcaarberg.ch/news/">Mitteilungen</a></li>
    <li><a href="http://www.fcaarberg.ch/verein/">Verein</a></li>
    <li><a href="http://www.fcaarberg.ch/dokumente/">Dokumente</a></li>
    <li><a href="http://www.fcaarberg.ch/spielbetrieb/">Spielbetrieb</a></li>
    <li><a href="http://www.fcaarberg.ch/aarolina/">Sportplatz</a></li>
    <li><a href="http://gruempel.fcaarberg.ch">Gr&uuml;mpelturnier</a></li>
    <li><a href="http://borromini.fcaarberg.ch">Borromini</a></li>
    <?php
      if (strftime("%Y", time()) % 2 == 0) { // show sponsorenlauf link only in even year
      ?>
        <li><a href="http://sponsorenlauf.fcaarberg.ch">Sponsorenlauf</a></li>
      <?php
      }
    ?>
    <li><a href="http://galerie.fcaarberg.ch" target="_blank">Galerie</a></li>
    <li><a href="http://www.fcaarberg.ch/links/">Links</a></li>
    <!-- <li><a href="http://marangu.fcaarberg.ch/" target="_blank">Projekt Marangu</a></li> -->
  </ul>
  <!-- End Navigation -->
  <!-- <h2 id="h_search">Suche</h2> -->
  <?php /*google_suche();*/ ?>
<?php
    } else {
?>
      <!-- <div class="invisible">
<?php
    }
  }

  function endLeftBlock() {
    global $printview;
    if ($printview == 0) {
?>
</td>
<?php
    } else echo "</div>-->";
  }
  
  function startRightBlock($content) {
    global $printview;
    if ($printview == 0) {
?>
<td valign="top" id="rightmenu" width="25%">
<?php
    } else {
?>
<!-- <div class="invisible">
<?php
    }
  }
  
  function endRightBlock() {
    global $printview;
    if ($printview == 0) {
?>
</td>
</tr>
<?php
    } else echo "</div>-->";
  }
  
  /**
 *
 */
function separator() {
  echo "<!-- separator -->
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n
      <tr bgcolor=\"#000000\">\n
      <td><img src=\"/images/pixel.jpg\" alt=\"trennlinie\" width=\"1\" height=\"1\"></td>\n
      </tr>\n
    </table>\n";
}

/**
 *
 */
function empty_space() {
  echo "<!-- empty space -->
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
      <tr>
        <td height=\"30\">&nbsp;</td>
      </tr>
    </table>";
}

  /**
   * (X)HTML head Informationen
   */
  function fcaHead() {
    global $printview;
    if ($printview == 0) $stylesheet = "standard.css";
    else $stylesheet = "print.css";
?>
<head>
<title>[FCA] - Fussball Club Aarberg</title>

<meta name="verify-v1" content="pdYcvSgNtIxNnTtS1ejAwFRCKdKQlxsTXwcVGTK4hQw=" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Style-Type" content="text/css" />

<meta name="description" content="FC Aarberg Homepage, fcaarberg.ch" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="robots" content="all" />

<link rel="shortcut icon" href="http://www.fcaarberg.ch/images/fca_small.ico" />
<link rel="apple-touch-icon" href="http://www.fcaarberg.ch/images/apple-touch-icon.gif"/>

<link rel="stylesheet" media="screen" type="text/css" title="Standard: FCA Farben" 
      href="http://www.fcaarberg.ch/style/<?php echo $stylesheet; ?>" />

<?php include_once("analyticstracking.php") ?>      

</head>
<?php
  }

  function init($printview = 0) {
    if (0 != strcmp('DB_USER', "fcaarberg")) {
      session_start();
      define("FCA_URL", "http://www.fcaarberg.ch");
      define("DB_USER", "fcaarberg");
      define("DB_PWD",  "DwLMJ9W9");
      define("DB_HOST", "localhost");
      define("FCA_DEBUG", "0");
    }
  }
  
  function getLocation() {
    
  }

  function google_suche() {
    echo "<!-- Search Google -->
        <form method=GET action=\"http://www.google.com/custom\">
        <input type=text name=q size=16 maxlength=255 value=\"\">
        <input type=submit name=sa VALUE=\"suchen\">
        <input type=hidden name=cof VALUE=\"GIMP:black;T:black;LW:80;L:http://www.fcaarberg.ch/images/logo_klein.jpg;GFNT:black;LC:blue;LH:100;BGC:white;AH:center;GALT:black;AWFID:e05ded5b0d6073c6;\">
        <input type=hidden name=domains value=\"fcaarberg.ch\"><br />
        <input type=radio name=sitesearch value=\"\"> WWW durchsuchen<br />
        <input type=radio name=sitesearch value=\"fcaarberg.ch\" checked> fcaarberg.ch durchsuchen
        <br>
        </form>
      
    <!-- Search Google -->";
  }
  
  function team_link_list($database) {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT name,id FROM Mannschaften ORDER BY name ASC;")
      or report_mysql_error(__FILE__,__LINE__);

    echo "<ul>";
    while($row = mysql_fetch_array($result)) {
      echo "<li><a href=\"mannschaft.php?teamid=".$row["id"]."\">".$row["name"]."</a></li>";
    }
    echo "</ul>";
  }
  
  function printTeamDropDownList($database, $name, $pre=0, $disabled="N") {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT id,name FROM Mannschaften ORDER BY name ASC;")
      or report_error(__FILE__,__LINE__,"Invalid query");
    
    echo "<select name=$name";
    if (0 != strcmp($disabled,"N")) {
        echo " disabled=\"disabled\"";                  
    }
    echo ">\n";
      
    echo "<option value=0>--</option>\n";
    while ($row = mysql_fetch_array($result)) {
      echo "<option value=".$row["id"];
      if ($pre == $row["id"]) echo " selected";
      echo ">".$row["name"]."</option>\n";
    }
    echo "</select>\n";
  }
  
  /**
   * report_error(_FILE_,_LINE_,message);
   */
  function report_error($file, $line, $message) {
    echo "<p align=\"center\">Ein Fehler ist aufgetreten in <i>$file</i> in Zeile <i>$line</i>: <b>$message</b>.</p>";
  }

  function report_mysql_error($file = 0, $line = 0) {
    if ($file == 0) $file = __FILE__;
    if ($line == 0) $line = __LINE__;
    report_error($file, $line, mysql_error());
  }

  function debug_echo($msg) {
    if (FCA_DEBUG == 1) echo $msg;
  }

  //--- DATUMS FUNKTIONEN ------------------------------------------------------

  function today($day_offset = 0) {
    $myTime = time();
    $myTime += ($day_offset * 60 * 60 * 24);
    return strftime("%d.%m.%Y", $myTime);
  }

  /**
   * Gibt die Saison des Datums zurï¿½ck. Eine Fussballsaison dauert vom 1.7.20xx - 30.6.20xy!
   *
   * @param $date_str [IN] "dd.mm.YYYY"
   * @return saisonstart: 2 = saison 2002/2003,
   *                      3 = saison 2003/2004, usw.
   */
  function getSaison($date_str) {
    $parts = explode(".",$date_str,3);
    $saison = $parts[2] - 2000;
    if ($parts[1]<7) $saison--;
    return sprintf("%02d",$saison);
  }

  /**
   * @param $ch_date [IN] "dd.mm.YYYY"
   * @return "YYYYmmdd"
   */
  function getMySQLDate($date_str) {
    $parts = explode(".",$date_str,3);
    return sprintf("%04d%02d%02d",$parts[2],$parts[1],$parts[0]);
  }

  /**
   * @param $ch_date [IN] dd.mm.YYYY
   * @return YYYY-mm-dd
   */
  function formatDateUS($ch_date) {
    //list($tag, $monat, $jahr) = explode(".", $datum);
    //return sprintf("%04d-%02d-%02d", $jahr, $monat, $tag);
    $parts = explode(".",$ch_date,3);
    return strftime("%Y-%m-%d", mktime(0,0,0,$parts[1],$parts[0],$parts[2]));
  }

  /**
   * @param $ch_date [IN] YYYY-mm-dd
   * @return dd.mm.YYYY
   */
  function formatDateCH($us_date, $tag = FALSE) {
    list($year, $month, $day) = explode("-", $us_date, 3);
    $ret = sprintf("%02d.%02d.%04d", $day, $month, $year);
    if ($tag) return getDayCH(mktime(0,0,0,$month,$day,$year)).", $ret";
    else return $ret;
  }

  /**
   *
   */
  function getDayCH($timestamp) {
    $a = localtime($timestamp, 1);
    switch ($a["tm_wday"]) {
      case 0: return "So";
      case 1: return "Mo";
      case 2: return "Di";
      case 3: return "Mi";
      case 4: return "Do";
      case 5: return "Fr";
      case 6: return "Sa";
    }
    return "";
  }
  
  function getMonthCH($month) {
    switch ($month)
    {
      case 0: return "Januar";
      case 1: return "Februar";
      case 2: return "M&auml;rz";
      case 3: return "April";
      case 4: return "Mai";
      case 5: return "Juni";
      case 6: return "Juli";
      case 7: return "August";
      case 8: return "September";
      case 9: return "Oktober";
      case 10: return "November";
      case 11: return "Dezember";
    }
    return "Error!";
  }

  /**
   * 0 = Sonntag
   * 1 = Montag
   * 2 = Dienstag
   */
  function getDay($timestamp) {
    $a = localtime($timestamp, 1);
    return $a["tm_wday"];
  }

  function getYear($ch_date)
  {
  	list($day, $month, $year) = explode(".", $ch_date, 3);
    //$ret = sprintf("%04d", $year);
    return $year;
  }
 
  /**
   * Calculate age in years based on timestamp and reference timestamp
   * If the reference $now is set to 0, then current time is used
   *
   * @param int $timestamp
   * @param int $now
   * @return int
   */
  function calculateAge($timestamp = 0, $now = 0) {
    # default to current time when $now not given
    if ($now == 0)
    {
      $now = time();
	}
 
    # calculate differences between timestamp and current Y/m/d
    $yearDiff   = date("Y", $now) - date("Y", $timestamp);
    $monthDiff  = date("m", $now) - date("m", $timestamp);
    $dayDiff    = date("d", $now) - date("d", $timestamp);
 
    # check if we already had our birthday
    if ($monthDiff < 0)
        $yearDiff--;
    elseif (($monthDiff == 0) && ($dayDiff < 0))
        $yearDiff--;
 
    # set the result: age in years
    $result = intval($yearDiff);
 
    # deliver the result
    return $result;
  }

  function getAge($birthday)
  {
  	$parts = explode(".",$birthday,3);
	$birthdaytime = mktime(0,0,0,$parts[1],$parts[0],$parts[2]);

  	return calculateAge($birthdaytime);
  }

  function date_MySQL2shortCH($mysql_date, $tag = FALSE) {
    $parts =  explode("-",$mysql_date,3);
    $ts = mktime(0,0,0,$parts[1],$parts[2],$parts[0]);
    $ret = strftime("%d.%m.%y", $ts);
    if ($tag) return getDayCH($ts).", $ret";
    else return $ret;
  }

  function time_trimSeconds($time) {
    $parts = explode(":",$time,3);
    return $parts[0].":".$parts[1];
  }
  
  function replaceUml($str) {
    $trans = array ("ä"     =>  "&auml;",
                    "ö"     =>  "&ouml;",
                    "ü"     =>  "&uuml;",
                    "Ä"     =>  "&Auml;",
                    "Ö"     =>  "&Ouml;",
                    "Ü"     =>  "&Uuml;",
                    "é"     =>  "&eacute;",
                    "è"     =>  "&egrave;"  );
    return strtr($str,$trans);
  }
  
  /**
   * Print the before text if the current time is before the time argument
   * otherwise display the after text.
   *
   * @param $time must be a unix timestamp (use mktime)
   * @param $before any html tag or string value   
   * @param $after any html tag or string value
   */
  function showDependentOnTime($time, $beforeText, $afterText) 
  {
	if (time() > $time) { 
	  echo $afterText;
	} else {
	  echo $beforeText;
    }
  }
  
  function formatMySQLQuery($str) {
    $trans = array ( "'"  => "&acute;",
                     '"'  => "&quot;" );
    return strtr($str,$trans);
  }
  
  function noSpamMail($adr) {
    for ($i=0; $i<strlen($adr); $i++) {
      $str .= "&#".ord($adr[$i]).";";
    }
    return $str;
  }
  
  //--- ADRESS FUNKTIONEN ------------------------------------------------------

  function createEmailAlias($vorname, $nachname, $nospam = true) {
    $alias = strtolower($vorname).".".strtolower($nachname)."@fcaarberg.ch";
    $trans = array ("é"     =>  "e",
                    "è"     =>  "e",
                    "à"     =>  "a",
                    "ä"     =>  "ae",
                    "ö"     =>  "oe",
                    "ü"     =>  "ue",
                    " "     =>  "",
                    "-"     =>  "");
	$alias = strtr($alias, $trans);
	if ($nospam) {
	    return noSpamMail($alias);
	} else {
		return $alias;
	}
  }
  
function show_address($database, $addrid, $contacts = 1, $link = 1, $address = 1) {
  $db_name = mysql_select_db("fcaarberg", $database);
  $result = mysql_query(" SELECT DISTINCT
                                  id,
                                  vorname,
                                  name,
                                  adresse,
                                  plz,
                                  ort,
                                  tel_n,
                                  tel_p,
                                  tel_g,
                                  emailalias
                          FROM    Adressen
                          WHERE   id = ".$addrid.";")
    or die ("Invalid query");
  $row = mysql_fetch_array($result);
  $addr = "<p>";
  if ($link == 1)
  {
  	$addr .= "<a href=\"http://www.fcaarberg.ch/verein/portrait.php?id=$addrid\">";
  }
  $addr .= "<b>".$row["name"]." ".$row["vorname"]."</b>";
  if ($link == 1)
  {
  	$addr .= "</a>";
  }
  if ($address == 1)
  {
  	$addr .= "<br />".$row["adresse"]."<br />".$row["plz"]." ".$row["ort"];
  }
  echo replaceUml($addr);

  if (0 == strcmp($row["emailalias"],"J")) {
    $alias = createEmailAlias($row["vorname"], $row["name"]);
    echo "</p><p><a href=\"mailto:$alias\">$alias</a>";
  }

  if ($contacts == 1)
  {
    $x = 0;
    $tel = "<br /><br /><i>";
    if ($row["tel_p"] != "") { $tel .= "Tel P $row[tel_p]<br />"; $x++; }
    if ($row["tel_g"] != "") { $tel .= "Tel G $row[tel_g]<br />"; $x++; }
    if ($row["tel_n"] != "") { $tel .= "Natel $row[tel_n]<br />"; $x++; }
    $tel .= "</i>";
    if ($x > 0) echo $tel;
  }
  echo "</p>";
}

function printDropDownList($values, $name, $pre=0, $nulloption=1, $disabled="N") {
  reset ($values);
  echo "<select name=$name";
  if (0 != strcmp($disabled,"N")) {
     echo " disabled=\"disabled\"";                  
  }
  echo ">\n";
  if ($nulloption != 0)
    echo "<option value=0>--</option>\n";
  while (list($val, $row) = each($values)) {
    echo "<option value=$row";
    if (strcmp($pre,$row) == 0) echo " selected";
    echo ">$row</option>\n";
  }
  echo "</select>\n";
  reset ($values);
}

function printCheckbox($name, $value, $checked) {
  echo "<input type=\"checkbox\" name=\"$name\" value=\"$value\"";
  if ($checked) echo " checked";
  echo ">\n";
}

function printYesNo($name, $pre = "N", $disabled = "N") {
  echo "<input type=\"radio\" name=\"$name\" value=\"J\"";
  if (0 == strcmp($pre,"J"))
    echo " checked ";
  if (0 != strcmp($disabled,"N"))
    echo " DISABLED ";
                        
  echo "/><label>J</label>
        <input type=\"radio\" name=\"$name\" value=\"N\"";
  if (0 == strcmp($pre,"N"))
    echo " checked ";
  if (0 != strcmp($disabled,"N"))
    echo " DISABLED ";
  echo "/><label>N</label>";
}
?>
