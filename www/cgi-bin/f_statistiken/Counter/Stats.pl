#!/usr/bin/perl -w

use strict;
use CGI::Carp qw(fatalsToBrowser);

opendir(DIR, "Count") || die "Verzeichnis Count nicht gefunden";
my @Eintraege0 = readdir(DIR);
closedir(DIR);
shift(@Eintraege0);
shift(@Eintraege0);
my @Eintraege = sort(@Eintraege0);


my @Zahlen;
for(@Eintraege)
{ open(A,"<Count/$_");
  my $Zahl = <A>;
  push(@Zahlen,$Zahl);
  close(A);
}

open(IDZUORDNUNG,"<IDZuordnung.txt");
my @IDZ = <IDZUORDNUNG>;
close(IDZUORDNUNG);
chomp(@IDZ);

print "Content-type: text/html\n\n";
print "<html>\n";
print "<head>\n";
print "<title>Counter-Statistik von den F-Junioren</title>\n";
print '<link rel="stylesheet" href="http://www.fcaarberg.ch/style/style.css">';
print "\n</head>\n";
print "<body>\n";
print '<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td>';
print "\n<h1>Besucherzahlen der Seiten von den F-Junioren</h1></td></tr>\n";
print "<tr><td><h3>Besucher seit dem 23. Dezember 2002:</h3></td></tr>\n";

print "<tr><td valign=\"top\">";
my $B;
$B = 0;
for(@Zahlen)
{ print "$IDZ[$B]: $_<br>\n";
  $B = $B + 1;
}
print "</td>";
print "</tr>";
print "</table>\n";
print "</body>\n";
print "</html>\n";