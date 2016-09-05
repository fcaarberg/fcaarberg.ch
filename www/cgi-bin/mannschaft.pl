#!/usr/bin/perl

# Homepage www.fcaarberg.ch
# Author patrick.zysset@fcaarberg.ch

# generiert eine mannschaftsuebersicht anhand eines textfiles.

require "nplib.pl";

#--- defines --------------------------------------------------------------

$path = "../mannschaften/";

#--- variables ------------------------------------------------------------

%mannschaft;  # mannschafts infos
%adressen;    # adressen
%trainer;     # trainer
%dress;       # dress infos
%verband;     # verbands infos
@training;    # trainings infos
$hash;        # art der info
$file;        # zu oeffnende datei (wird als cgi parameter uebergeben)

#--- main ----------------------------------------------------------------

# get the cgi parameter

if ($ENV{'REQUEST_METHOD'} eq 'GET') {
  $file = $ENV{'QUERY_STRING'}; # GET method
} else {
  read(STDIN, $file, $ENV{'CONTENT_LENGTH'}); # POST method
}

$fileindex = rindex($file, '?');
if ($fileindex >= 0) {
  $file = substr($file, $fileindex, length($file));
}

# open the file
open(TEAM, $path.$file);

# read the whole text file...

while($line = <TEAM>) {
  if (not($line =~ m/\A\s*\#.*/)) { # if not a comment line
    if ($line =~ m/\[(.*)\]/i) { 
      $hash = lc($1);
      if ($hash eq "training") {
	# push new training
      } elsif (not($hash eq "verband" or $hash eq "trainer" or $hash eq "dress" or $hash eq "mannschaft")) {
      	$adressen{$hash} = (\%{$hash});
      }
    } # hash = [xxx]
    elsif ($line =~ m/(\w*)\=(.*)/) { # if value-key pair
      if ($hash eq "verband") {
	$verband{$1} = $2;
      } elsif ($hash eq "dress") {
	$dress{$1} = $2;
      } elsif ($hash eq "trainer") {
	$trainer{$1} = $2;
      } elsif ($hash eq "training") {
	#set training attribute
      } elsif ($hash eq "mannschaft") {
	$mannschaft{$1} = $2;
      } else {
	${$adressen{$hash}}{$1} = $2;
      }
    }
  }
}

close(TEAM);


# write output

#print keys(%{$adressen{"jaeger"}}); #test

print header();
print printComment("start of dynamic content");
print "<h2>".$verband{"name"}."</h2>\n"; # mannschaftsname

print "<table width=\"100%\" align=\"center\"><tr><td class=\"second\" width=\"25%\" valign=\"top\">\n";

# verbands daten:
print "<table  align=\"left\">\n";
print "<tr><td><b>".$verband{"verband"}."</b></td></tr>\n";
print "<tr><td><b>".$verband{"liga"}."</b></td></tr>\n";
print "<tr><td><b>".$verband{"gruppe"}."</b></td></tr>\n";
print "<tr><td><a href=\"http://".$verband{"spielplan"}."\">Spielplan</a></td></tr>";
print "<tr><td><a href=\"http://".$verband{"resultate"}."\">Rangliste und Resultate</a></td></tr>\n";
print "</table>\n";

print "</td><td width=\"75%\">\n";

# mannschafts daten
print "<table align=\"left\">\n";
print "<tr><th colspan=\"2\">Dress</th></tr>\n";
if (exists $dress{"sponsor"}) {
  print "<tr><td valign=\"top\">Sponsor:</td><td>".&printAddress($dress{"sponsor"})."</td></tr>\n";
}
print "<tr><td valign=\"top\">Hemd</td><td>".$dress{"hemd"}."</td></tr>\n";
print "<tr><td valign=\"top\">Hose</td><td>".$dress{"hosen"}."</td></tr>\n";
print "<tr><td valign=\"top\">Stulpen</td><td>".$dress{"stulpen"}."</td></tr>\n";

print "<tr><th colspan=\"2\">Trainer</th></tr>\n";
if (exists $trainer{"trainer1"}) {
  print "<tr><td valign=\"top\">Trainer:</td><td>".&printAddress($trainer{"trainer1"})."</td></tr>\n";
}
if (exists $trainer{"trainer2"}) {
  print "<tr><td valign=\"top\">Co-Trainer</td><td>".&printAddress($trainer{"trainer2"})."</td></tr>\n";
}
print "</table>\n";

#Mannschaftsfoto
if (exists $mannschaft{"foto"}) { 
  print "<table  align=\"left\">\n";
  print "<tr><td><a href=\"".$path.$mannschaft{"foto"}."\">";
  print &printImage($path.$mannschaft{"vorschau"},"vorschau");
  print "</a></td></tr>\n";
  print "</table>\n";
}

print "</td></tr></table>\n";

print printComment("end of dynamic content");

exit;

#--- functions ------------------------------------------------------------------

# prints a preformatted  address
#
# @param hash with address values
# @return the html string
sub printAddress {
  $param = lc($_[0]);
  $name = $strasse = $ort = $tel = $email = "";
  $name = ${$adressen{$param}}{"name"}."\n";
  if (exists ${$adressen{$param}}{"strasse"}) {
    $strasse = ${$adressen{$param}}{"strasse"}."\n";
  }
  if (exists ${$adressen{$param}}{"ort"}) {
    $ort = ${$adressen{$param}}{"ort"}."\n";
  }
  if (exists ${adressen{$param}}{"tel"}) {
    $tel = ${adressen{$param}}{"tel"}."\n";
  }
  if (exists ${$adressen{$param}}{"email"}) {
    $email = "<a href=\"mailto:".${$adressen{$param}}{"email"}."\">".${$adressen{$param}}{"email"}."</a>\n";
  }
  $address = "<pre>".$name.$strasse.$ort."\n".$tel.$email."</pre>\n";
  return $address;
}

# prints a html comment
#
# @param string containing the comment
# @return the html string
sub printComment {
  $param = $_[0];
  $string = "<!-- ".$param." -->\n";
  return $string;
}

# prints a picture
#
# @param src of the image
# @param alt text
# @param width (not yet)
# @param height (not yet)
# @return the html string
sub printImage {
  $src = $_[0];
  $alt = $_[1];
  $string = "<img src=\"".$src."\" alt=\"".$alt."\" >";
  return $string;
}
#--- end -----------------------------------------------------------------------
