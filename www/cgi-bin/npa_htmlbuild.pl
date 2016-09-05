push(@Addons_Loaded, 'HTML Builder');
$Addons_List{'HTML Builder 1.0'} = ['npa_htmlbuild.pl', 'Adds several common HTML codes to your NewsPro posts without requiring any knowledge of HTML.', 'http://amphibian.gagames.com/addon.cgi?htmlbuild&1.0'];
push(@Addons_NPHTMLHead_Head, 'JSBut_Head');
push(@Addons_DisplaySubForm_Post4, 'JSBut_Sub');

sub JSBut_Head {
if ($title eq $Messages{'Section_Submit'}) {
	print qq~
<script language="JavaScript">
<!--
var submitval = "";
function setupstyle(){
document.entryform.select_title.value = "Style Type";
document.entryform.select_list.options[0].text = "Bold";
document.entryform.select_list.options[1].text = "Underline";
document.entryform.select_list.options[2].text = "Italicize";
document.entryform.select_list.options[3].text = "Indent";
document.entryform.select_list.options[4].text = "Block Quote";
for (var loop=5; loop <= 11; loop++){document.entryform.select_list.options[loop].text = "";}
document.entryform.text_title.value = "Enter Text to be Styled";
document.entryform.text_entry.value = "";
document.entryform.text2_title.value = "";
document.entryform.text2_entry.value = "";
}
function setupfont(){
document.entryform.select_title.value = "Select Color";
document.entryform.select_list.options[0].text = "No Change";
document.entryform.select_list.options[1].text = "Black";
document.entryform.select_list.options[2].text = "White";
document.entryform.select_list.options[3].text = "Green";
document.entryform.select_list.options[4].text = "Red";
document.entryform.select_list.options[5].text = "Blue";
document.entryform.select_list.options[6].text = "Yellow";
document.entryform.select_list.options[7].text = "Grey";
document.entryform.select_list.options[8].text = "Dark Green";
document.entryform.select_list.options[9].text = "Dark Red";
document.entryform.select_list.options[10].text = "Dark Blue";
document.entryform.select_list.options[11].text = "Hex Value";
document.entryform.text_title.value = "Enter Text to be Styled";
document.entryform.text_entry.value = "";
document.entryform.text2_title.value = "Font Size";
document.entryform.text2_entry.value = "";
}
function setupform(){
document.entryform.select_title.value = "Format Item";
document.entryform.select_list.options[0].text = "Tab";
document.entryform.select_list.options[1].text = "Bullet";
document.entryform.select_list.options[2].text = "Break Line";
document.entryform.select_list.options[3].text = "Table";
for (var loop=4; loop <= 11; loop++){document.entryform.select_list.options[loop].text = "";}
document.entryform.text_title.value = "";
document.entryform.text_entry.value = "";
document.entryform.text2_title.value = "";
document.entryform.text2_entry.value = "";
}
function setuplink(){
document.entryform.select_title.value = "Link Target";
document.entryform.select_list.options[0].text = "Same Window";
document.entryform.select_list.options[1].text = "New Window";
document.entryform.select_list.options[2].text = "No Frames";
for (var loop=3; loop <= 11; loop++){document.entryform.select_list.options[loop].text = "";}
document.entryform.text_title.value = "Enter URL of Page";
document.entryform.text_entry.value = "http://";
document.entryform.text2_title.value = "Page Name";
document.entryform.text2_entry.value = "";
}
function setupmail(){
document.entryform.select_title.value = "";
for (var loop=0; loop <= 11; loop++){document.entryform.select_list.options[loop].text = "";}
document.entryform.text_title.value = "Enter E-mail Address";
document.entryform.text_entry.value = "";
document.entryform.text2_title.value = "Name";
document.entryform.text2_entry.value = "";
}
function setupimage(){
document.entryform.select_title.value = "";
for (var loop=0; loop <= 11; loop++){document.entryform.select_list.options[loop].text = "";}
document.entryform.text_title.value = "Enter Image URL";
document.entryform.text_entry.value = "";
document.entryform.text2_title.value = "";
document.entryform.text2_entry.value = "";
}
function enter(){
if (submitval == 1){style();}
if (submitval == 2){fonts();}
if (submitval == 3){format();}
if (submitval == 4){link();}
if (submitval == 5){mail();}
if (submitval == 6){image();}
}
function printout (output){
if (document.entryform.distype.checked == true){document.submitnews.newstext.value += output;}
else{
document.entryform.select_title.value = "";
for (var loop=0; loop <= 11; loop++){document.entryform.select_list.options[loop].text = "";}
document.entryform.text_title.value = "Output";
document.entryform.text_entry.value = output;
document.entryform.text2_title.value = "";
document.entryform.text2_entry.value = "";
document.entryform.text_entry.select();
submitval = "";
}
}
function style(){
var the_index = document.entryform.select_list.selectedIndex;
var the_text = document.entryform.text_entry.value;
if (the_index == 0){output = ' <B>'+the_text+'</B> ';}
if (the_index == 1){output = ' <U>'+the_text+'</U> ';}
if (the_index == 2){output = ' <I>'+the_text+'</I> ';}
if (the_index == 3){output = ' <UL>'+the_text+'</UL> ';}
if (the_index == 4){output = ' <BLOCKQUOTE>'+the_text+'</BLOCKQUOTE> ';}
printout(output);
}
function fonts(){
var the_index = document.entryform.select_list.selectedIndex;
var the_text = document.entryform.text_entry.value;
var the_text2 = document.entryform.text2_entry.value;
var fontcolor = "";
if (the_index == 1){fontcolor = ' color="#000000"';}
if (the_index == 2){fontcolor = ' color="#FFFFFF"';}
if (the_index == 3){fontcolor = ' color="#00FF00"';}
if (the_index == 4){fontcolor = ' color="#FF0000"';}
if (the_index == 5){fontcolor = ' color="#0000FF"';}
if (the_index == 6){fontcolor = ' color="#FFFF00"';}
if (the_index == 7){fontcolor = ' color="#808080"';}
if (the_index == 8){fontcolor = ' color="#008000"';}
if (the_index == 9){fontcolor = ' color="#800000"';}
if (the_index == 10){fontcolor = ' color="#000080"';}
if (the_index == 11){
	var source = prompt( "Enter Color Hex Value:", "#" ); 
	if ((source != "")&&(source != null)&&(source != "#")){fontcolor = ' color="'+source+'"';}
}
var source2 = prompt( "Enter Font Face (ex: Verdana, Arial, Helvetica, sans-serif):", "" );
var fontface = "";
if ((source2 != "")&&(source2 != null)){fontface = ' face="'+source2+'"'}
var fontsize = "";
if (the_text2 != ""){fontsize = ' size="'+the_text2+'"'}
output = ' <font'+fontface+fontsize+fontcolor+'>'+the_text+'</font> ';
printout (output);
}
function format(){
var the_index = document.entryform.select_list.selectedIndex;
output = "";
if (the_index == 0){output = '&nbsp;&nbsp;&nbsp;&nbsp;';}
if (the_index == 1){output = '<LI>';}
if (the_index == 2){output = '<HR>';}
if (the_index == 3){
var R = prompt("Enter the number of Rows:","");
var C = prompt("Enter the number of Columns:","");
if(R&&C){output = '<TABLE>\\n';
for(loop = 0; loop < R; loop++){output += '<TR>\\n';
for(loop2 = 0; loop2 < C; loop2++){output += '<TD> </TD>\\n';}
output += '</TR>\\n';}
output += '</TABLE>';
}}
printout(output);
}
function link(){
var the_index = document.entryform.select_list.selectedIndex;
var the_text = document.entryform.text_entry.value;
var the_text2 = document.entryform.text2_entry.value;
var linktarget="";
if (the_index == 1){var linktarget = ' target="_blank" ';}
if (the_index == 2){var linktarget = ' target="_top" ';}
output = ' <a href="'+the_text+'"'+linktarget+'>'+the_text2+'</a> ';
printout (output);
}
function mail(){
var the_text = document.entryform.text_entry.value;
var the_text2 = document.entryform.text2_entry.value;
output = ' <a href="mailto:'+the_text+'">'+the_text2+'</a> ';
printout (output);
}
function image(){
var the_text = document.entryform.text_entry.value;
output = '<img src="'+the_text+'">';
printout (output);
}
// --> 
</script>
	~;
}
}

sub JSBut_Sub {
print qq~
<center><form name="entryform" onSubmit="enter(); return false;">
<B>HTML Builder</B>
<table><tr><td valign="top" rowspan="2"><table>
<tr><td><input type="button" name="Text Styles" value="Text Styles" onclick="submitval=1; setupstyle();"></td></tr>
<tr><td><input type="button" name="Font Styles" value="Font Styles" onclick="submitval=2; setupfont();"></td></tr>
<tr><td><input type="button" name="Formating" value="Formating" onclick="submitval=3; setupform();"></td></tr>
<tr><td><input type="button" name="Link" value="Link" onclick="submitval=4; setuplink();"></td></tr>
<tr><td><input type="button" name="E-mail" value="E-mail" onclick="submitval=5; setupmail();"></td></tr>
<tr><td><input type="button" name="Image" value="Image" onclick="submitval=6; setupimage();"></td></tr>
</table></td>
<td valign="top">
<table>
<tr><td colspan=3><input type="text" size=15 name="select_title" onFocus="document.entryform.select_list.focus();"></td></tr>
<tr><td colspan=3><select size="4" name="select_list">
<option>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
<option></option>
</select></td></tr>
<tr><td><input type="submit" name="Enter" value="Enter"></td>
<td><input type="checkbox" name="distype" checked=true></td>
<td><font size=1 face="Arial, Helvetica, sans-serif">Add code to <br><I>News Text</I></font></td>
</tr></table></td>
<td valign=top><table>
<tr><td><input type="text" size=24 name="text_title" onFocus="document.entryform.text_entry.focus();"></td></tr>
<tr><td><textarea cols=20 rows=6 name="text_entry"></textarea></td></tr>
</table></td>
<td valign=top><table>
<tr><td><input type="text" size=12 name="text2_title" onFocus="document.entryform.text2_entry.focus();"></td></tr>
<tr><td><textarea cols=10 rows=6 name="text2_entry"></textarea></td></tr>
</table></td>
</tr>
<td colspan=3><font size=1 face="Arial, Helvetica, sans-serif">
* Push the button from the left menu for the type of code you want to make.<BR>
* Enter values into the required fields.<BR>
* Press the <B>ENTER</B> button to display the code OR add it to the end of your <I>News Text</I>.
</font></td>
</tr></table>
</form></center>
~;
}
1;