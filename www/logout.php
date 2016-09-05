<?PHP
 $logout = true;
 $cfgProgDir = 'phpSecurePages/';
 include($cfgProgDir."secure.php");

 include("fca_funktionen.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

  <?php html_head(); ?>

  <body>

    <?php
      fca_header();
      separator();
      empty_bar();
      separator();
      navigation_bar();
      separator();
    ?>

    <!-- content goes here -->
    <h1>Auf Wiedersehen!</h1>
    <p align="center">
    Sie haben Sich soeben erfolgreich abgemeldet, falls Sie weitere administrative Arbeiten
    erledigen müssen, oder Sich als neuer Benutzer anmelden wollen - klicken Sie <a href="admin.php">HIER</a>.
    </p>

    <!-- content ends here -->

  <?php
    empty_space();
    separator();
    google_search();
    separator();
    footer();
    separator();
  ?>

</body>
</html>