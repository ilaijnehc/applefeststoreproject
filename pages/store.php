<?php
include("includes/init.php");
$title = "Store";
$nav_class_store = "class=active";

$sql_select_query = "SELECT * FROM products;";
$sql_select_params = array();

// --- Search ---

// form values
$search_terms = NULL;

// sticky values
$sticky_search = '';

// search submitted
if (isset($_GET['q'])) {
  // trim leading and trailing spaces on http parameters.
  $search_terms = trim($_GET['q']); // untrusted

  // If empty string, set to NULL
  if (empty($search_terms)) {
    $search_terms = NULL;
  }

  $sticky_search = $search_terms; // tainted

  // SQL query
  $sql_select_query = "SELECT * FROM products WHERE (name LIKE '%' || :search || '%') OR (description LIKE '%' || :search || '%');";
  $sql_select_params = array(':search' => $search_terms);
}

// --- Sort ----

$sort = $_GET['sort']; // untrusted

$sort_css_classes = array(
  'alphabet' => '',
  'high' => '',
  'low' => ''
);

// do we have a valid value to sort?
if (in_array($sort, array('alphabet','high','low'))) {

  $sql_select_query = "SELECT * FROM products";

  if ($sort == 'alphabet') {
    $sql_select_query = $sql_select_query . ' ORDER BY name ASC;';
    $sort_css_classes['alphabet'] = 'active';
  } else if ($sort == 'high') {
    $sql_select_query = $sql_select_query . ' ORDER BY price DESC;';
    $sort_css_classes['high'] = 'active';
  } else if ($sort == 'low') {
    $sql_select_query = $sql_select_query . ' ORDER BY price ASC;';
    $sort_css_classes['low'] = 'active';
  } else {
  // just in case we have a untrusted value in $sort.
  $sort = NULL;
}
}
// -- Filter by tags --
// with dropdown
if (isset($_GET['apply_filter'])){
  $tag_id = $_GET['tagOptions'];
  $sql_select_query =  "SELECT products.id, products.file_ext, products.name, products.price FROM products INNER JOIN product_tags ON products.id=product_tags.product_id INNER JOIN tags ON product_tags.tag_id=tags.id WHERE (tags.id = $tag_id)";
  //var_dump($sql_select_query);
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

  <main id="storegallery">
    <h1><?php echo $title; ?></h1>
    <div class="main-container">
    <aside>
    <div class="container">
    <div class="item1">
        <h4>Search Keywords</h4>
        <form id="search-form" action="/store" method="get" novalidate>
        <label for="filter-keyword" class="tag" id="tag-search">Keyword:</label>
        <input id="filter-keyword" type="text" name="q" required value="<?php echo htmlspecialchars($sticky_search); ?>" />
        <button id="search-button" class="align-right-tag" type="submit">Search</button>
    </form>
    </div>
    <div class="item2">
        <h4>Sort By</h4>
        <ul>
        <li><a class="<?php echo $sort_css_classes['alphabet']; ?>" href="/store?sort=alphabet">Alphabetical</a></li>
        <li><a class="<?php echo $sort_css_classes['high']; ?>" href="/store?sort=high">Most Expensive</a> </li>
        <li><a class="<?php echo $sort_css_classes['low']; ?>" href="/store?sort=low">Least Expensive</a></li>
        </ul>
    </div>
    <div class="item3">
        <h4>Filter by Tags</h4>

        <!-- Filter by tags using dropdown -->
        <form action="/store" method="get" novalidate>
          <label for="tags" class="tag">Filter Tag:</label>
          <select name="tagOptions" id="tag_dropdown">
            <option value="0">Choose From Tags</option>
            <?php
            $tags = exec_sql_query($db,"SELECT name, id FROM tags")->fetchAll();
            foreach($tags as $tag){
                if(!empty($tag[0])){?>
                <option value="<?php echo htmlspecialchars($tag['id']) ?>"><?php echo htmlspecialchars($tag['name']) ?></option>
            <?php } } ?>
          </select>
          <button type="submit" class="align-right-tag" name="apply_filter">Filter</button>
        </form>

    </div>

    </aside>

    <section class="gallery-sect">
    <?php
      // query the database for the product records
      $records = exec_sql_query(
        $db,
        $sql_select_query,
        $sql_select_params
      )->fetchAll();
      // var_dump($sql_select_query);
      // Only show the items gallery if we have records to display.
      if (count($records) > 0) { ?>
      <?php foreach ($records as $record) { ?>
        <div class="gallery">
                <a href="/store/item?<?php echo http_build_query(array('id' => $record['id'])); ?>">
                <img src="/public/uploads/products/<?php echo $record['id'] . '.' . $record['file_ext']; ?>" alt="<?php echo htmlspecialchars($record['name']); ?>" />
                <p><?php echo ucfirst($record['name']); ?></p>
                <p>$<?php echo ucfirst($record['price']); ?></p>
                <p>Tags: <?php
                  // find item tags
                    $tag_records = exec_sql_query(
                      $db,
                      "SELECT tags.name FROM tags INNER JOIN product_tags ON tags.id=product_tags.tag_id INNER JOIN products ON product_tags.product_id=products.id WHERE (products.id=:id);",
                      array(':id' => $record['id'])
                    )->fetchAll();
                    if (count($tag_records) > 0) {
                      $item_tags = $tag_records;
                    } else {
                      $item_tags = NULL;
                    }
                  if($item_tags){
                    $item_count = 1;
                    $total_count = count($item_tags);
                    foreach ($item_tags as $item_tag){?>
                    <a><?php echo htmlspecialchars($item_tag[0]); ?></a>
                    <?php if ($item_count != $total_count) {echo ", ";} ?>
                    <?php  $item_count = $item_count + 1;
                    }
                  } ?>
          </p>
              </a>
        </div>

      <?php } ?>
    <?php } ?>
</section>
</div>
</main>

</body>
<?php include("includes/footer.php"); ?>
</html>
