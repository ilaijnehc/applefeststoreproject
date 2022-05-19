<?php
include("includes/init.php");
$title = "Vendors Login";
$nav_class_vendors = "class=active";

$password_feedback_class = 'hidden';
$show_form = True;
$show_confirmation = False;

// upload form feedback
$desc_feedback_class = 'hidden';
$file_feedback_class = 'hidden';
$name_feedback_class = 'hidden';
$price_feedback_class = 'hidden';
$tag_feedback_class = 'hidden';

// upload form sticky ?
$sticky_desc = '';
$sticky_name = '';
$sticky_price = '';
$sticky_source = '';

// Users must be logged in to upload files!
if (isset($_POST["upload"])) {
  $upload_name = trim($_POST['name']); // untrusted
  $upload_desc = trim($_POST['desc']); // untrusted
  $upload_price = trim($_POST['price']); // untrusted
  $upload_source = trim($_POST['source']);

  $upload = $_FILES['img-file'];
  // Assume the form is valid...
  $form_valid = True;

  // file is required
  // TODO: check if file upload was successful (UPLOAD_ERR_OK)
  if ($upload['error'] == UPLOAD_ERR_OK){
    $upload_filename = basename($upload['name']);
    $upload_ext = strtolower( pathinfo($upload_filename, PATHINFO_EXTENSION) );

    if(!in_array($upload_ext, array('jpg'))){
      $form_valid = False;
    }
  } else {
    $form_valid = False;
  }

  // name is required
  if (empty($upload_name)) {
    $form_valid = False;
    $name_feedback_class = '';
  }

  // description is required
  if (empty($upload_desc)) {
    $form_valid = False;
    $desc_feedback_class = '';
  }

  // price is required
  if (empty($upload_price)) {
    $form_valid = False;
    $price_feedback_class = '';
  }

  // Record NULL (not empty string)
  if (empty($upload_source)) {
    $upload_source = NULL;
  }

  if ($form_valid) {
    $db->beginTransaction();
    $show_form=False;
    $show_confirmation = True;

    // insert upload into DB
    $result = exec_sql_query(
      $db,
      "INSERT INTO products (user_id, name, price, description, filename, file_ext, source) VALUES (:user_id, :name, :price, :description, :filename, :file_ext, :source)",
      array(
        ':user_id' => $current_user['id'],
        ':name' => $upload_name,
        ':price' => $upload_price,
        ':description' => $upload_desc,
        ':filename' => $upload_filename,
        ':file_ext' => $upload_ext,
        ':source' => $upload_source
      )
    );

    if ($result) {
      // We successfully inserted the record into the database, now we need to
      // move the uploaded file to it's final resting place: uploads directory

      // TODO: get primary key of last database insert
      $record_id = $db->lastInsertId('db');
      // TODO: Move the file to the uploads/documents folder
      $id_filename = 'public/uploads/products/' . $record_id . '.' . $upload_ext;

      move_uploaded_file($upload['tmp_name'],$id_filename);
    }

    $db->commit();
  } else {
    // file uploads are not sticky!
    // user must reselect file, show file feedback when showing feedback!
    $file_feedback_class = '';

    $sticky_desc = $upload_desc;
    $sticky_name = $upload_name;
    $sticky_price = $upload_price;
    $sticky_source = $upload_source;
  }
}

define("MAX_FILE_SIZE", 1000000);

//adding tag - server side
if (isset($_POST['add-tag'])){
  $tag_name = trim($_POST['new-tag']);

  exec_sql_query(
    $db,
    "INSERT INTO tags (name) VALUES (:tag);",
    array(':tag' => $tag_name)
  );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $title; ?></title>

  <link rel="stylesheet" type="text/css" href="/public/styles/theme.css" media="all" />
</head>

<body>
<?php include("includes/header.php"); ?>

<main>
  <h1><?php echo $title; ?></h1>
  <?php if(!is_user_logged_in()) { ?>
    <p>Are you a vendor? Please log in your information to access editing features.</p>
    <?php echo_login_form('/vendors', $session_messages); ?>

  <?php } else {?>
  <?php $user_record = find_user($db,current_user()['id'])?>
  <h2>
    Welcome, <?php echo $user_record['username']?>! </h2>
    <li><h2><a href="/store">Click here for store/gallery</a></h2></li>

    <?php if (is_user_logged_in()) { ?>

        <li><h2><a href="<?php echo logout_url(); ?>">Click here to sign out</a></h2></li>
        <p>On this page you may also upload a new entry or introduce a brand new tag to the catalog!</p>

        <hr/>
        <?php if($show_form) { ?>
        <h2>Upload A New Product</h2>
        <p>Got a new product? Upload here! Items with * are required.</p>
        <form action="/vendors" method="post" enctype="multipart/form-data" novalidate>
          <!-- MAX_FILE_SIZE must precede the file input field -->
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />

          <div class="form-triple">
          <p class="feedback <?php echo $file_feedback_class; ?>">Please select an JPG file.</p>
          <div class="form-double">
            <label for="upload-file">Image(JPG)*:</label>
            <input id="upload-file" type="file" name="img-file" accept=".jpg" required />
          </div>
          </div>

          <div class='form-triple'>
          <p class="feedback <?php echo $name_feedback_class; ?>">Please provide a name.</p>
          <div class="form-double">
            <label for="upload-name">Product Name*:</label>
            <input id='upload-name' type="text" name="name" value="<?php echo htmlspecialchars($sticky_name); ?>" required />
          </div>
          </div>

          <div class='form-triple'>
          <p class="feedback <?php echo $price_feedback_class; ?>">Please provide a price.</p>
          <div class="form-double">
            <label for="upload-price">Price*:</label>
            <input id='upload-price' type="number" name="price" value="<?php echo htmlspecialchars($sticky_price); ?>" required />
          </div>
          </div>

          <div class="form-triple">
          <p class="feedback <?php echo $desc_feedback_class; ?>">Please provide a description.</p>
          <div class="form-double">
            <label for="upload-desc">Description*:</label>
            <textarea id="upload-desc" name="desc" rows="5" placeholder="Product description." required><?php echo htmlspecialchars($sticky_desc); ?></textarea>
          </div>
          </div>

          <div class="form-double">
            <label for="upload-source" class="optional">Source URL:</label>
            <input id='upload-source' type="url" name="source" placeholder="URL where found. (optional)" value="<?php echo htmlspecialchars($sticky_source); ?>" />
          </div>

          <div class="align-right">
            <button type="submit" name="upload">Upload Product</button>
          </div>
        </form>
        <?php } ?>

        <?php if($show_confirmation) { ?>
          <p>Congratulations! Your product is successfully loaded onto our gallery. Navigate to store page for more information.</p>

        <?php } ?>

        <!-- Adding tag - client side -->
        <hr/>
        <h2>Register a New Tag</h2>
        <p>Can't find a current tag to describe your newest product? Add one here!</p>
        <form action="/vendors" method="post" novalidate>

        <div class="form-triple">
          <p class="feedback <?php echo $tag_feedback_class; ?>">Please provide a tag.</p>
          <div class="form-double">
            <label for="new-tag">Tag Name:</label>
            <input id='new-tag' type="text" name="new-tag" value="<?php echo htmlspecialchars($sticky_tag); ?>" required />
          </div>
        </div>

        <div class="align-right">
            <button type="submit" name="add-tag">Add Tag to Database</button>
        </div>

        </form>
      <?php } ?>
  </p>

  <?php } ?>
</main>

</body>
<?php include("includes/footer.php"); ?>
</html>
