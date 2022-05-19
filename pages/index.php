<?php
include("includes/init.php");
$title = "Home Page";
$nav_class_index = "class=active";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>Apple Fest</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/theme.css" media="all"/>
  </head>

  <?php include("includes/header.php"); ?>

      <main>
        <article>
          <div class="text">
          <h1><?php echo $title; ?></h1>
          <h2>About Us</h2>

          <p>Downtown Ithaca will welcome a couple farmers to sell their harvest bounty throughout the week in a socially distant, de-densified version of a farmers market and cider trail happening on the Commons and inside downtown shops and restaurants in the district.</p>

          <p>Shops and restaurants who have safely reopened may feature apple & cider themed menus and gifts for sale too. The collection outlined on a Cider Trail. </p>

          <p>As part of the Cider Trail experience, participants can share Instagram and Facebook photos throughout the week and enter to win a Downtown Ithaca Gift card when they hashtag their photos with #FeelingAppleFestive & #DowntownIthaca </p>

          <p>Next Fall, we hope we can return to the Annual Apple Harvest Festival during the last weekend in September 2021, but for now we encourage you all to enjoy apples and apples products safely, responsibly, and socially distant.</p>
          </div>

          <!--src: applefestwebsite, https://www.downtownithaca.com/apple-festive/ -->
          <img src="/public/images/cider.jpg" class="portrait" alt="A picture of some apple cider"/>
       </article>
       <div class="citation">
        Source: <cite><a href="https://www.downtownithaca.com/apple-festive/">Original Apple Fest Site</a></cite>
       </div>
     <hr/>

     <article>
            <div class="text">
              <h2>Upcoming Events</h2>

              <h3>Apple Harvest Open Air Farmers Market</h3>

              <p>Monday, Sept. 28 - Friday, Oct. 2, 2020 12:00pm - 4:00pm</p>
            </div>

            <!--src:eventcrazy.com, https://www.eventcrazy.com/Ithaca-NY/events/details/12334-Ithaca-Apple-Harvest-Festival-->
            <img src="/public/images/apples.jpg" class="landscape" alt="A picture of some apples"/>
          </article>
          <div class='citation'>
            Source: <cite><a href="htthttps://www.eventcrazy.com/Ithaca-NY/events/details/12334-Ithaca-Apple-Harvest-Festival">Eventcrazy.com</a></cite>
          </div>

        <hr/>

          <article>
            <div class="text">
              <h2>Ongoing Marketplaces</h2>

              <h3>Open Air Apple Farmers Market on the Commons</h3>

              <p>Monday, Sept. 28 - Friday, Oct. 2 | 12:00pm - 4:00pm</p>
            </div>

            <!--src: applefestwebsite, https://www.downtownithaca.com/apple-festive/ -->
            <img src="/public/images/appleproducts.jpg" class="landscape" alt="A picture of apple product vendors with bottles of cider"/>
          </article>
          <div class='citation'>
            Source: <cite><a href="https://www.downtownithaca.com/apple-festive/">Original Apple Fest Site</a></cite>
          </div>
          <br/>
          <br/>
          <article>
            <div class="text">
              <h3>2020 Apple & Cider Trail inside Shops & Restaurants around Downtown ithaca</h3>

              <p>Monday, Sept. 28 - Oct. 4 | Store hours will vary</p>
            </div>

            <!--src: applefestwebsite, https://www.downtownithaca.com/apple-festive/ -->
            <img src="/public/images/cidernapples.jpg" class="landscape" alt="A picture of apple product vendors with bags of apple"/>

          </article>
          <div class='citation'>
            Source: <cite><a href="https://www.downtownithaca.com/apple-festive/">Original Apple Fest Site</a></cite>
          </div>

   </main>

   <?php include("includes/footer.php"); ?>

</html>
