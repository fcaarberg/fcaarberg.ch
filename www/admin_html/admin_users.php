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


  $requiredUserLevel = array(10); // "Administrator"
  $cfgProgDir = 'phpSecurePages/';
  $thisPage   = "admin_users.php";
  include($cfgProgDir."secure.php");

  include("admin_mainfile.php");

  /**
   * Gibt eine liste mit allen phpSecurePages-Benutzern aus. Jeder Record kann gel�scht werden.
   */
  function showUserList($database)
  {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("SELECT * FROM phpSP_users;")
      or die("Invalid query");

    echo "<table align=\"center\" border=0 cellpadding=5><!-- Results table headers --><tr><td></td>";
    echo "<th>user</th><th>password</th><th>userlevel</th></tr>";

    $i = 0;
    while ($row = mysql_fetch_array($result))
    {
      $id = $row["primary_key"];
      if ($i++ %2 == 1)
      {
          $class = "first";
      }
      else
      {
          $class = "second";
      }
      echo "<tr class=\"$class\">";
      // echo "<td><a href=\"$PHP_SELF?id=$id&action=edit\">Bearbeiten</a></td>"; // NOT IMPLEMENTED YET!
      echo "<td><a href=\"$PHP_SELF?id=$id&action=delete\">L&ouml;schen</a></td>";
      echo "<td>".replaceUml($row["user"])."</td>";
      // echo "<td>".replaceUml($row["password"])."</td>";
      echo "<td>******</td>";
      echo "<td>".$row["userlevel"]."</td></tr>";
    }
    echo "</table>";
    echo "<p align=\"center\"><a href=\"$PHP_SELF?action=new\">Neuer Benutzer anlegen</a></p>";
  }

  /**
   * der user mit dem primary_key $id wird unwiderruflich gel�scht.
   */
  function deleteUser($database, $id)
  {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query("DELETE FROM `phpSP_users` WHERE `primary_key` = '$id' LIMIT 1;")
      or die("Invalid query");
  }

  /**
   * 
   */
  function addUserForm($database)
  {
    $content = "<form method=\"post\" action=\"$PHP_SELF\" name=\"addUserForm\">"
      ."<input type=\"hidden\" name=\"action\" value=\"insert\"/>"
      ."<table align=\"center\" border=0><tr><th>Feld</th><th>Wert</th></tr>"
      ."<tr><td align=\"center\">Benutzer:</td><td>";
    echo replaceUml($content);
    printAddressDropDownList($database,"fields[user_addr]",$f["user_addr"]);

    echo "<tr><td align=\"center\">Passwort:</td>"
      ."<td><input type=\"text\" name=\"fields[password]\" value=\"\" size=\"32\" maxlength=\"32\"/></td></tr>"
      ."<tr><td align=\"center\">User Level:</td>"
      ."<td><input type=\"text\" name=\"fields[userlevel]\" value=\"\" size=\"4\" maxlength=\"4\"/></td></tr>"
      ."</td></tr>"
      ."</table><p align=\"center\"><input type=\"submit\" value=\"Sichern\"/></p>"
      ."</form>";
  }

  /**
   * 
   */  
  function getUserLogin($database, $userId)
  {
    $db_name = mysql_select_db("fcaarberg", $database);
    $result = mysql_query(" SELECT DISTINCT
                                  id,
                                  vorname,
                                  name
                          FROM    Adressen
                          WHERE   aktiv = 'J' AND id = ".$userId.";")
      or die ("Invalid query");
    
    $row = mysql_fetch_array($result);
    $userLogin = strtolower("$row[vorname].$row[name]");
    $userLogin = str_replace("�", "ae", $userLogin);
    $userLogin = str_replace("�", "oe", $userLogin);
    $userLogin = str_replace("�", "ue", $userLogin);

    return $userLogin;
  }

  /**
   * 
   */
  function addUser($database,$f)
  {
    $user = getUserLogin($database,$f["user_addr"]);
    $db_name = mysql_select_db("fcaarberg", $database);
    $query = "INSERT INTO phpSP_users (user,password,userlevel,user_addr)
                            VALUES ('".$user."','".$f["password"]."',".$f["userlevel"].",".$f["user_addr"].");";
    $result = mysql_query($query)
      or die("Invalid query");
  }

  init();
  $db = mysql_pconnect(DB_HOST, DB_USER, DB_PWD);

  if (0 == strcmp($action,"delete")) {
    deleteUser($db,$id);
  } else if (0 == strcmp($action,"insert")) {
    addUser($db,$fields);
  }

  openDocument();     // open a new document (DOCTYPE)
  fcaHead();          // write head data (meta tags, title, ...)
  openBody(__FILE__); // start body tag
  
  // Main content (middle) ...
  startContent();  
?>

  <h1>Benutzer Administration</h1>
  <div align="center">
  
<?php
  if (0 == strcmp($action, "edit")) {
    editUserForm($id);
  } else if (0 == strcmp($action, "new")) {
    addUserForm($db);
  } else {
    showUserList($db);
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
  <h2 id="t_filter">Hilfe</h2>
  <b><u>Benutzer</u></b>
  <div> Der Benutzername wird automatisch in der Form vorname.nachname erstellt!</div>
  
  <b><u>Passwort</u></b>
  <div>Das Passwort muss einmalig gesetzt werden und kann momentan nicht ge&auml;ndert werden.</div>
  
 <b><u>User Level</u></b>
 <ul>
  <li><b>[10]</b> Administrator / Voller Zugriff! </li>
  <li><b>[ 9]</b> Nicht verwendet</li>
  <li><b>[ 8]</b> Nicht verwendet</li>
  <li><b>[ 7]</b> Adressen, Anl&auml;sse, Mitteilungen, Mannschaften, Spiele, Turniere, Resultate, Spielberichte, Links, Dokumente, Dateien, Borromini</li>
  <li><b>[ 6]</b> Adressen, Anl&auml;sse, Mitteilungen, Spielberichte, Links, Dokumente, Borromini</li>
  <li><b>[ 5]</b> Adressen, Anl&auml;sse, Matchballspender</li>
  <li><b>[ 4]</b> Adressen (nur Borromini!), Anl&auml;sse</li>
  <li><b>[ 3]</b> Matchballspender</li>
  <li><b>[ 2]</b> Resultate, Spielberichte</li>
  <li><b>[ 1]</b> Anl&auml;sse, Gr&uuml;mpel</li>
 </ul>
 
<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>  
