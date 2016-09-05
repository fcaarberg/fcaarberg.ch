<?php
/******************************************************************************
 * Copyright 2011 Christoph Horber, FC Aarberg
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
 2012-07-11;ch;updated fvbj url
 2011-07-18;ch;initial version
*/
	
	$requiredUserLevel = array(10); // "Administrator"
	$cfgProgDir = 'phpSecurePages/';
	include($cfgProgDir."secure.php");
	
	include("admin_mainfile.php");
	
	function import($db, $url, $importGame = 0) {
		if (function_exists('curl_init')) {
			$gamelines = readFromUrl($url);
			
			$importCount = 0;
			// starting at line 1, line zero is csv head
			for ($i=1;$i<count($gamelines);$i++) {
				$game = explode(";", $gamelines[$i]);
				$spielnummer = getGameValue($game, 4);
				// import all games or only requested with matching spielnummer
				// echo "importgame compare (0 == $importGame): ".(0 == $importGame)." , (0 == strcmp($importGame, $spielnummer)): ".(0 == strcmp($importGame, $spielnummer));
				if (0 == $importGame || 0 == strcmp($importGame, $spielnummer)) {
					$spieltyp = getSpieltyp($game);
					$status = getGameValue($game, 2); // verschoben or ""
					$tag = getGameValue($game, 5); // mo, di, mi, do, fr, sa, so
					$datum = getGameValue($game, 6); // format: dd.mm.yyyy
				
					$zeit = getGameValue($game, 7); // format HH:mi
					$homeAway = getHomeAway($game);
					if (0 != strcmp($homeAway, "")) {
						$teamname = getTeamName($game, $homeAway);
						$teamliga = getLiga($game, $homeAway); // 2., 3., 5.
						$team = getTeam($teamname, $teamliga);
						$gegner = getGegner($game, $homeAway);
				
						addGame($db, $team, $gegner, $homeAway, $spieltyp, $spielnummer, $datum, $zeit, $status);
						$importCount++;
					}
				}
			}
			if (0 == $importGame) {
				echo $importCount." Spiele importiert!";
			} else if ($importCount > 0) {
				echo "Spiel $importGame importiert!";
			} else {
				echo "Keine Spiele importiert!";
			}
		} else {
			echo "curl php function not supported on this host!";
		}
	}
	
	function printFootballGames($db, $url, $diff = false) {
		if (function_exists('curl_init')) {
			$gamelines = readFromUrl($url);
			
			echo "<table><tr class=\"second\"><th>Datum</th>";
			echo "<th>Zeit</th>";
			echo "<th>Spielnummer</th>";
			echo "<th>Mannschaft</th>";
			echo "<th>Gegner</th>";
			echo "<th>Ort</th>";
			echo "<th>Art</th>";
			echo "</tr>";
			
			// starting at line 1, line zero is csv head
			for ($i=1;$i<count($gamelines);$i++) {
				$game = explode(";", $gamelines[$i]);
				$spieltyp = getSpieltyp($game);
				$status = getGameValue($game, 2); // verschoben or ""
				$spielnummer = getGameValue($game, 4);
				$tag = getGameValue($game, 5); // mo, di, mi, do, fr, sa, so
				$datum = getGameValue($game, 6); // format: dd.mm.yyyy
	
				$zeit = getGameValue($game, 7); // format HH:mi
				$homeAway = getHomeAway($game);
				if (0 != strcmp($homeAway, "")) {
					$teamname = getTeamName($game, $homeAway);
					$teamliga = getLiga($game, $homeAway); // 2., 3., 5.
					$team = getTeam($teamname, $teamliga);
					$gegner = getGegner($game, $homeAway);
					$gameVerband = createGame($spielnummer, $team, $gegner, $spieltyp, $datum, $zeit, getSaison(today()), $homeAway, '');

					$gameFCA = readExistingGame($db, $spielnummer);

					$gameError = 0; // 0=ok, 1=import, 2=update, 3=info
					if (count($gameFCA) > 0) { 
						//$d = isDifferent($eGame[1], formatDateUS($datum), str_replace("00:00", "", $eGame[5]), $zeit);
						$d = compare($gameFCA, $gameVerband);
						if ($d == 1) {
							$class = "second-red";
							$gameError = 2;
						} else if ($d == -1) {
							$class = "second-blue";
							$gameError = 3;
						} else if ($i %2 == 1) {
							$class = "first";
						} else {
							$class = "second";
						}
					} else {
						$class = "second-blue";
						$gameError = 1;
					}

					// show only games with deviation if requested
					if (!($gameError == 0 && $diff)) {

						echo "<tr class=\"$class\" id=\"$spielnummer\">";
	
						printTd($gameFCA[1], formatDateUS($datum), formatDateUS($datum));
						printTd(str_replace("00:00", "", $gameFCA[5]), $zeit, $zeit);
						
						echo "<td>".$spielnummer."</td>";
						if (strlen($team) == 2) {
							echo "<td>".$team." Liga</td>";
						} else {
							echo "<td>".$team."</td>";
						} 
						echo "<td>".$gegner."</td>";
						echo "<td>".$homeAway."</td>";
						echo "<td>".$spieltyp."</td>";
	
						if ($gameError == 1) {
							// game does not yet exists -> import
							echo "<td><a href=\"$PHP_SELF?action=insert&gamenr=$spielnummer\"><img alt=\"Importieren\" title=\"Importieren\" src=\"/images/insert.png\"></a></td>";
						} else if ($gameError == 2) {
							// game has differences between fca db and football.ch
							// use form with hidden fields
							echo "<td>";
							echo "<form method=\"post\" action=\"$PHP_SELF?action=update\" name=\"updateGame$spielnummer\">";
							echo "<input type=\"hidden\" name=\"spielnummer\" value=\"$spielnummer\"/>";
							echo "<input type=\"hidden\" name=\"datum\" value=\"$datum\"/>";
							echo "<input type=\"hidden\" name=\"time\" value=\"$zeit\"/>";
							echo "<input type=\"hidden\" name=\"homeAway\" value=\"$homeAway\"/>";
							echo "<input type=\"hidden\" name=\"gegner\" value=\"$gegner\"/>";
							echo "<input type=\"image\" alt=\"Update\" src=\"/images/update.png\">";
							echo "</form>";
						}
						echo "</tr>\n";
					}
				}				
			}
			
			echo "</table>";
			if (!$diff) {
				echo "<a href=\"$PHP_SELF?action=import\">Import all</a> (Achtung: bereits bestehende Spiele werden nochmals importiert!)";
			}
		} else {
			echo "curl php function not supported on this host!";
		}
	}
	
	function isDifferent($eDate, $date, $eTime, $time) {
		$d = printTd($eDate, $date, $date, false);
		if ($d != 0) {
			return $d;
		} else {
			$t = printTd($eTime, $time, $time, false);
			return $t;
		}
	}
	
	function compare($gameFCA, $gameVerband) {
		//$d = isDifferent($eGame[1], formatDateUS($datum), str_replace("00:00", "", $eGame[5]), $zeit);
		$d = isDifferent($gameFCA[1], formatDateUS($gameVerband->datum), str_replace("00:00", "", $gameFCA[5]), $gameVerband->zeit);
		if ($d == 0) {
			// compare home/away
			if (0 != strcmp($gameFCA[4], $gameVerband->homeAway)) {
			  $d = 1;
			  // compare gegner
			} else if (0 != strcmp(trim($gameFCA[6]), trim(replaceUml($gameVerband->gegner)))) {
				$d = 1;
			} else {
				// TODO
			}
		}
		return $d;
	}
	
	function printTd($arg1, $arg2, $value, $print=true) {
		$ret = 0;
		if (0 == strcmp(trim($arg1), $arg2)) {
			$color = "black";
			$val = $value;
		} else {
			$color = "red"; // not equals
			if (0 == strcmp($arg2, "")) {
				$val = $arg1."<br>".$arg2;
				$ret = -1;
			} else {
				$val = $arg2."<br>".$arg1;
				$ret = 1;
			}
		}
		if ($print) {
			echo "<td><font color='".$color."'>".$val."</font></td>";
		}
		return $ret;
	}
	
	function readFromUrl($url) {
		// initialize a new curl resource
		$ch = curl_init();
		
		// set the url to fetch
		curl_setopt($ch, CURLOPT_URL, $url);
		
		// don't give me the headers just the content
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		// return the value instead of printing the response to browser
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// use a user agent to mimic a browser
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7) AppleWebKit/534.48.3 (KHTML, like Gecko) Version/5.1 Safari/534.48.3');
		
		$content = curl_exec($ch);
		
		// remember to always close the session and free all resources
		curl_close($ch);
		
		return explode("\n", $content); //create array separate by new line
	}
	
	function readExistingGame($db, $spielnr) {
		$db_name = mysql_select_db("fcaarberg", $database);
		$query = "SELECT  Mannschaften.sfv_mannschaftsname,Spiele.*
		            FROM  Spiele, Mannschaften
		            WHERE Spiele.mannschaft = Mannschaften.id
		            AND   Spiele.spielnr = ".$spielnr."
		            AND   Spiele.saison = ".getSaison(today());
		$result = mysql_query($query)
		  or report_mysql_error(__FILE__,__LINE__);
		while ($row = mysql_fetch_array($result)) {
			$arr = array(
					$row["sfv_mannschaftsname"],
					$row["datum"],
					$row["spielnr"],
					$row["spielart"],
					$row["spielort"],
					substr($row["anspielzeit"],0, 5),
					replaceUml($row["gegner"]),
					$row["saison"],
					replaceUml($row["result"]));
			return $arr;
		}
		$a = array();
		return $a;
	}
	
	function getTeam($teamname, $teamliga) {
		if (0 == strcmp($teamname, "")) {
			return $teamliga;
		} else {
			return $teamliga.$teamname;
		}
	}
	
	function getHomeAway($game) {
		$vereinsNrHome = getGameValue($game, 9);
		$vereinsNrAway = getGameValue($game, 12);
	
		if (0 == strcmp($vereinsNrHome, "10201")) {
			return "H";
		} else if (0 == strcmp($vereinsNrAway, "10201")) {
			return "A";
		} else {
			return ""; // not an FCA game
		}
	}
	
	function getGegner($game, $homeAway) {
		if (0 == strcmp($homeAway, "H")) {
			return getGameValue($game, 11);
		} else if (0 == strcmp($homeAway, "A")) {
			return getGameValue($game, 8);
		}
	}
	
	function getTeamName($game, $homeAway) {
		if (0 == strcmp($homeAway, "H")) {
			$name = getGameValue($game, 8);
			return trim(str_replace("FC Aarberg", "", $name));
		} else if (0 == strcmp($homeAway, "A")) {
			$name = getGameValue($game, 11);
			return trim(str_replace("FC Aarberg", "", $name));
		}
	}
	
	function getLiga($game, $homeAway) {
		if (0 == strcmp($homeAway, "H")) {
			return getGameValue($game, 10);
		} else if (0 == strcmp($homeAway, "A")) {
			return getGameValue($game, 13);
		}
	}
	
	function getGameValue($game, $idx) {
		return str_replace("\"", "", $game[$idx]);
	}
	
	function getSpieltyp($game) {
		$spieltyp = getGameValue($game, 0); // (Meisterschaft, Cup, Trainingsspiele)
		if (0 == strcmp($spieltyp,"Meisterschaft")) {
			return "M";
		} else if (0 == strcmp($spieltyp,"Cup")) {
			return "C";
		} else if (0 == strcmp($spieltyp,"Trainingsspiele")) {
			return "F";
		} else {
			return "M";
		}
	}
	
	function updateGame($database, $spielnummer, $datum, $zeit, $ort, $gegner) {
		$db_name = mysql_select_db("fcaarberg", $database);		
		$query = "UPDATE spiele
				SET datum = '".formatDateUS($datum)."',
				anspielzeit = '".$zeit."',
				spielort = '".$ort."',
				gegner = '".$gegner."'
				WHERE spielnr = '".$spielnummer."'
				AND saison = '".getSaison($datum)."';";

		debug_echo($query);
		$result = mysql_query($query)
		  or report_mysql_error(__FILE__,__LINE__);
		  
		echo "Spiel $spielnummer aktualisiert!";
	}
	
	function addGame($database, $mannschaft, $gegner, $spielort, $spielart, $spielnr, $datum, $anspielzeit, $status) {
		$db_name = mysql_select_db("fcaarberg", $database);
		$query = "INSERT INTO Spiele (mannschaft,garderobe,gegner,spielort,klubhaus,spielart,spielnr,datum,saison,anspielzeit,resultat)
	                     VALUES ((SELECT id FROM mannschaften WHERE SFV_MANNSCHAFTSNAME = '".$mannschaft."'),
	                              '0',
	                              '".trim(replaceUml($gegner))."',
	                              '".$spielort."',
	                              'J',
	                              '".$spielart."',
	                              '".$spielnr."',
	                              '".formatDateUS($datum)."',
	                              '".getSaison($datum)."',
	                              '".$anspielzeit."',
				      '".replaceUml($status)."');";
		debug_echo($query);
		$result = mysql_query($query)
		  or report_mysql_error(__FILE__,__LINE__);
	}
	
	function createGame($spielnummer, $team, $gegner, $spieltyp, $datum, $zeit, $saison, $homeAway, $result) {
		$game = new Game();
		$game->setNr($spielnummer);
		$game->setTeam($team);
		$game->setGegner($gegner);
		$game->setArt($spieltyp);
		$game->setDatum($datum);
		$game->setZeit($zeit);
		$game->setSaison($saison);
		$game->setHomeAway($homeAway);
		$game->setResult($result);
		return $game;
	}
	
	class Game {
		public $nr = '';
		public $datum = '';
		public $zeit = '';
		public $team = '';
		public $gegner = '';
		public $ort = '';
		public $art = '';
		public $saison = '';
		public $homeAway = '';
		public $result = '';
		
		public function setNr($spielnummer) {
			$this->nr = $spielnummer;
		}
		public function setTeam($team) {
			$this->team = $team;
		}		
		public function setGegner($gegner) {
			$this->gegner = $gegner;
		}
		public function setArt($art) {
			$this->art = $art;
		}
		public function setDatum($datum) {
			$this->datum = $datum;
		}
		public function setZeit($zeit) {
			$this->zeit = $zeit;
		}
		public function setSaison($saison) {
			$this->saison = $saison;
		}
		public function setHomeAway($homeAway) {
			$this->homeAway = $homeAway;
		}
		public function setResult($result) {
			$this->result = $result;
		}
		
		function printGame() {
			echo 'printGame';
			echo $this->nr.": ".$this->datum;
		}
	}
	
	init();
	$db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
	
	openDocument();     // open a new document (DOCTYPE)
	fcaHead();          // write head data (meta tags, title, ...)
?>
	<style type="text/css">
	 .gameHidden {
	 	visibility:hidden;
	 }
	 .gameShow {
	 	visibility:visible;
	}
	</style>
<?php
	openBody(__FILE__); // start body tag
	
	// Main content (middle) ...
	startContent();
?>
	
	<h1>Abgleich football.ch</h1>
	<div align="center">

<?php
	// old url $url = "http://www.football.ch/nis/verein/vereinSpielplanExport.aspx?v=1282&sp=1&nm=Vereinsspielplan&ac=All";
	$url = "http://www.football.ch/fvbj/portaldata/1/nis/verein/vereinSpielplanExport.aspx?v=1282&sp=1&nm=Vereinsspielplan&ac=All";
    
	if (0 == strcmp($action, "import")) {
		import($db, $url); // import all
	} else if (0 == strcmp($action, "insert")) {
		import($db, $url, $gamenr);
		echo "<p><a href=\"admin_football.ch.php\">zur&uuml;ck</a>";
	} else if (0 == strcmp($action, "update")) {
		echo updateGame($db, $spielnummer, $datum, $time, $homeAway, $gegner);
		echo "<p><a href=\"admin_football.ch.php\">zur&uuml;ck</a>";
	} else if (0 == strcmp($action, "show")) {
		if (0 == strcmp($showDiff, "true")) {
			printFootballGames($db, $url, 1);
		} else {
			printFootballGames($db, $url);
		}
	} else {
		printFootballGames($db, $url);
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
	<h2 id="t_help">Info</h2>
	Datenabgleich mit football.ch ist nicht vollst&auml;ndig implementiert!
	
	<h2 id="t_options">Optionen</h2>
	<form method="post" action="admin_football.ch?action=show">
		Nur Abweichungen
		<?php
			if (0 == strcmp($showDiff, "true")) {
				echo "<input type=\"checkbox\" name=\"showDiff\" value=\"true\" checked=\"checked\"/>";
			} else {
				echo "<input type=\"checkbox\" name=\"showDiff\" value=\"true\"/>";
			}
		?>
		<input type="submit" value="neu laden"/> 
	</form>
<?php
	endRightBlock();
	closeBody();
	closeDocument();
?>
