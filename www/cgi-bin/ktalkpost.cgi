#!/usr/bin/perl
# Replace the above with the path to Perl on your system, as in newspro.cgi
# kTalk - NewsPro talkback addon
# Version 2.0


# Set the path to ktconfig.pl
# If it's in the same directory as ktalkpost.cgi, you may be able to leave
# this as is. If it is in another directory, or if you run into problems,
# change this to the absolute path to ktconfig.pl.
$ktconfigpath = 'ktconfig.pl';

# If you use a server that allows file locking (most UNIXes and Windows NT do,
# Windows 9x does not), set this to 1 to prevent accidental file corruption if
# two people use the script at the exact same time.
$UseFlock = 1;



# ------- NO EDITING REQUIRED BELOW ------

require $ktconfigpath;

# Require npconfig.pl
require "npconfig.pl";

# Require nplib.pl
require "nplib.pl";

read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
@pairs = split(/&/, $buffer);

foreach $pair (@pairs) {
($name, $value) = split(/=/, $pair);
   $value =~ tr/+/ /;
   $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
   $value =~ s/&/&amp;/g;
   $value =~ s/"/&quot;/g;
   $value =~ s/</&lt;/g;
   $value =~ s/>/&gt;/g;
	$FORM{$name} = $value;
}


# Checks if we should print the adduser page
if (lc($ENV{'QUERY_STRING'}) eq 'dispadduserform'){
	&dispadduserform();
}


# Checks if we should print the lost-password form
if (lc($ENV{'QUERY_STRING'}) eq 'displostpassform'){
	&displostpassform();
}

# Checks if we should printout the changepassword form (a validation code must be submitted)
if (lc($ENV{'QUERY_STRING'}) =~ /^changepass/){
	 &dispchangepassform();
}

# Checks if we should save a user.
if ($FORM{'adduser'}){
	&adduser();
}

# Checks if we should alter userinformation.
if ($FORM{'changeinfo'}){
	&changeinformation();
}

# Checks if script are supposed to mail a password to a user
if ($FORM{'mailpass'}){
	&mailpass();
}

$basedir = $talkdir;

# Check so that newsID/name/comment are submitted.
if ($FORM{'newsID'} eq ''){ # Checks newsId variable
	&ktalk_message("No newsID to append comment to.");
}

if ($FORM{'name'} eq ''){ # Check namevariable
	&ktalk_message("No username submitted, it's required..");
}

if ($FORM{'comment'} eq ''){ # Check comment variable
	&ktalk_message("No comment submitted, it's required.");
}

# Check if a user is registered.
$userstate = &isRegistered();

if($RegisteredOnly eq "1"){
	if($userstate eq '1' ){
		# He's registered, do nothing and continue.
	} else {
		&ktalk_message("Only registered users are allowed to post.");
	}
}

# Checks lenght of comment field
$ktCommentLength = length($FORM{'comment'});
if ($ktCommentLength > $ktalkCommentMaxLength){
	&ktalk_message("Post is to large, the limit are [$ktalkCommentMaxLength] and you tried to post [$ktCommentLength].")
}

# Checks length on username
$ktUserNameLength = length($FORM{'username'});
if (length($FORM{'username'}) > $ktUserNameMaxLength){
	&ktalk_message("Username is to long, the limit are [$ktUserNameMaxLength] and you tried to submit [$ktUserNameLength].");
}


# Checks the email field length 
$ktEmailLength = length($FORM{'email'});
if (length($FORM{'email'}) > $ktEmailMaxLength){
	&ktalk_message("Email is to long, the limit are [$ktEmailMaxLength] and you tried to submit [$ktEmailLength].");
}

# Reading the template
open(INFILE,"<$reponsetmpl") or &dienice("Can't open $reponsetmpl");
@templatePage=<INFILE>;
close(INFILE);

#condense page array into one scalar variable
$resultPage=join('',@templatePage);

#Getting a nice filename
my $ktalkfilename = $FORM{newsID}; #Assuming submitted fields are in
$ktalkfilename =~ s/[^0-9\,]//g; # Strip out anything other than number or comma from the news ID
$ktalkfilename .= $talkext; # Add .shtml to the end.

# opens the diskussion file Reads it into a array
open(INF,"$basedir/$ktalkfilename") or &dienice("Can't open $basedir/$ktalkfilename");
   @ary = <INF>;
close(INF);


open(NEWFILE,">$basedir/$ktalkfilename") || &dienice("Can't open $basedir/$ktalkfilename for writing.");
if ($UseFlock) {
	flock(NEWFILE, 2);
}

foreach $line (@ary) {
    chomp($line);
 
 if ($line =~ /<!--ktalk postnum:(\d+)-->/) {
    $postnum = $1;
    $postnum++;
    $line =~ s/<!--ktalk postnum:\d+-->/<!--ktalk postnum:$postnum-->/;
}

# Date things
MiniNPopen();
ReadConfigInfo();
 
if ($NPConfig{'TimeOffset'}) { # If timeoffset is set in newspro we will use that.
	$kTalkPostDate = time();
	$kTalkPostDate += (3600 * $NPConfig{'TimeOffset'});
	$kTalkPostDate = &GetTheDate($kTalkPostDate);

} else {
	$kTalkPostDate = time();
	$kTalkPostDate = &GetTheDate($kTalkPostDate);
}
  
    #Here we insert the new comment
   if ($line =~ /\Q<!--ktalk newpost-->\E/){

	#Substitute all the variables
	$resultPage =~ s/\Q<!--ktalk newpost-->\E//g; # compatability with older versions
	$resultPage =~ s/<!--INSTRUCTIONS[\s\S]+?END INSTRUCTIONS-->//g; # in case people leave the instruction comment in

	if($FORM{'email'} eq ''){
		if($ktEmailRequired){ # If field is required
			&ktalk_message("Email is required, use your 'back' button and fill in the missing data.");
		} else {
			$resultPage =~ s/<!--ktalk field email-->/N\/A/g;
		}
	}
	else{
		$resultPage =~ s/<!--ktalk field email-->/$FORM{'email'}/g;
	}
	
	# The VipColor system
	
	if($UseVipColors){
		if(&isvip()){
			$printName = "<font color=\"$VipColor\">$FORM{'name'}<\/font>";
		} else {
			$printName = $FORM{'name'};
		}
		
	} else {
		$printName = $FORM{'name'};
	}
	
	if ($registered eq 1){
		$resultPage =~ s/<!--ktalk field name-->/$printName/g;
		$resultPage =~ s/<!--ktalk field ip-->/$hiddenmsg\n/g;
		$resultPage =~ s/<!--ktalk field unregistered-->//g;
	}
	
	if ($registered eq 0){
		if($AnonUsername){
			$resultPage =~ s/<!--ktalk field name-->/$AnonUsername/g;
		} else {
			$resultPage =~ s/<!--ktalk field name-->/$printName/g;
		}
		$resultPage =~ s/<!--ktalk field unregistered-->/$unregistered/g;
		$resultPage =~ s/<!--ktalk field ip-->/<font size=\"1\">$ENV{'REMOTE_ADDR'}<\/font>\n/g;
	}
        
    	if ($shownum){
        	$resultPage =~ s/<!--ktalk numindex-->/$postnum/g;
        } else {
		$resultPage =~ s/<!--ktalk numindex-->//g;
        }
	
	# Insert date        
	$resultPage =~ s/<!--ktalk field time-->/$kTalkPostDate/g;

	if($resultPage =~ /\[b\]/gi  and $resultPage =~ /\[\/b\]/gi){
		$resultPage =~ s/\[b\]/<b>/gi;
		$resultPage =~ s/\[\/b\]/<\/b>/gi;
	}
	

	# Mails the result
	&mailer();
		
	$FORM{'comment'} =~ s/\n/<br>/g; # Replace linebreaks with <br>
	
	if($ubbcodes){
		$FORM{'comment'} =~ s/(\[b\])(.+?)(\[\/b\])/<b>$2<\/b>/isg;
		$FORM{'comment'} =~ s/(\[i\])(.+?)(\[\/i\])/<i>$2<\/i>/isg;
		$FORM{'comment'} =~ s/(\[QUOTE\])(.+?)(\[\/QUOTE\])/ <BLOCKQUOTE><font size="1" face="Arial">Quote:<\/font><HR>$2<HR><\/BLOCKQUOTE>/isg;
	}

	$ktComment = $FORM{'comment'};

	$resultPage =~ s/<!--ktalk field comment-->/<!--ktalk start_fields: start_email $FORM{'email'} end_email start_name $FORM{'name'} end_name end_fields--><!--ktalk start_comment-->$ktComment<!--ktalk end_comment-->/g;
	
	# Autolinks the comment field.
	if($autolink){
		$resultPage =~ s/([\s\(]|\A|<br>)(http:\/\/|ftp:\/\/|https:\/\/)([^\s\)"<>,]+)/$1<a href="$2$3">$2$3<\/a>/gi;   
	}

   # Generate a random ID.
   my $rando = int(rand(999999));
   my $commentID = $rando . "-" . time;
   unless ($ktPostOrder) {
	   print NEWFILE "<!--ktalk newpost-->\n";
   }
   print NEWFILE "<!--ktalk start_item $commentID -->";  
   print NEWFILE $resultPage;
   print NEWFILE "<!--ktalk end_item $commentID -->";
   if ($ktPostOrder) {
   	   print NEWFILE "\n<!--ktalk newpost-->\n";
   }
}
else {
    print NEWFILE "$line\n";
}

}
close(NEWFILE);


# Reading the template

open(INFILE,"<$confirmtmpl") or &dienice("Can't open $confirmtmpl");
@confirmtmpl=<INFILE>;
close(INFILE);

print "Content-type:text/html\n\n";

# Build news if required.
if ($BuildOnPost) {
	$JustLoadSubs = 1;

	$SilentBuild = 1;

	require "$NPdir/newspro.cgi";

	$abspath = $NPdir;
	StartUp();
	ReadConfigInfo();
	LoadAddons();
	GenHTML();
}


#condense page array into one scalar variable
$result = join('', @confirmtmpl);
$result =~ s/<!--ktalk pageURL-->/$talkbaseurl\/$ktalkfilename/gi;

print $result; # Prints the verification page to the user.

####################################################################################
#                                                                                  #
#                  All the subs that makes the work                                #
#                                                                                  #
####################################################################################


#####################################################################################
# This sub checks variuos fields for illegal characters                             #
#####################################################################################

sub ktalk_illegalchars {
	if ($ktCheckChars){
		($ktCheck) = @_;
		$_ = $ktCheck;	
		if ($_ =~ /[^\w\d\_]/) {
			ktalk_message("You have submitted illegal characters, please remove and try again, main.");
		}
	}
}

#####################################################################################
# This sub shows a message to the user.                                             #
# For editing look at the variable $ktalkMessageFile in ktconfig.pl                 #
#####################################################################################
sub ktalk_message {
	($message) = @_;

	# opens the messagefile Reads it into a array
	open(INF,"$ktalkMessageFile") or &dienice("Can't open $ktalkMessageFile");
	   @ary = <INF>;
	close(INF);
	
	print "Content-type:text/html\n\n";
	foreach $line (@ary) {
		if ($line =~ /\Q<!--ktalk field message-->\E/){
			$line =~ s/<!--ktalk field message-->/$message/g;
			print "$line\n";
		} else {
		    	print "$line\n";
		}
	}
	exit;
}

####################################################################################
# This sub saves submitted information about the user into the userdatabase        #
####################################################################################
sub changeinformation {
	$validationcode = $FORM{'validationcode'};
	
	if ($validationcode eq ''){ # Checks if validation code is submitted
		&ktalk_message("No validation code was submitted. [$validationcode]");
	}
	
	if($FORM{'newpass'} ne $FORM{'newpass2'}){ # Check the two passwords against each other
		&ktalk_message("The two password-fields doesnt match, please try again.");
	}
	
	if($FORM{'newpass'} eq '' || $FORM{'newpass2'} eq ''){
		# Both password field are empty
		$ktNoPasschange = 1;
	} else {
		if(length($FORM{'newpass'}) < $passwordlenght){ # Checks the lenght of the password submitted
			&ktalk_message("The Password-field must be longer than $passwordlenght characters.");
		}
	}

	open(INF,"$ktalkuserfile") || &dienice("Can't open $ktalkuserfile") ;
		@userdata = <INF>;
	close(INF);
	
	
	foreach $i (@userdata) { # Loops the userdata file
		chomp($i);
		($username,$userpass,$usermail,$usercode,$userlevel) = split(/\|/,$i);			
		
		if($validationcode eq $usercode){ # the two codes match 
			$cPass = crypt($FORM{'newpass'}, 'Bc');
			
			if (&valid_address == 0){       		
        			&ktalk_message("The email address you submitted seams strange, did you mistype it? The database only accept email address's in the following format. user\@host.com.");
        		}
				
			open (CNT,$ktalkuserfile);
			if ($UseFlock) {
				flock(CNT, 2);
			}
			my @newCNT;
			my $ktUserFound = 0;
		
			while (<CNT>) {
				chomp($_);
			($ktusername,$ktuserpass,$ktusermail,$ktusercode,$ktuserlevel) = split(/\|/,$_);
				if ($ktusercode eq $validationcode) {
			       		if($ktNoPasschange){
			       			push(@newCNT, "$ktusername\|$ktuserpass\|$FORM{'email'}\|0\|$ktuserlevel\|");
			       		} else {
			       			push(@newCNT, "$ktusername\|$cPass\|$FORM{'email'}\|0\|$ktuserlevel\|");
			       		}
			       		$ktUserFound++;
			 	} else {
		   			push(@newCNT, $_);
		  		}
		 	}
		 	close(CNT);
		 	
		 	unless ($ktUserFound) {
				&dienice("Userdata not saved, please try again.");	
		 	} else {
		  		open(CNT, ">$ktalkuserfile");
		  			print CNT join("\n", @newCNT);
		  		close(CNT);
		  		$ktUserFound = 1;
				$UserInformationSaved = qq~
				<br><br>
				<b>User information saved</b><br>
				You can now login with your new userdata.
				<br>
				~;	
				&ktalk_message("$UserInformationSaved");

		 	}
		}
	}
}

#################################################################################################################
# This sub will check the temporary password and then print a page where the user can change his/hers password..#
# This sub _must_ be dynamicaly generated, ie you cannot make a copy of the form.                               #
#################################################################################################################



sub dispchangepassform {
	
	$validationcode = $ENV{'QUERY_STRING'};
	$validationcode =~ s/changepass//;
	
	open(INF,"$ktalkuserfile") || &dienice("Can't open $ktalkuserfile") ;
		@userdata = <INF>;
	close(INF);
		
	#Loops the userdata file
	foreach $i (@userdata) {
		chomp($i);
		($username,$userpass,$usermail,$usercode,$userlevel) = split(/\|/,$i);

		if($validationcode eq $usercode){
			# the two codes match 
			$print_username = $username;
			$print_mail = $usermail;
			$match = 1;
		}
	}
	
	if ($match){
	
		$changePassForm =  qq~
		<form action="$postcgiurl" method="Post">
		<center>
		
		<table width="75%" border="0">
		<tr> 
		<td colspan="2"> 
		<div align="center"><font face="Arial, Helvetica, sans-serif" size="2">Change 
		Your information [<b>$print_username</b>]</font></div>
		</td>
		</tr>
		<tr>
		<td>Email:</td>
		<td>
		<input type="text" name="email" value="$print_mail">
		</td>
		</tr>
		<tr> 
		<td><font face="Arial, Helvetica, sans-serif" size="2">Password:</font></td>
		<td> <font face="Arial, Helvetica, sans-serif" size="2"> 
		<input type="password" name="newpass">
		</font></td>
		</tr>
		<tr> 
		<td><font face="Arial, Helvetica, sans-serif" size="2">Re type password:</font></td>
		<td> <font face="Arial, Helvetica, sans-serif" size="2"> 
		<input type="password" name="newpass2">
		<input type="hidden" name="validationcode" value="$validationcode">
		<input type="hidden" name="changeinfo" value="1">
		</font></td>
		</tr>
		<tr>
		<td colspan="2">
		<center>
		<input type="Submit" value="Submit">
		</center>
		</td>
		</tr>
		</table>
		
		</center>
		</form>
		<br>
		<br>
		~;
		&ktalk_message("$changePassForm");
	} else {
		&ktalk_message("Did not find such a validation code, please try and mail yourself a new one.");
	}
}

####################################################
# The die sub, called whenver something goes wrong.#
####################################################
sub dienice {
    print "Content-type:text/html\n\n";
    ($errmsg) = @_;
    print "<h2>Error</h2>\n";
    print "$errmsg<br>\n";
    print "$ktGeneralErrorMsg";
    print "</body></html>\n";
    exit;
}


############################################################
# This sub mails the comment to a specified email address. #
############################################################

sub mailer {
	if ($mail_webmaster) {
		open(MAIL, "|$mail_prog -t") || &dienice("Couldnt comunicate with your mail program, read the documentation.");
		print MAIL "To: $webmaster\n";
		print MAIL "From: $sender\n";
		print MAIL "Subject: kTalk from: $FORM{'name'} \n\n";
		print MAIL "Url:$talkbaseurl\/$ktalkfilename\nEmail:$FORM{'email'}\nName:$FORM{'name'}\nTalk:\n$FORM{'comment'}\n\n";
		print MAIL "--End of Mail--\n\n";
		close(MAIL);
	}
}

#################################################################################
# The sequrity sub, called to check the submitted data against the userdatabase.#
#################################################################################

sub isRegistered {

	# Sets the registered flag to 0 because we havent check him yet.
	$registered = 0;
	
	open(INF,"$ktalkuserfile") || &dienice("Can't open $ktalkuserfile") ;
		@userdata = <INF>;
	close(INF);
	
	#Loops the userdata file
	foreach $i (@userdata) {
		chomp($i);
		($username,$userpass,$usermail) = split(/\|/,$i);
		
		# Here we try to match a username with the submitted username
		if(lc($FORM{'name'}) eq lc($username)) {
			# Submitted username and database username match			
			if(crypt($FORM{'password'}, "Bc") eq $userpass){
				# the two passwords match do nothing
				$registered = 1;
				return 1;
			}
			else{
				# the two passwords doesnt match
				&ktalk_message("The username you submitted is registered and the password you submitted does not match the password in our database.");
			}
		}
	}			
}

####################################################
# The display add userform.                        #
####################################################

sub dispadduserform {

	$adduserform = qq~
<html>
<head>
<title>Add user </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<form action="$postcgiurl" method="post">
<table width="75%" border="0" align="center">
  <tr> 
    <td width="100" colspan="2">
      <div align="center">Register yourself</div>
    </td>
  </tr>
  <tr> 
    <td width="100"><font face="Arial, Helvetica, sans-serif" size="2">Username:</font></td>
    <td> 
      <input type="text" name="username">
    </td>
  </tr>
  <tr> 
    <td width="100"><font face="Arial, Helvetica, sans-serif" size="2">Email:</font></td>
    <td> 
      <input type="text" name="email">
    </td>
  </tr>
  <tr> 
    <td width="100"><font face="Arial, Helvetica, sans-serif" size="2">Password:</font></td>
    <td> 
      <input type="password" name="password" maxlength="20">
      <input type="hidden" name="adduser" value="1">
    </td>
  </tr>
  <tr> 
    <td width="100"><font size="2"></font></td>
    <td> 
      <input type="submit" name="submit" value="Submit">
    </td>
  </tr>
</table>
</form>
<br>
<br>
</body>
</html>

~;
&ktalk_message("$adduserform");
}

####################################################
# The display lost password form.                  #
####################################################

sub displostpassform {

	$lostpasswordform =  qq~
<form action="$postcgiurl" method="post">
  <table width="75%" border="0" align="center">
    <tr> 
      <td width="100%" colspan="2"> 
	<font face="Arial, Helvetica, sans-serif">
	<center>Mail me 
	  the password<br>
	  <font size="1">(the password will be mailed to the address that you 
	  have submitted to the talk-database, if the address is'nt correct please 
	  contact the administrator)</center></font></font>
      </td>
    </tr>
    <tr> 
      <td width="100"><font face="Arial, Helvetica, sans-serif" size="2">Username:</font></td>
      <td> 
	<input type="text" name="username">
      </td>
    </tr>
    <tr> 
      <td width="100"><font size="2"></font></td>
      <td> 
	<input type="hidden" name="mailpass" value="1">
	<input type="submit" name="submit" value="Submit">
      </td>
    </tr>
  </table>
</form>
<br>
<br>
~;

&ktalk_message("$lostpasswordform");

}


###########################################################
# The mail password sub, mails the user his/hers password.#
###########################################################

sub mailpass {
	$match=0;
	if($FORM{'username'} eq ''){
        	&ktalk_message("You didnt fill in the required Username-field, please try again.");
	}
	open(INF,"$ktalkuserfile") || &dienice("Can't open $ktalkuserfile") ;
		@userdata = <INF>;
	close(INF);
	#Loops the userdata file
	foreach $i (@userdata) {
		chomp($i);
		($username,$userpass,$usermail,$usercode,$userlevel) = split(/\|/,$i);
		if(lc($FORM{'username'}) eq lc($username)) {
			$mailtoaddress = $usermail;
			$mailtoname = lc($username);
			$mailtopass = $userpass;		
			$match = 1;
		}
	}
	if($match eq 1){
		$random_string = &insertrandomstring("$mailtoname");
		
		open(MAIL, "|$mail_prog -t") || &dienice("MAIL PROGGY $!");
		print MAIL "To: $mailtoaddress\n";
		print MAIL "From: $forgotsender\n";
		print MAIL "Subject: Your userregistration\n\n";
		
		print MAIL qq~
Upon request your registration information has been sent to you.
The request was made from the following ip (if availible)...

$ENV{'REMOTE_ADDR'}

Registration:

Username: $mailtoname
Useremail: $mailtoaddress

To set a new password, paste the url below in the "goto" field in your browser and change it online.\n
$postcgiurl?changepass$random_string


-----------------------------------------------------------
		~;
		print MAIL "--End of Mail--\n\n";
		close(MAIL);

		# We sent a mail and we wanna tell the user that.		
		&ktalk_message("Mail sent to [$mailtoname].<br> This can take a while so be patient, if your mail doesnt arrive please mail the administrator.<br>[<a href=\"javascript:this.close()\">Close this window.<\/a>] ");
	
	} else{
		# Tells the user we couldnt find a username-match		
		&ktalk_message("Didnt find such a username [$mailtoname]... try and register yourself first.<br>[<a href=\"javascript:this.close()\">Close this window.<\/a>]");
		
	}
exit;
}


####################################################
# This is a simple email address verification sub .#
####################################################

sub valid_address {
  $testmail = $FORM{'email'};
  if ($testmail =~ /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/ ||
  $testmail !~ /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/)
   { return 0; }  
   else { return 1; }
}


####################################################
# This is a simple db safe sub                    .#
####################################################


sub dbsafe {
	($ktCheck) = @_;
	$_ = $ktCheck;	

	if ($_ =~ /[|]/){
		return 1;
	}  	
   #return 1;
}




####################################################
# The adduser sub, adds a user to the userdatabase.#
####################################################

sub adduser {

	# checks so that no values are empty
	if($FORM{'username'} eq ''){
		&ktalk_message("You didnt fill in the required username-field.");
	}
	# Checks length on username
	$ktCheck = length($FORM{'username'});
	if (length($FORM{'username'}) > $ktUserNameMaxLength){
		&ktalk_message("Username is to long, the limit are [$ktUserNameMaxLength] and you tried to submit [$ktCheck].");
	}
	
	if(&ktalk_illegalchars($FORM{'username'})){
		&ktalk_message("Illegal characters in username.");
	}
	
	# Checks email for null entrys
	if($FORM{'email'} eq ''){
		&ktalk_message("You didnt fill in the required email-field.");
	}

	# Checks the email field length 
	$ktCheck = length($FORM{'email'});
	if ($ktCheck > $ktEmailMaxLength){
		&ktalk_message("Email is to long, the limit are [$ktEmailMaxLength] and you tried to submit [$ktCheck].");
	}
	# Check email syntax
	if (&valid_address == 0){       		
        	&ktalk_message("The email address you submitted seams strange, did you mistype it? The database only accept email address's in the following format. user\@host.com.");
        }
	
	# Makes a DB-safe test on the email address
	if(&dbsafe($FORM{'email'})){
		&ktalk_message("Illegal characters in email.");
	}

	# Checks password for null entrys
	if($FORM{'password'} eq ''){
        	&ktalk_message("You did'nt fill in the required password-field.");
	}
	
	# Checks the password length 
	$ktCheck = length($ktPasswordMaxLength);
	if(length($FORM{'password'}) > $ktPasswordMaxLength){
		&ktalk_message("The password is to long, the max characters for password is set to [$ktPasswordMaxLength].");
	}
	
	# Checks password length... we dont want the user to have to short password
	if(length($FORM{'password'}) < $ktRequiredPasswordlenght){
        	&ktalk_message("The Password-field must be longer than $ktRequiredPasswordlenght characters.");
	}
	
	open(INF,"$ktalkuserfile") || &dienice("Can't open $ktalkuserfile") ;
		@userdata = <INF>;
	close(INF);

	#Loops the userdata file
	foreach $i (@userdata) {
		chomp($i);
		($username,$userpass,$usermail,$usercode,$userlevel) = split(/\|/,$i);

		# Here we try to match a username with the submitted username
		if(lc($FORM{'username'}) eq lc($username)) {
	        	&ktalk_message("The username already exist please try with another one.");
		}
		
		if(lc($FORM{'email'}) eq lc($usermail)) {
	        	&ktalk_message("There is already a user registered with that emailaddress, please use another one.");
		}
		
	}
	# Save the encrypted pass into a variable
	$cPass = crypt($FORM{'password'}, 'Bc');
	
	open (CNT,$ktalkuserfile) || &dienice("Can't open $ktalkuserfile for INPUT.");
	my @newCNT;

	if ($UseFlock) {
		flock(CNT, 2);
	}

	while (<CNT>) {
		chomp($_);
		push(@newCNT, $_);
	}
		push(@newCNT, "$FORM{'username'}\|$cPass\|$FORM{'email'}\|\|0\|");
	close(CNT);
	
	open(CNT, ">$ktalkuserfile") || &dienice("Can't open $ktalkuserfile for output.");
	if ($UseFlock) {
		flock(CNT, 2);
	}

		print CNT join("\n", @newCNT);
	close(CNT);

	&ktalk_message("You have been added to the database, you can now post comments as a registered user. <br> [<a href=\"javascript:this.close()\">Close this window.<\/a>]");
}


###############################################################################
# This sub generates a random number that is sent to the user for validation. #
###############################################################################
sub insertrandomstring {


	($seekuser) = @_;
	$length = 20;	# make it at least 20 chars long.

	$vowels = "aeiouyAEUY";
	$consonants = "bdghjmnpqrstvwxzBDGHJLMNPQRSTVWXZ12345678";
	srand(time() ^ ($$ + ($$ << 15)) );
	$alt = int(rand(2)) - 1;
	$s = "";
	$newchar = "";
	foreach $i (0..$length-1) {
		if ($alt == 1) {
			$newchar = substr($vowels,rand(length($vowels)),1);
		} else {
			$newchar = substr($consonants, 
				rand(length($consonants)),1);
		}
		$s .= $newchar;
		$alt = !$alt;
	}

	# The inserting of the randomstring into the database.
	$insertstring = $s;

	open (CNT,$ktalkuserfile);
	if ($UseFlock) {
		flock(CNT, 2);
	}
	my @newCNT;
	my $ktUserFound = 0;

	while (<CNT>) {
		chomp($_);
	($ktusername,$ktuserpass,$ktusermail,$ktusercode,$ktuserlevel) = split(/\|/,$_);
		if (lc($ktusername) eq $seekuser) { # If username is'nt changed	
	       		push(@newCNT, "$ktusername\|$ktuserpass\|$ktusermail\|$s\|$ktuserlevel\|");  		
	       		$ktUserFound++;
	 	} else {
   			push(@newCNT, $_);
  		}
 	}
 	close(CNT);
 	
 	unless ($ktUserFound) {
  		# Did not find user
 	} else {
  		open(CNT, ">$ktalkuserfile");
  			print CNT join("\n", @newCNT);
  		close(CNT);
  		$ktUserFound = 1;
 	}

	return $insertstring;
}

######################################################################################
# This sub checks if a user is admin                                                 #
# It require two params:                                                             #
# adminname/name - name                                                              #
# adminpass/password - password                                                      #
# after checking the validity of those two it checks the database for the admin flag #
######################################################################################

sub isvip {
		# This is so we can use the admin-check in other places than just within the adminpages
		if($FORM{'adminname'} eq ''){
			$adminname = $FORM{'name'};
		} else {
			$adminname = $FORM{'adminname'};
		}
		
		if($FORM{'adminpass'} eq ''){
			$adminpass = $FORM{'password'};
		} else {
			$adminpass = $FORM{'adminpass'};
		}
		
	open(INF,"$ktalkuserfile") || &dienice("Can't open $ktalkuserfile") ;
		@userdata = <INF>;
	close(INF);
	
	foreach $i (@userdata) { # Loops the userdata file
		chomp($i);
		($username,$userpass,$usermail,$usercode,$userlevel) = split(/\|/,$i);			
		if(lc($username) eq lc($adminname)){ # the names match 
			$usermatch = 1; # The user match
			if($userlevel eq "1"){ 
				$adminmatch = 1; # The userlevel match and you are a admin.
				if(crypt($adminpass, 'Bc') eq $userpass){			
					$passwordmatch = 1; # The password match
				}
				else {
				}			
			}
			else{
				
			}
		}
		else {
			
		}
	}
	
	if($usermatch and $adminmatch and $passwordmatch){ 	
		return 1;
	} else {
	
	}
}
