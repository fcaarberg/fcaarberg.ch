#!/usr/bin/perl -w

use CGI;
my $cgi = new CGI;
use CGI::Carp qw(fatalsToBrowser);

my $CTIME_String = localtime(time);
my $altCookie = $cgi->cookie(-name=>'MarcoSperre');
my $neuCookie = $cgi->cookie(-name=>'MarcoSperre',
                            -value=>$CTIME_String,
                            -expires=>'+10y',
                            -path=>'/');

print $cgi->header(-cookie=>$neuCookie),
  $cgi->start_html("Cookie-Test"),
  $cgi->p("<b>Ihr letzter Besuchszeitpunkt dieser Seite war</b>: ", $altCookie || 'unbekannt'),
  $cgi->p("<b>Als neuer Besuchszeitpunkt wurde gespeichert</b>: ", $CTIME_String),
  $cgi->end_html();

