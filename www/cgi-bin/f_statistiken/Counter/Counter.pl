#!/usr/bin/perl -w

use CGI::Carp qw(fatalsToBrowser);
use CGI;

if(defined $ENV{'HTTP_COOKIE'})
{  my @cookies = split(/[;,]\s*/,$ENV{'HTTP_COOKIE'});
   if($cookies[0] =~ /MarcoSperre/)
   {  goto FINALE;
   }
}

# Aktuelle Zeit
my $time = time();

### Teil 1 ###

# ID wird abgefragt
my $Gesamt = $ENV{'QUERY_STRING'};

NEU:
# Datei mit IPs wird geöffnet und IPs werden ausgelesen
open(IPSPERRE,"<Ipsperren/Ipsperre$Gesamt.txt") || Datei();
my @IPs = <IPSPERRE>;
chomp(@IPs);
close(IPSPERRE);

# Die IPs werden geprüft, ob sie schon mehr als eine Stunde alt sind
my @B;
for(@IPs)
{ my @A = split(/:/,$_);
  if($A[0] + 3600 > $time)
  { push(@B,$_);
  }
}

# @B enthält nun die IPs welche weniger als eine Stunde alt sind

### Teil 2 ###

# IP des Users wird ermittelt
my $ip = $ENV{'REMOTE_ADDR'};
my @C;

# IP des Users wird mit den IPs der Datei verglichen
for(@B)
{ @C = split(/:/,$_);
  if($C[1] eq $ip)
  { goto ENDE;
  }
}

# IP ist nicht in der Datei vorhanden, also wird der Zähler um 1 erhöht
open(COUNT,"<Count/Count$Gesamt.txt") || die "Datei Count nicht gefunden";
my $Zahl = <COUNT>;
close(COUNT);
open(COUNT,">Count/Count$Gesamt.txt");
$Zahl = $Zahl + 1;
print COUNT $Zahl;
close(COUNT);

ENDE:
# Die IPs werden in aktualisierter Form wieder in die Datei geschrieben
open(IPSPERRE,">Ipsperren/Ipsperre$Gesamt.txt") || die "Datei Ipsperre$Gesamt nicht gefunden";
for(@B)
{ print IPSPERRE "$_\n";
}
print IPSPERRE "$time:$ip\n";
close(IPSPERRE);

# Ausgabe der Bild-Datei
FINALE:
print "Content-type: image/gif\n\n";
open(BILD,"Counter.gif") || die "Logo nicht gefunden";
binmode(BILD);
print <BILD>;
close(BILD);

sub Datei
{ open(IPSPERRE,">Ipsperren/Ipsperre$Gesamt.txt");
  print IPSPERRE "0";
  close(IPSPERRE);
  open(COUNT,">Counter/Count$Gesamt.txt");
  print COUNT "0";
  close(COUNT);
  goto NEU;
}
