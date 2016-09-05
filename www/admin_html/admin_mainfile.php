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
 
/*
modification history
--------------------
01a,12jan10,pz  remove inactive addresses
*/

  include("/home/fcaarberg/public_html/mainfile.php");
  
  /**
   * 
   */
  function adminSecureInformation($login, $userLevel, $ID)
  {
    if ($login == "") $login = "[kein]";
    if ($userLevel == "") $userLevel = "0";
    echo "<h2 id=\"h_secphp\">Benutzer</h2>";
    echo "Angemeldeter Benutzer: <b>$login</b><br />";
    echo "Berechtigungs Level: $userLevel<br /><br />";
    echo "<a href=\"logout.php\">[Abmelden]</a>";  
  }
  
  /**
   * 
   */
  function adminNavigation($userLevel) 
  {
    if ($userLevel == "") $userLevel = "0";
    $navAdr = "<li><a href=\"admin_adressen.php\">Adressen</a></li>";
    $navAnl = "<li><a href=\"admin_anlaesse.php\">Anlaesse</a></li>";
    $navBor = "<li><a href=\"admin_borromini.php\">Borromini</a></li>";
    $navBen = "<li><a href=\"admin_users.php\">Benutzer</a></li>";
    $navDat = "<li><a href=\"admin_upload.php\">Dateien</a></li>";
    $navDok = "<li><a href=\"admin_dokumente.php\">Dokumente</a></li>";
    $navFun = "<li><a href=\"admin_funktionaere.php\">Funktion&auml;re</a></li>";
    $navLin = "<li><a href=\"admin_links.php\">Links</a></li>";
    $navGru = "<li><a href=\"admin_gruempel.php\">Gr&uuml;mpelturnier</a></li>";
    $navGOk = "<li><a href=\"admin_gruempel_ok.php\">Gr&uuml;mpelturnier OK</a></li>";
    $navMan = "<li><a href=\"admin_mannschaften.php\">Mannschaften</a></li>";
    $navMat = "<li><a href=\"admin_matchbaelle.php\">Matchballspender</a></li>";
    $navMit = "<li><a href=\"admin_news.php\">Mitteilungen</a></li>";
    $navRes = "<li><a href=\"admin_resultate.php\">Resultate</a></li>";
    $navSpi = "<li><a href=\"admin_spiele.php\">Spiele</a></li>";
    $navBer = "<li><a href=\"admin_spielberichte.php\">Spielberichte</a></li>";
    $navSpk = "<li><a href=\"admin_spiko.php\">Spiko</a></li>";
    $navTur = "<li><a href=\"admin_juniorenturniere.php\">Spielpl&auml;ne Juniorenturniere</a></li>";
    $navVor = "<li><a href=\"admin_vorstand.php\">Vorstand</a></li>";
  
    echo "<h2 id=\"h_admin\">Administration</h2>";
    echo "<ul>";
    switch ($userLevel)
    {
      case 1:
        echo $navAnl.$navGru.$navGOk;
        break;
      case 2:
        echo $navRes.$navBer; break;
      case 3:
        echo $navMat; break;
      case 4:
        echo $navAdr.$navAnl; break;
      case 5:
        echo $navAdr.$navAnl.$navMat; break;
      case 6:
        echo $navAdr.$navMit.$navLin.$navAnl.$navBer.$navDok.$navBor; break;
      case 7:
        echo $navAdr.$navSpi.$navTur.$navRes.$navTur.$navMan.$navMit.$navAnl.$navMat.$navBer.$navDok.$navDat.$navBor; 
        break;
      case 8:
      case 9:
        echo ""; break;
      case 10:
        echo $navAdr.$navVor.$navFun.$navSpi.$navRes.$navSpk.$navTur.$navMan.$navBen.$navMit.$navLin.$navAnl;
        echo $navBor.$navMat.$navBer.$navGru.$navGOk.$navDok.$navDat;
        break;
      default:
        echo "<li>No user rights!</li>";
        break;
    }
    echo "</ul>";
  }
  
  /**
   * 
   */
  function printAddressDropDownList($database, $name, $pre=0)
  {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT id,name,vorname FROM Adressen WHERE aktiv = 'J' ORDER BY name,vorname ASC;")
      or report_error(__FILE__,__LINE__,"Invalid query");

    echo "<select name=$name>\n";
    echo "<option value=0>--</option>\n";
    while ($row = mysql_fetch_array($result))
    {
      echo "<option value=".$row["id"];
      if ($pre == $row["id"]) echo " selected";
      echo replaceUml(">".$row["name"]." ".$row["vorname"]."</option>\n");
    }
    echo "</select>\n";
  }
  
?>
