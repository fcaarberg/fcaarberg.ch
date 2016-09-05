#c:\\per\\bin

# Homepage www.fcaarberg.ch
# Author patrick.zysset@fcaarberg.ch

open(ANLASS, "../anlaesse.txt");

# get game informations

@verein;
@sonst;
$type;
$what;
$where;
$date;
$info;

while($gline = <ANLASS>) {
  if ($gline =~ m/\A\[(\w*)\]/i) {
    $type=$1;
  } elsif ($gline =~ m/was:\s*(.*)/i) {
    $what=$1;
  } elsif ($gline =~ m/wo:\s*(.*)/i) {
    $where=$1;
  } elsif ($gline =~ m/wann:\s*(.*)/i) {
    $date=$1;
  } elsif ($gline =~ m/info:\s*(.*)/i) {
    $info=$1;
    if ($type =~ m/verein/i) {
      push(@verein, '<td>'.$what.'</td><td>'.$where.'</td><td>'.$date.'</td><td>'.$info.'</td>');
    } elsif ($type =~ m/sonst/i) {
      push(@sonst, '<td>'.$what.'</td><td>'.$where.'</td><td>'.$date.'</td><td>'.$info.'</td>');
    }
  }
}

close(ANLASS);

print "<h1>Anl&auml;sse</h1>";

print "<h3>Vereinsanl&auml;sse</h3>";
print "<table width=\"100%\">\n";
print "<tr><th>Was</th><th>Wo</th><th>Wann</th><th>Informationen</th></tr>\n";
$counter = 0;
foreach (@verein) {
  print "<tr class=\"";
  if ($counter % 2 == 1) { print "first"; }
  else { print "second"; }
  print "\">".$_."</tr>\n";
  $counter++;
}
print "</table>";

print "<h3>sonstige Anl&auml;sse</h3>";
print "<table width=\"100%\">\n";
print "<tr><th>Was</th><th>Wo</th><th>Wann</th><th>Informationen</th></tr>\n";
$counter = 0;
foreach (@sonst) {
  print "<tr class=\"";
  if ($counter % 2 == 1) { print "first"; }
  else { print "second"; }
  print "\">".$_."</tr>\n";
  $counter++;
}
print "</table>";
