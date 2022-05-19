<?php
include("includes/init.php");
$title = "COVID";
$nav_class_covid = "class=active";

// form CSS classes
$show_form = True;
$show_confirmation = False;

// feedback message CSS classes
$name_feedback_class = 'hidden';
$email_feedback_class = 'hidden';
$location_feedback_class = 'hidden';
$date_feedback_class = 'hidden';
$time_feedback_class = 'hidden';
$number_feedback_class = 'hidden';

// values
$name = '';
$email = '';
$location = '';
$date ='';
$time = '';
$number ='';

// sticky values
$sticky_location = '';
$sticky_name = '';
$sticky_email = '';
$sticky_date = '';
$sticky_time = '';
$sticky_number = '';

// Did the user submit the form?
if (isset($_POST["submit"])) {
  //Get HTTP request user data
  $name = $_POST["name"]; // untrusted
  $email = $_POST["email"]; // untrusted
  $location = $_POST["location"]; //untrusted
  $date = $_POST["date"];
  $time = $_POST["time"];
  $number = $_POST["number"]; //untrusted

  $form_valid = TRUE;

  // name is required. Is it empty?
  if ( empty($name) ) {
    $form_valid = FALSE;
    $name_feedback_class = '';
  }
  // email is required. Is it empty?
  if ( empty($email) ) {
    $form_valid = FALSE;
    $email_feedback_class = '';
  }

  // date is required. Is it empty?
  if ( empty($date)){
      $form_valid = FALSE;
      $date_feedback_class = '';
  }

  // time is required. Is it empty?
  if ( empty($time)){
      $form_valid = FALSE;
      $time_feedback_class = '';
  }

  // number of people is required. Is it empty?
  if (empty($number)){
      $form_valid = FALSE;
      $number_feedback_class = '';
  }

  if ($form_valid) {
    // TODO: form is valid, hide form, show confirmation page
    $show_form =  False;
    $show_confirmation = True;
  } else {
    // TODO: form is invalid, set sticky values
    $sticky_location = $location;
    $sticky_name = $name; // tainted
    $sticky_email = $email; // tainted
    $sticky_date = $date; // tainted
    $sticky_time = $time; // tainted
    $sticky_number = $number; // tainted
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Apple Fest</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/theme.css" media="all"/>

    <script src="scripts/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="scripts/validation.js" type="text/javascript" async></script>

  </head>
  <?php include("includes/header.php"); ?>

<main>
    <h1><?php echo $title; ?></h1>
          <?php if($show_confirmation) {?>
            <div>
            <h2>RSVP Confirmation</h2>
            <p>Thanks for completing the interest form,<strong> <?php echo htmlspecialchars($name)?> </strong>! We have noted your reservation of <strong> <?php echo htmlspecialchars($number)?> </strong> people. We will send you a confirmation email to <strong> <?php echo htmlspecialchars($email)?> </strong>as well. Please confirm that you are visiting <strong> <?php echo htmlspecialchars($location)?> </strong>and your appointment is at <strong> <?php echo htmlspecialchars($time)?> </strong>on <strong><?php echo htmlspecialchars($date)?> </strong>. You don't have to arrive exactly at that time, but please do your best to stick to the plan as that would help us enforce social distancing guidelines! </p>
          </div>
        <?php } ?>
        <?php if ($show_form) {?>
            <div>
            <h2>Help us maintain social distance by filling out the following interest form!</h2>
            <p>Fields marked * are required</p>
            <form id="request-form" method="post" action="/covid" novalidate>

              <div class="form-triple">
                <div class="form-double">
                <label for="request-name">Name*:</label>
                <input type="text" name="name" id="request-name" value="<?php echo htmlspecialchars($sticky_name);?>"/>
                </div>
                <div id="feedback-name" class="feedback <?php echo $name_feedback_class; ?>">*Please enter a name</div>
              </div>

              <div class="form-triple">
                <div class="form-double">
                <label for="request-email">Email*:</label>
                <input type="email" name="email" id="request-email" value="<?php echo htmlspecialchars($sticky_email);?>"/>
              </div>
                <div id="feedback-email" class="feedback <?php echo $email_feedback_class; ?>">*Please enter a valid email address.</div>
              </div>

              <div class="form-triple">
                <div class="form-double">
                <label for="request-location">Location:</label>
                <select name="location" id="request-location">

                  <option <?php if($sticky_location=="Apple Fest") echo 'selected'; ?> value="Apple Fest">Apple Fest</option>
                  <option <?php if($sticky_location=="Ithaca Commons") echo 'selected'; ?> value="Ithaca Commons">Ithaca Commons</option>
                  <option <?php if($sticky_location=="Downtown Ithaca") echo 'selected'; ?> value="Downtown Ithaca">Downtown Ithaca</option>
                  <option <?php if($sticky_location=="Apple Trails") echo 'selected'; ?> value="Apple Trails">Apple Trails</option>
                </select>
                </div>
                <div id="feedback-location" class="feedback <?php echo $location_feedback_class; ?>">*Please select a location.</div>
              </div>

              <div class="form-triple">
                <div class="form-double">
                <label for="request-date">Date*:</label>
                <input type="date" name="date" id="request-date" value="<?php echo htmlspecialchars($sticky_date);?>"/>
                </div>
                <div id="feedback-date" class="feedback <?php echo $date_feedback_class; ?>">*Please select a date.</div>
              </div>

              <div class="form-triple">
                <div class="form-double">
                <label for="request-time">Time*:</label>
                <input type="time" name="time" id="request-time" value="<?php echo htmlspecialchars($sticky_time);?>"  />
                </div>
                <div id="feedback-time" class="feedback <?php echo $time_feedback_class; ?>">*Please enter a time.</div>
              </div>

              <div class="form-triple">
                <div class="form-double">
                <label for="request-number">Number of People*:</label>
                <input type="number" name="number" id="request-number" value="<?php echo htmlspecialchars($sticky_number);?>"/>
                </div>
                <div id="feedback-number" class="feedback <?php echo $number_feedback_class; ?>">*Please enter at least 1 person.</div>
              </div>

              <div class="align-right">
                <button id="request-submit" type="submit" class="submit" name="submit">Submit</button>
              </div>
            </form>
            </div>
          <?php } ?>
          <hr/>

          <h2>Please remain socially distant</h2>
          <p>During the day, a couple of farms will have pop-up farm stands each afternoon on the commons to sell pre-packed Apples, Cider and other fall produce for contactless grab & go.</p>

          <p>Lines with 6' or more of social distance between customers will be in place at the farm stands and all customers and farmers are required to wear masks.</p>

          <p>Hand sanitizer is stationed around the Ithaca Commons, and patrons are encouraged to bring their own for personal use as well.</p>
    </main>
    <?php include("includes/footer.php"); ?>

</html>
