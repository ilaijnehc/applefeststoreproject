<?php
include("includes/init.php");
$title = "Directions";
$nav_class_directions = "class=active";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Apple Fest</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/theme.css" media="all"/>
  </head>
  <?php include("includes/header.php"); ?>

      <main>
          <h1><?php echo $title; ?></h1>

          <p>Next Fall, we hope we can return to the Annual Apple Harvest Festival during the last weekend in September 2021, but for now we encourage you all to enjoy apples and apples products safely, responsibly, and socially distant.</p>

          <h2>Parking</h2>

          <p>Residents and vistors can park in the Green, Seneca, and Cayuga Street garages and walk to anywhere in Downtown Ithaca.</p>

          <p>Garage parking is $1.00/hour in the garages. On-street parking is $1.50/hour during the week until 6pm.</p>

          <h2>Public Transit</h2>

          <p>If you are arriving by TCAT, good news! </p> <p>There are three major stops around us:</p>
          <ul>
            <li>Ithaca Commons - Seneca St or E. Seneca Street (in front of Starbucks)</li>
            <li>Ithaca Commons - Aurora St or Aurora @ State/MLK</li>
            <li>Ithaca Commons - Green St</li>
          </ul>

          <p>There are also dozens of TCAT routes that stops near us!</p>
          <p>Including: 10, 11, 11S, 13, 13S, 14, 15, 17, 20, 21, 30, 31, 32, 36, 37, 40, 43, 51, 52, 53, 65, 67, 70, 72, and 90.</p>



          <!--src: applefestwebsite, https://www.downtownithaca.com/apple-festive/ -->
          <img src="/public/images/map.jpg" class="map" alt="an image of map at the commons"/>
          Source: <cite><a href="https://www.downtownithaca.com/apple-festive/">Original Apple Fest Site</a></cite>

   </main>
   <?php include("includes/footer.php"); ?>
</html>
