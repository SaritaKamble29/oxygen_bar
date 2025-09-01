<?php
$page_title = 'Add Product';
require_once('includes/load.php');

// Check user permission
page_require_level(2);

// Load categories, suppliers, and media
$all_categories = find_all('categories');
$all_suppliers  = find_all('suppliers');
$all_media      = find_all('media'); // new for product photo

if (isset($_POST['add_product'])) {
  $req_fields = array(
    'product-name','product-categorie','product-supplier','product-quantity',
    'buying-price','saleing-price','barcode','volume','brand','product-photo'
  );
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name     = remove_junk($db->escape($_POST['product-name']));
    $p_cat      = (int)$db->escape($_POST['product-categorie']);
    $p_sup      = (int)$db->escape($_POST['product-supplier']);
    $p_qty      = (int)$db->escape($_POST['product-quantity']);
    $p_buy      = (float)$db->escape($_POST['buying-price']);
    $p_sale     = (float)$db->escape($_POST['saleing-price']);
    $p_barcode  = remove_junk($db->escape($_POST['barcode']));
    $p_volume   = remove_junk($db->escape($_POST['volume']));
    $p_brand    = remove_junk($db->escape($_POST['brand']));
    $p_photo    = (int)$db->escape($_POST['product-photo']);
    $date       = make_date();

    $query  = "INSERT INTO products (";
    $query .= " name, quantity, buy_price, sale_price, categorie_id, supplier_id, barcode, volume, brand, media_id, date";
    $query .= ") VALUES (";
    $query .= " '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$p_sup}', '{$p_barcode}', '{$p_volume}', '{$p_brand}', '{$p_photo}', '{$date}'";
    $query .= ")";

    if ($db->query($query)) {
      $session->msg('s', "Product added successfully!");
      redirect('add_product.php', false);
    } else {
      $session->msg('d', ' Sorry, product addition failed.');
      redirect('add_product.php', false);
    }

  } else {
    $session->msg("d", $errors);
    redirect('add_product.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading bg-primary text-white" style="padding:10px;">
        <strong>
          <span class="glyphicon glyphicon-plus"></span>
          <span>Add New Product</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_product.php">

          <!-- Product Name -->
          <div class="form-group">
              <label for="product-name">Product Name</label>
              <input type="text" class="form-control" id="product-name" name="product-name" placeholder="Product Name">
          </div>

          <!-- Category -->
          <div class="form-group">
            <label for="product-categorie">Category</label>
              <select class="form-control" name="product-categorie" id="product-categorie">
                <option value="">Select Category</option>
                <?php foreach ($all_categories as $cat): ?>
                  <option value="<?php echo (int)$cat['id'] ?>">
                    <?php echo ucfirst($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
          </div>

          <!-- Supplier -->
          <div class="form-group">
            <label for="product-supplier">Supplier</label>
              <select class="form-control" name="product-supplier" id="product-supplier">
                <option value="">Select Supplier</option>
                <?php foreach ($all_suppliers as $sup): ?>
                  <option value="<?php echo (int)$sup['id'] ?>">
                    <?php echo ucfirst($sup['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
          </div>

          <!-- Quantity -->
          <div class="form-group">
              <label for="product-quantity">Quantity</label>
              <input type="number" class="form-control" id="product-quantity" name="product-quantity" placeholder="Quantity">
          </div>

          <!-- Buying Price -->
          <div class="form-group">
              <label for="buying-price">Buying Price</label>
              <input type="number" step="0.01" class="form-control" id="buying-price" name="buying-price" placeholder="Buying Price">
          </div>

          <!-- Selling Price -->
          <div class="form-group">
              <label for="saleing-price">Selling Price</label>
              <input type="number" step="0.01" class="form-control" id="saleing-price" name="saleing-price" placeholder="Selling Price">
          </div>

          <!-- Barcode -->
          <div class="form-group">
              <label for="barcode">Barcode</label>
              <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Scan or Enter Barcode">
              <small class="text-muted">Scan barcode using scanner or type manually</small>
          </div>

          <!-- Volume -->
          <div class="form-group">
              <label for="volume">Volume (e.g. 750ml)</label>
              <input type="text" class="form-control" id="volume" name="volume" placeholder="Enter Volume">
          </div>

          <!-- Brand -->
          <div class="form-group">
              <label for="brand">Brand</label>
              <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter Brand">
          </div>

          <!-- Product Photo -->
          <div class="form-group">
            <label for="product-photo">Product Photo</label>
              <select class="form-control" name="product-photo" id="product-photo">
                <option value="">Select Photo</option>
                <?php foreach ($all_media as $media): ?>
                  <option value="<?php echo (int)$media['id'] ?>">
                    <?php echo $media['file_name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
          </div>

          <button type="submit" name="add_product" class="btn btn-success btn-block">
            <span class="glyphicon glyphicon-ok"></span> Add Product
          </button>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- AJAX Barcode Auto-fill -->
<script>
document.getElementById('barcode').addEventListener('change', function() {
    let barcode = this.value.trim();
    if(barcode.length > 0){
        fetch('barcode_lookup.php?barcode=' + barcode)
        .then(res => res.json())
        .then(data => {
            if(data.success){
                document.getElementById('product-name').value = data.product.name;
                document.getElementById('volume').value       = data.product.volume;
                document.getElementById('brand').value        = data.product.brand;
                document.getElementById('buying-price').value = data.product.buy_price;
                document.getElementById('saleing-price').value= data.product.sale_price;
                document.getElementById('product-quantity').value= data.product.quantity;
                document.getElementById('product-categorie').value= data.product.categorie_id;
                document.getElementById('product-supplier').value = data.product.supplier_id;
                if(data.product.media_id){
                    document.getElementById('product-photo').value = data.product.media_id;
                }
            }
        })
        .catch(err => console.log(err));
    }
});
</script>

<?php include_once('layouts/footer.php'); ?>
