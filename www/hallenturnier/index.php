<?php
  include("/home/fcaarberg/public_html/mainfile.php");
  include("/home/fcaarberg/public_html/hallenturnier/menu.php");

  init();

  openDocument();
  fcaHead();
  openBody(__FILE__);
  // Main content (middle) ...
  startContent();
?>

<h1>Hallenturnier in Kappelen</h1>
<p>Bereits ist das Hallenturnier in Kappelen wieder vorbei. Geblieben sind die tollen Erinnerungen an dieses grosse Event. Ohne einen einzigen Zwischenfall und mit sehr fairen Mannschaften konnte das diesjährige Turnier über die Bühne gehen. Gratulation an alle Teilnehmer!
<br>
Ein grosses Lob muss auch an die Eltern unserer Junioren gehen, die auch in diesem Jahr wieder bereit waren hinter dem Buffet mitzuhelfen. Nicht vergessen werden dürfen unsere A-, B- und C-Junioren, die mit Hilfe einiger älterer Cracks unseres Vereins, die Turnierleitung als Schiedsrichter und Jurymitglieder problemlos im Griff hatten. Ein weiterer Beweis für die unglaublich tolle Juniorenabteilung unseres Vereins.
<br>
Die sportlichen Leistungen aller Teams waren grossartig. Am Ende des Turniers jedoch waren der FC Bethlehem bei den F-Junioren und der FC Aarberg b bei den E-Junioren die besten Teams ihrer Kategorie und konnten sich den Turniersieg sichern.
</p>
<p>Vielen Dank und weiterhin eine gute Saison wünscht<br>
Der KiFu-Verantwortliche <a href="mailto:marco.aebischer@fcaarberg.ch">Marco
  Aebischer</a> </p>

<?php
  endContent();
  startLeftBlock();
  menu();
  endRightBlock();
  closeBody();
  closeDocument();
?>