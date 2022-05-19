<?php
include("includes/init.php");
$nav_store_class = "current_page";

// which item should we show? get item ID param
$item_id = (int)trim($_GET['id']);
$url = "/store/item?" . http_build_query(array('id' => $item_id));

$edit_mode = False;
$edit_authorization = False;

// are we in editing mode?
if (isset($_GET['edit'])) {
  $edit_mode = True;

  // edit param value is also item ID
  $item_id = (int)trim($_GET['edit']);
}

// find the item record
if ($item_id) {
  $records = exec_sql_query(
    $db,
    "SELECT * FROM products WHERE id = :id;",
    array(':id' => $item_id)
  )->fetchAll();
  if (count($records) > 0) {
    $item = $records[0];
  } else {
    $item = NULL;
  }
}

// Only continue if we have a valid item
if ($item) {
  // Does this user have permission to edit this item?
  // Only the owner of the item may edit it
  if (current_user()['id'] == $item['user_id']){
    $edit_authorization = TRUE;
  }

  // was the item edited?
  if($edit_authorization){
  if (isset($_POST['save'])) {
    $name = trim($_POST['name']); // untrusted
    $description = trim($_POST['description']); // untrusted
    $price = trim($_POST['price']);
    $source = trim($_POST['source']);

  // If name/description/price is not empty, update it.
    if (!empty($name) || !empty($description) || !empty($price) || !empty($source)) {
      exec_sql_query(
        $db,
        "UPDATE products SET name = :name, description = :description, price = :price, source=:source WHERE (id = :id);",
        array(
          ':id' => $item_id,
          ':name' => $name,
          ':description' => $description,
          ':price' => $price,
          ':source' => $source
        )
      );

        // get updated item
        $records = exec_sql_query(
          $db,
          "SELECT * FROM products WHERE id = :id;",
          array(':id' => $item_id)
        )->fetchAll();
        $item = $records[0];
      }
    }
  // did user add tag?
  if (isset($_POST['add_tag'])){
    $tag_option = $_POST['tagOptions'];
    // var_dump($tag_option);

    exec_sql_query(
      $db,
      "INSERT INTO product_tags (product_id, tag_id) VALUES (:product_id, :tag_id);",
      array(':product_id' => $item_id, ':tag_id' => $tag_option)
    )->fetchAll();
  }
  // did user delete tag?
  if (isset($_POST['delete_tag'])){
    $tag_option = $_POST['del_tagOptions'];
    // var_dump($tag_option);

    exec_sql_query(
      $db,
      "DELETE FROM product_tags WHERE (product_id = :product_id) AND (tag_id = :tag_id);",
      array(':product_id' => $item_id, ':tag_id' => $tag_option)
    )->fetchAll();
  }

  //delete product - server
  if (isset($_POST['delete_product'])){
    //var_dump($item_id);
    exec_sql_query(
      $db,
      "DELETE FROM products WHERE (id = :product_id);",
      array(':product_id' => $item_id)
    );
    exec_sql_query(
      $db,
      "DELETE FROM product_tags WHERE (id = :product_id);",
      array(':product_id' => $item_id)
    );
    //retrieve updated item
    $records = exec_sql_query(
      $db,
      "SELECT * FROM products WHERE id=:id;",
      array(':id' => $item_id)
    )->fetchAll();
    $item=$records[0];
    //unlink file path

    // $id_filename =  'public/uploads/products/' . $item_id . '.jpg';
    // var_dump($id_filename);
    // unlink($id_filename);
  }
  } // end of if edit_auth

  // item information
  $title = htmlspecialchars($item['name']);
  $url = "/store/item?" . http_build_query(array('id' => $item['id']));
  $edit_url = "/store/item?" . http_build_query(array('edit' => $item['id']));
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

  <main class="item">
      <h1>Product Item: <?php echo $title; ?></h1>
      <hr/>
      <br/>
        <div class="center">
        <?php if ($item) { ?>
        <img src="/public/uploads/products/<?php echo $item['id'] . '.' . $item['file_ext']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />

        <?php if ($edit_mode) { ?>
        <!-- Note: form needs feedback messages. -->
        <form class="edit" action="<?php echo $url; ?>" method="post" novalidate>
          <div class="form-quad">
          <div class="form-double">
            <label for="edit-name"> Name: </label>
              <input id="edit-name"type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required />
          </div>
         </div>


        <div class="form-quad">
        <div class="form-double">
          <label for="edit-price"> Price: </label>
            <input id="edit-price" type="number" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required />
        </div>

        <div class="form-quad">
        <div class="form-double">
          <label for="edit-description"> Description: </label>
            <textarea for="edit-description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>
        </div>

        <div class="form-quad">
          <div class="form-double">
            <label for="edit-source"> Source: </label>
              <input id="edit-source"type="text" name="source" value="<?php echo htmlspecialchars($item['source']); ?>" required />
          </div>
         </div>

        <div class="align-right">
          <button type="submit" name="save">Save</button>
        </form>
        </div>
          <?php
        } else { ?>
          <h3>Name:
            <?php echo htmlspecialchars($item['name']); ?>
            <?php if($edit_authorization) { ?>
            (<a href="<?php echo $edit_url; ?>">Edit</a>)
            <?php } ?>
          </h3>

          <h3>Price: $
            <?php echo htmlspecialchars($item['price']); ?>
            <?php if($edit_authorization) { ?>
            (<a href="<?php echo $edit_url; ?>">Edit</a>)
            <?php } ?>
          </h3>

          <p>Tags:
            <!-- RETRIEVING TAGS -->
            <?php
            $tag_records = exec_sql_query(
              $db,
              "SELECT DISTINCT tags.name FROM tags INNER JOIN product_tags ON tags.id=product_tags.tag_id INNER JOIN products ON product_tags.product_id=products.id WHERE (products.id=:id);",
              array(':id' => $item_id)
            )->fetchAll();
            // if there are multiple tags?
            if (count($tag_records) > 0) {
              $item_tags = $tag_records;
            } else {
              $item_tags = NULL;
            }
            // putting comma in between tags to make more readable
            if($item_tags){
              $item_count = 1;
              $total_count = count($item_tags);
              foreach ($item_tags as $item_tag){?>
              <a><?php echo htmlspecialchars($item_tag[0]); ?></a>
              <?php if ($item_count != $total_count) {echo ", ";} ?>
              <?php  $item_count = $item_count + 1;
              }
            } ?>
            <!-- ADD TAG FORM -->
            <?php if($edit_authorization) { ?>
              <form class="tag_form" action="<?php echo $url; ?>" method="post" novalidate>
              <label for="tags">Select Tag To Add:</label>
              <select name="tagOptions" id="tag_dropdown">
                <option value="0">Choose From Tags</option>
                <?php
                $tags = exec_sql_query($db,"SELECT name, id FROM tags")->fetchAll();
                foreach($tags as $tag){
                    if(!empty($tag[0])){?>
                    <option value="<?php echo htmlspecialchars($tag['id']) ?>"><?php echo htmlspecialchars($tag['name']) ?></option>
                <?php } } ?>
              </select>
              <button type="submit" class="submit" name="add_tag" id="add_tag">Add Tag</button>
              </form>

            <!-- DELETE TAG FORM -->
              <form class="tag_form" action="<?php echo $url; ?>" method="post" novalidate>
              <label for="tags">Select Tag To Delete:</label>
              <select name="del_tagOptions" id="tag_dropdown">
                <option value="0">Choose From Tags</option>
                <?php
                $tags = exec_sql_query($db,"SELECT name, id FROM tags")->fetchAll();
                foreach($tags as $tag){
                    if(!empty($tag[0])){?>
                    <option value="<?php echo htmlspecialchars($tag['id']) ?>"><?php echo htmlspecialchars($tag['name']) ?></option>
                <?php } } ?>
              </select>
              <button type="submit" class="submit" name="delete_tag" id="delete_tag">Delete Tag</button>
              </form>

          <?php }?>
          </p>
          <p>Description:
            <?php echo htmlspecialchars($item['description']); ?>
            <?php if($edit_authorization) { ?>
            (<a href="<?php echo $edit_url; ?>">Edit</a>)
            <?php } ?>
          </p>

          <p>Source:
            <?php echo htmlspecialchars($item['source']); ?>
            <?php if($edit_authorization) { ?>
            (<a href="<?php echo $edit_url; ?>">Edit</a>)
            <?php } ?>
          </p>
          <?php
        } ?>
        <!-- delete product -- client side -->
        <?php if($edit_authorization) { ?>
          <form class="tag_form" action="<?php echo $url; ?>" method="post" novalidate>
          <button type="submit" class="submit" name="delete_product" id="delete_product">Delete This Product</button>
          </form>
          <!-- <?php var_dump($item_id);?> -->
        <?php } ?>
<?php } else{ ?>
      <p>Sorry, the item you are looking for is out of stock. </p>
      <p>Stay tuned on the site gallery in case your favorite shop restocks on the product!</p>
<?php } ?>

  <br/>
    <div class="back">
      <a href="/store">Back To Catalog</a>
    </div>
  </div>

  </main>

  <?php include("includes/footer.php"); ?>
</body>

</html>
