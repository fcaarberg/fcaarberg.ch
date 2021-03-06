# Configuration file for kTalk 2.0

# Set the following variables:

## -- ESSENTIAL SETTINGS -- ##

# Absolute path to the talkback file directory. This script must be able to create
# files in this directory; on UNIX systems, this means CHMODding the directory 777
# (or, on some servers, 666). No trailing slash.
$talkdir = '/path/to/talk_dir';

# The URL to the directory set above. No trailing slash.
$talkbaseurl = 'http://www.mysite.com/talk';

# What extension should the HTML files generated by kTalk have?
$talkext = '.shtml';

# The URL to ktalkpost.cgi, the other kTalk CGI file. Usually this file will be in your main
# NewsPro directory.
$postcgiurl = 'http://www.mysite.com/news/ktalkpost.cgi';

# Which order do you want comments to appear in?
# 0 = Most recent (at the top) to least recent (default)
# 1 = Least recent (at the top) to most recent
$ktPostOrder = '0';

# What is the kTalk template file you are using called? Most users should leave
# this setting unchanged. If you have problems, try using an absolute path.
$talktmpl = 'ktalk.tmpl';

# The template file for each comment. Most users should leave this as it is.
$reponsetmpl = 'ktresponse.tmpl';

# The template file for the post confirmation page. Most users should leave this as it is.
$confirmtmpl = 'ktconfirm.tmpl';

# This is the file that is used for send bits of information to the user.
# It is used often as a confirmation page.
# If you have problems, try using an absolute path
$ktalkMessageFile = 'ktmessage.tmpl';

# Do you want to build news (which updates the comment count) whenever a comment is posted?
# This can be very slow if you have many news items.
# 0 = No 1 = Yes
$BuildOnPost = '0';

# If $BuildOnPost above is set to 1, specify the full path to the NewsPro script files
# directory below. No trailing slash.
$NPdir = '/path/to/newspro';


# If you want registration to be mandatory you set this option to 1.
# If set to 1, users can't post if they aren't registered
# 0 = No 1 = Yes
$RegisteredOnly = '0';

# Set this to a username if you would like anon-posters to get a default username.
# If you don't set this they may use any name they want as long it is'nt registered.
# If you leave this empty the user can choose any unregisterd name he chooses.
$AnonUsername = 'Anonymous';

# VIP colors: Alters the posted name of a VIP-User to a special color.
# If you want to use VIP colors set this to 1 else set it to 0.
# 0 = No 1 = Yes (Don't forget to change the VIPColor)
$UseVipColors = '1';

# This is the color the vip-name gets when Vipcolors are enabled.
# It uses the hex-colorsystem, the same as in HTML.
$VipColor = '#FF8040';

# You can let your user submit a set of primitive formatting tags, 
# inspired by Ultimate Bulletin Board.
# The codes in kTalk are:
# [b][/b] for bold text
# [i][/i] fot italic text
# [quote][/quote] for quoting.
# 0 = No 1 = Yes
$ubbcodes = 1;

# Automaticly generate html-links when the user posts an url. 
# [1 = Generate links, 0 = Does not generate links.]
$autolink = 1;


# Set the path to ktusers.dat
# If it's in the same directory as ktalkpost.cgi, you may be able to leave
# this as is. If it is in another directory, or if you run into problems,
# change this to the absolute path to ktusers.dat.
$ktalkuserfile = '/path/to/ktalk/ktusers.dat';

# This variable indicates if a mail of every post should go out to an address 
# (Don't forget to fill in the webmaster mailaddress).
# 0 = No 1 = Yes
$mail_webmaster = 0;

# The email address that comment are sent to (for supervision only)
$webmaster = 'webmaster@mysite.com';

# Sender is the mailaddress shown in the "from" field in the users mailclient.
$sender = 'sender@mysite.com';

# When a user request a passwordchange this will end up in the 
# from-field in his/hers email client.
$forgotsender = 'sender@mysite.com';

# The mail program that mail are sent with.
$mail_prog = "/usr/lib/sendmail";

# This is the message that are printed if a user arent a registered user.
# Do not remove or alter the "\"-signs if you are not experienced in perl.
# Tip: You could for example put a image here.
# Example: $unregistered = <img src=\"unregistered.gif\" alt=\"Unregistered user\">
# Feel free to experiment but be careful and allways consult a perl manual if you 
# feel insecure.
$unregistered = "<font size=\"1\" face=\"Arial\" color=\"red\">Unregistered<\/font>";

# When a user is registered the ip is hidden this will be the msg.
# Do not remove or alter the "\"-signs if you are not experienced in perl.
# Tip: You could for example put a image here.
# Example: $unregistered = <img src=\"unregistered.gif\" alt=\"Unregistered user\">
# Feel free to experiment but be careful and allways consult a perl manual if you 
# feel insecure.
$hiddenmsg = "<font size=\"1\" face=\"Arial\">Hidden<\/font>";

# This is the maxlength for the actual commentfield (ie not passwordlength etc).
# 3000 should be enough but you can adjust it as you like.
$ktalkCommentMaxLength = 3000;

# UserName Maxlength.
$ktUserNameMaxLength = 30;

# Email maxlength 
$ktEmailMaxLength = 50;

# Password maxlength
$ktPasswordMaxLength = 20;

# This varible sets the _required_ password lenght.
# A value of atleast 5 is recomended.
$ktRequiredPasswordlenght = 5;

# If you want to enumerate the posts. (You must have the valid field in the templates)
# 1 = Yes 0 = No
$shownum = 1;

# Set if email-field that are required
# 1 = Required 0 = Not required
$ktEmailRequired = 0;

# This will enable a experimental illegal-character test on submitted usernames.
# If you run into any troubles with registration, disable this.
$ktCheckChars = 1;

# In the remove ktalk page section you can choose to display the title of the ktalkpage.
# This can be slow so turn it off if you like.
$ktDisplayTitle = 1;

# General error message
$ktGeneralErrorMsg = qq~ If this error persists, and you believe that it indicates a problem, please send
	an e-mail to the administrator of this site. Please use your browser's Back button to leave this page.~;

# Which ndisplay.pl subroutine do you want to use as your news style on talkback pages?
# If you don't know what this means, leave it as-is.
$ktnewssub = 'DoNewsHTML';


# That's it!
# REMEMBER, you must set the path to Perl in ktalkpost.cgi, 
# and you may need to set the path to this file in both
# ktalkpost.cgi and npa_ktalk.pl
1;
