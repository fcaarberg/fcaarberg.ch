# NewsPro Messages File
$nplangversion = 3.7;
#
# Language Name: DEUTSCH
$LangName = "DEUTSCH";
# Translation by Roland Becker, rb.npro@konferenz.com
#
# By editing this file, you can change any of the messages 
# seen on NewsPro screens by basic - Standard or High - level
# users. The administrator-only screens are English-only, as
# if the administrator did not speak English, all documentation
# would have to be translated as well, which is simply too great a task.
#
# Translators: please leave the above message intact, and in English.
#
# Do NOT edit the internal message names, only the messages themselves.
# Example:
# 'DoNotEditMe' => 'TranslateOrEditMe'
#
# If you use the tilde (~) within a message, you must escape it, i.e. \~
# Characters which contain a tilde, such as Ò, are fine and don't need to be escaped.
#
# For the messages, the boundary of where you can edit is between q~ and the following ~,
# Do not edit outside that boundary.
#
# START DATE INFORMATION

@Week_Days = ('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag');
@Months = ('Januar','Februar','M&auml;rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');


# START DATE GENERATION SUBROUTINE
# If the basic syntax of dates is different in your language - not just the month/day names, but the order of the words - 
# change this subroutine to reflect it.

# If times in your language use a 12 hour (AM/PM) clock, set the following to 1.
# Otherwise, set it to 0.
$nplang_12Hour = 0;

# Set the following to the standard date format in your language. Users will be able to choose
# any date format that they want, but the format below is the default.
# See Change Settings for information on how date formats should be written.
# Edit between q~ and ~;

$nplang_DateFormat = q~ <Field: Weekday> <Field: Day>. <Field: Month_Name> <Field: Year> ~;


# START MESSAGES
%Messages = (

'NewsPro' => q~NewsPro~, # You may want to still call the script NewsPro, or you may want to translate the name.
'ContentType' => q~<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">~, # If your language needs a different character set, you may have to change this tag.
'MainPage_Welcome' => q~Willkommen,~, # Will be used as: Welcome to NewsPro, (name)!
'MainPage_Choose' => q~W&auml;hlen Sie eine der nachfolgenden Startoptionen.~,
# The following should be present in every language file except for the English one. 
# Use this example as a guide; translate it, remove the # from the beginning of the line,
# and delete the empty one below.
'MainPage_LangWarning' => q~Die Grundfunktionen des Programms stehen in deutscher Sprache zur Verf&uuml;gung. Alle Verwaltungsfunktionen nur in englischer Sprache.<br><br>~,
'Section_Submit' => q~Hinzuf&uuml;gen~,
'Section_Build' => q~News erzeugen~,
'MainPage_Descriptions_Submit' => q~Eine neue Nachricht in die Datenbank einfuegen.~,
'MainPage_Descriptions_Build' => q~Generieren der HTML Seiten und der Nachrichtenarchive aus der Datenbank.~,
'MainPage_Descriptions_Build_NoAuto' => q~Damit die neuen Nachrichten auf Ihren Seiten erscheinen, muessen sie erst aus der Datenbank erzeugt werden.~,
'Section_Modify' => q~Nachrichten &auml;ndern~,
'MainPage_Descriptions_Modify' => q~L&ouml;schen oder &auml;ndern von vorher eingegebenen Nachrichten.~,
'MainPage_Modify_1' => q~Sie k&ouml;nnen nur die von Ihnen eingegebenen Nachrichten &auml;ndern.~,
'MainPage_Modify_2' => q~Sie k&ouml;nnen jegliche Nachricht &auml;ndern, auch die, die von anderen Autoren eingegeben wurden.~,
'Section_UserInfo' => q~Benutzerinformation~,
'MainPage_Descriptions_UserInfo' => q~&Auml;ndern des Kennwortes oder der Emailanschrift.~,
'Section_LogOut' => q~Ausgang~,
'MainPage_Descriptions_LogOut' => q~Abmelden; dadurch ist dieses System nur unter Eingabe Ihres Benutzernamens und Kennwortes zug&auml;nglich.~,
'MainPage_YourVersion' => q~Ihre Version:~,
'MainPage_CurrentVersion' => q~Aktuelle Version:~,
'MainPage_Upgrade' => q~Zur Aktualisierung.~,
'MainPage_Addons' => q~Zubeh&ouml;r~,
'MainPage_Download' => q~Programm abladen~,
'MainPage_WebPage' => q~Besuch der Newspro Homepage.~,
'MainPage_SendEmail' => q~Senden Sie eine e-mail mit Ihren Bemerkungen, Fragen, Anregungen, etc.~,
# As help is not translated, the following should read "online help (English only)" when translated.
'HTMLFoot_Help' => q~Hilfestellung (nur in Englisch)~,
'Section_Main' => q~Hauptseite~,
'BackTo' => q~Zur&uuml;ck zu ~, #Used as a link, Back to (site name)
'Controls_Search' => q~Archivsuche:~,
'Controls_View' => q~Alle Nachrichten anzeigen~,
'Controls_Email' => q~Eintrag in die Email-Verteilerliste. Sie bekommen dann die jeweils neuesten Nachrichten per e-Mail.~,
'Controls_EmailTextBox' => q~Ihre e-mail~, # Used in a text box, to show where to enter your e-mail address.
'Controls_Subscribe' => q~Anmelden~,
'Controls_Unsubscribe' => q~Abmelden~,
'Submit_NewsText' => q~Nachrichten~,
'Submit_GlossaryOn' => q~Glossar: Aktiv~,
'Off' => q~Inaktiv~,
'Submit' => q~Absenden~,
'Reset' => q~L&ouml;schen~,
'Save_NewsSaved' => q~Nachricht gespeichert~,
'Save_Message' => q~Die Nachricht wurde in der Datenbank gespeichert. Vergessen Sie nicht, dass die Nachrichten zur Ver&ouml;ffentlichung aus der Datenbank generiert werden m&uuml;ssen. Das Kommando "Ver&ouml;ffentlichen" finden Sie im untenstehenden Menu.~,
'Build_Title' => q~Ver&ouml;ffentlichen~,
'Build_Message' => q~Der HTML-Code der Nachrichten wurde aus der Datenbank erzeugt. Auf Ihren Seiten sollten nun die neuesten Nachrichten sichtbar sein.~,
'Modify_Posted' => q~Datum:~, # Used as: Posted on (date)
'Modify_By' => q~Author:~, # Used as: Posted on (date) by (name)
'Modify_Keep' => q~Speichern:~, # Used as a choice between keeping or removing a post.
'Modify_Remove' => q~L&ouml;schen:~,
'Modify_NoPerm' => q~Sie k&ouml;nnen diese Nachricht nicht &auml;ndern.~,
# The following six messages create one sentence: Save changes and finish modifying news or show next (number) items.
'Modify_Save' => q~&Auml;nderungen speichern~,
'And' => q~und~,
'Modify_Finish' => q~Vorgang beenden~,
'Modify_Or' => q~oder~,
'Modify_Show' => q~Anzeige folgender~,
'Modify_Items' => q~Meldungen~,
'ModifySave_Title' => q~Ver&auml;nderte Nachricht~,
'ModifySave_Message' => q~Ihre &Auml;nderungen (sofern erfolgt) wurden gespeichert. Damit die die neuen / ge&auml;nderten Nachrichten auf Ihren Seiten erscheinen m&uuml;ssen Sie "ver&ouml;ffentlicht" werden.~,
'Login_Is' => q~ist angemeldet.~,
'Login_OKMessage' => q~Ist angemeldet. Ein &quot;cookie&quot; wurde auf ihrem Computer gespeichert, damit Sie sich beim n&auml;chsten Mal nicht mehr extra anmelden m&uuml;ssen. Ihr Browser muss Cookies zu diesem Zweck akzeptieren.~,
'Login_OKClick' => q~Zur Hauptseite.~,
'Login_NoTitle' => q~Benutzername / Kennwort falsch~,
'Login_NoMessage' => q~Die eingegebenen Daten sind nicht g&uuml;ltig. Bitte benutzen Sie die R&uuml;cktaste ihres Browsers um zur vorigen Seite zur&uuml;ckzukehren und es nochmals zu versuchen.~,
'Login_Title' => q~Zugang zum Redaktionssystem f&uuml;r registrierte Benutzer~,
'Username' => q~Benutzername~,
'Password' => q~Kennwort~,
'Login_Login' => q~Eingang~,
'UserInfo_Message' => q~Sie k&ouml;nnen diese zwei Optionen &auml;ndern: Ihr Kennwort und ihre E-Mail-Anschrift.~,
'Email' => q~E-Mail~,
'UserInfoSave_Title' => q~Benutzerangaben ge&auml;ndert~,
'UserInfoSave_Message' => q~Ihre Angaben wurden ge&auml;ndert. Wenn Sie Ihr Kennwort ge&auml;ndert haben, dann m&uuml;ssen Sie sich f&uuml;r weitere Vorg&auml;nge erneut anmelden.~,
'LogOut_Message' => q~ist abgemeldet.~,
'DisplayLink' => q~Nachrichtensystem ~,
'Viewnews_Error_1' => q~Die Datumsangabe ist ung&uuml;ltig.~,
'Viewnews_Error_2' => q~Mit den gew&uuml;nschten Suchkriterien wurden keine Nachrichten gefunden.~,
'Viewnews_From' => q~Nachrichten von~, # These two used as: News from (date) to (date)
'Viewnews_To' => q~bis~,
'Viewnews_Error_3' => q~Anfang / Ende sind ung&uuml;ltig.~,
'Viewnews_Items' => q~Nachrichten~,
'Viewnews_Latest' => q~Letzte Nachrichten~,
'Viewnews_EmailAdd' => q~wurde in den E-Mail-Verteiler aufgenommen.~,
'Viewnews_EmailFail' => q~scheint keine g&uuml;ltige E-Mailanschrift zu sein.~,
'Viewnews_EmailUnsubSuccess' => q~wurde aus dem E-Mail-Verteiler gel&ouml;scht.~,
'Viewnews_EmailUnsubFail' => q~ist nicht im E-Mail-Verteiler gelistet.~,
'Viewnews_EmailIncomplete' => q~Bitte erg&auml;nzen Sie alle Felder.~,
'Viewnews_Mailing' => q~E-mail Newsletter~,
'Viewnews_Success' => q~Erfolg~,
'Viewnews_Failure' => q~Fehler~,
'Viewnews_TMPLError' => q~Die Datei viewnews.tmpl konnte nicht ge&ouml;ffnet werden.~,
'Error' => q~Fehler~,
'Viewnews_NoResults' => q~Zu den Suchbegriffen sind keine Nachrichten vorhanden.~,
'Viewnews_Results' => q~Suchergebnisse~,
'Viewnews_NoOpen' => q~Das folgende Dokument kann nicht ge&ouml;ffnet werden:~, #Used as: could not open file (pathname)
'Category' => q~Kategorie~,
'Preview' => q~Vorschau~
); # The end!

1;