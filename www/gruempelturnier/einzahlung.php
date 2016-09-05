<?php
  //*** include mainfile ***//
  include("/home/fcaarberg/public_html/mainfile.php");
  
  function thisYear() {
    return strftime("%Y", time());
  }

  init();
  $db = mysql_connect(DB_HOST, DB_USER, DB_PWD);
  $db_name = mysql_select_db("fcaarberg", $db);
  $result = mysql_query(" SELECT    *
                          FROM      gruempel_infos
                          WHERE     jahr=".thisYear()."
                          LIMIT     1;")
    or die ("Invalid query");
  $row = mysql_fetch_array($result);

  openDocument();
  fcaHead();
  openBody(__FILE__);
  // Main content (middle) ...
  startContent();
?>

<h1 align="center">Gr&uuml;mpelturnier <?php echo thisYear(); ?></h1> 
    
  <H2 align=center>Einzahlung</H2>
  <P align=center>
    Der Einsatz ist mit einem Einzahlungsschein, der wie folgt ausgef&uuml;llt 
    ist, bis am <b><?php echo formatDateCH($row["datum_einzahlung"], true); ?></b> einzuzahlen!
  </P>
<div align="center">

  <TABLE BORDER=0 BGCOLOR="#FFCCCC" WIDTH="500" CELLSPACING=0 CELLPADDING=0 align=center>
    <TR>
      <TD ROWSPAN=12 BGCOLOR="#000000"><FONT SIZE="-1"><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></FONT>
      </TD>
  <TD COLSPAN=8 BGCOLOR="#000000"><FONT SIZE="-1"><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></FONT></TD>
  <TD ROWSPAN=12 BGCOLOR="#000000"><FONT SIZE="-1"><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></FONT></TD>
</TR>

<TR>
  <TD COLSPAN=8>
    <TABLE BORDER=0 WIDTH="100%" CELLSPACING=0 CELLPADDING=0>
    <TR>
      <TD ALIGN=CENTER><FONT SIZE="-1" COLOR="#CC6666">+Einzahlung Giro PTT+</FONT></TD>
      <TD ALIGN=CENTER><FONT SIZE="-1" COLOR="#CC6666">+Versement Virement PTT+</FONT></TD>
      <TD ALIGN=CENTER><FONT SIZE="-1" COLOR="#CC6666">+Versamento Girata PTT+</FONT></TD>
    </TR>
    </TABLE>
  </TD>
</TR>

<TR>
  <TD COLSPAN=8 BGCOLOR="#000000"><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></TD>
</TR>

<TR>
  <TD WIDTH="1%">&nbsp;</TD>  
  <TD COLSPAN=2 WIDTH="45%"><FONT SIZE="-1" COLOR="#CC6666">
 &nbsp;Einzahlung f&uuml;r
  </FONT></TD>
  <TD WIDTH="50%">&nbsp;</TD>
  <TD WIDTH="1%" BGCOLOR="#000000" ROWSPAN=2><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></TD>
  <TD WIDTH="1%"><FONT SIZE="-1" COLOR="#CC6666">
    &nbsp;Zahlungszweck&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
  <TD WIDTH="1%" BGCOLOR="#000000" ROWSPAN=2><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></TD>
  <TD WIDTH="1%" ROWSPAN=2>
 <IMG SRC="images/stampRed.gif"  ALT="Stempel"   ALIGN=MIDDLE >
  </TD>
</TR>

<TR>
  <TD WIDTH="1%">&nbsp;</TD>  
  <TD COLSPAN=2><TABLE  CELLPADDING=0 WIDTH="100%"   BORDER=0 >
<TR><TD><FONT SIZE="-1"><FONT SIZE="-1">&nbsp;</FONT></FONT>Berner Kantonalbank,</TD>
</TR>
<TR><TD><FONT SIZE="-1"><FONT SIZE="-1">&nbsp;</FONT></FONT>3001 Bern</TD>
</TR>
<TR><TD><FONT SIZE="-1"><FONT SIZE="-1">&nbsp;</FONT></FONT></TD>
</TR>
<TR><TD><FONT SIZE="-1"><FONT SIZE="-1">&nbsp;</FONT></FONT></TD>
</TR>
</TABLE></TD>
  <TD WIDTH="50%">&nbsp;</TD>
  <TD>&nbsp;Mannschaftsname:<BR>
      &nbsp;Kategorie:<BR>
      &nbsp;Tel-Nr. Spielf&uuml;hrer:
  </TD>    
</TR>

<TR>
  <TD COLSPAN=4><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></TD>
  <TD BGCOLOR="#000000" COLSPAN=4><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></TD>
</TR>


<TR>
  <TD WIDTH="1%">&nbsp;</TD>  
  <TD COLSPAN=2><FONT SIZE="-1" COLOR="#CC6666">
    &nbsp;Zugunsten von
  </FONT></TD>
  <TD WIDTH="50%">&nbsp;</TD>
  <TD BGCOLOR="#000000" ROWSPAN=5><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></TD>
  <TD ROWSPAN=4 COLSPAN=3><FONT SIZE="-1">
    &nbsp;
  </FONT></TD>
</TR>

<TR>
  <TD WIDTH="1%"><FONT SIZE="4">&nbsp;</FONT></TD>  
  <TD COLSPAN=2>
    <TABLE BORDER=0 WIDTH="100%" CELLSPACING=0 CELLPADDING=0>
    <TR>
   <TD><FONT SIZE="-1">&nbsp;&nbsp;</FONT></TD>
   <TD><FONT SIZE="-1">&nbsp;&nbsp;&nbsp;</FONT></TD>
   <TD ALIGN="RIGHT"><FONT SIZE="-1">&nbsp;&nbsp;</FONT></TD>
 </TR>
 </TABLE>
  </TD>
  <TD WIDTH="50%">&nbsp;</TD>
</TR>

<TR>
  <TD WIDTH="1%">&nbsp;</TD>  
  <TD COLSPAN=2><TABLE  CELLPADDING=0 WIDTH="100%"   BORDER=0 >
<TR><TD>&nbsp;BEKB Lyss</TD>
</TR>
<TR><TD>&nbsp;42 3.148.687.35 79015</TD>
</TR>
<TR><TD>&nbsp;Fussballclub Aarberg</TD>
</TR>
<TR><TD><FONT SIZE="-1"><FONT SIZE="-1">&nbsp;</FONT></FONT></TD>
</TR>
</TABLE></TD>
  <TD WIDTH="50%">&nbsp;</TD>
</TR>

<TR>
  <TD WIDTH="1%"><FONT SIZE="4">&nbsp;</FONT></TD>  
  <TD><FONT SIZE="-1" COLOR="#CC6666">
 &nbsp;Konto
  </FONT></TD>
  <TD ALIGN="RIGHT">30-106-9<FONT SIZE="-1"> </FONT></TD>
  <TD WIDTH="50%">&nbsp;</TD>
</TR>

<TR>
  <TD WIDTH="1%"><FONT SIZE="4">&nbsp;</FONT></TD>  
  <TD><FONT SIZE="-1" COLOR="#CC6666">
 &nbsp;Betrag
  </FONT></TD>
  <TD VALIGN="TOP" ALIGN="RIGHT"><FONT SIZE="-1" COLOR="#CC6666">&nbsp;&nbsp;CHF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT>xx.--</TD>
  <TD WIDTH="50%">&nbsp;</TD>
  <TD COLSPAN=3><FONT SIZE="-1" COLOR="#CC6666">
 <NOBR>&nbsp;Einbezahlt von:&nbsp; </FONT>HANS MUSTER</TD>
</TR>

<TR>
  <TD COLSPAN=8 BGCOLOR="#000000"><FONT SIZE="-1"><IMG SRC="/images/null.gif" HEIGHT=1 WIDTH=1></FONT></TD>
</TR>
</TABLE>
<P align=center>
    IBAN: CH3400790042314868735
  </P>
</div>

<?php
  endContent();
  // Left Block content ...
  startLeftBlock();
?>

<h2 id="h_verein">Gr&uuml;mpelturnier</h2>
<ul>
<li><a href="index.php">Informationen</a></li> 
<!-- <li><a href="flyer.php">Flyer</a></li>  -->
<li><a href="index.php?action=ok">Organisation</a></li>
<!-- 
<li><a href="index.php?action=anmeldung">Anmeldung</a></li>
<li><a href="einzahlung.php">Einzahlung</a></li>
 -->
</ul>

<?php
  endRightBlock();
  closeBody();
  closeDocument();
?>  