<?php
$page_title = 'Edit Product';
require_once('includes/load.php');

// Check permission
page_require_level(2);

// Validate product id
$product_id = (int)$_GET['id'];
$product = find_by_id('products', $product_id);

if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}

// Load categories, suppliers, media
$all_categories = find_all('categories');
$all_suppliers  = find_all('suppliers');
$all_media      = find_all('media');

if(isset($_POST['update_product'])){
  $req_fields = array('product-name','product-categorie','product-supplier','product-quantity','buying-price','saleing-price','barcode','volume','brand','media-id');
  validate_fields($req_fields);

  if(empty($errors)){
    $p_name     = remove_junk($db->escape($_POST['product-name']));
    $p_cat      = (int)$db->escape($_POST['product-categorie']);
    $p_sup      = (int)$db->escape($_POST['product-supplier']);
    $p_qty      = (int)$db->escape($_POST['product-quantity']);
    $p_buy      = (float)$db->escape($_POST['buying-price']);
    $p_sale     = (float)$db->escape($_POST['saleing-price']);
    $p_barcode  = remove_junk($db->escape($_POST['barcode']));
    $p_volume   = remove_junk($db->escape($_POST['volume']));
    $p_brand    = remove_junk($db->escape($_POST['brand']));
    $p_media    = (int)$db->escape($_POST['media-id']);

    $query  = "UPDATE products SET";
    $query .= " name='{$p_name}', quantity='{$p_qty}', buy_price='{$p_buy}', sale_price='{$p_sale}',";
    $query .= " categorie_id='{$p_cat}', supplier_id='{$p_sup}', barcode='{$p_barcode}',";
    $query .= " volume='{$p_volume}', brand='{$p_brand}', media_id='{$p_media}'";
    $query .= " WHERE id='{$product_id}'";

    if($db->query($query)){
      $session->msg('s',"Product updated successfully!");
      redirect('edit_product.php?id='.$product_id, false);
    } else {
      $session->msg('d','Sorry, update failed.');
      redirect('edit_product.php?id='.$product_id, false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_product.php?id='.$product_id,false);
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
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-pencil"></span> <span>Edit Product</span></strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_product.php?id=<?php echo (int)$product['id']; ?>">
          <div class="form-group">
              <label for="product-name">Product Name</label>
              <input type="text" class="form-control" name="product-name" value="<?php echo remove_junk($product['name']); ?>">
          </div>

          <div class="form-group">
            <label for="product-categorie">Category</label>
              <select class="form-control" name="product-categorie">
                <?php foreach ($all_categories as $cat): ?>
                  <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']) echo "selected"; ?>>
                    <?php echo $cat['name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
          </div>

          <div class="form-group">
            <label for="product-supplier">Supplier</label>
              <select class="form-control" name="product-supplier">
                <?php foreach ($all_suppliers as $sup): ?>
                  <option value="<?php echo (int)$sup['id']; ?>" <?php if($product['supplier_id'] === $sup['id']) echo "selected"; ?>>
                    <?php echo $sup['name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
          </div>

          <div class="form-group">
              <label for="product-quantity">Quantity</label>
              <input type="number" class="form-control" name="product-quantity" value="<?php echo (int)$product['quantity']; ?>">
          </div>

          <div class="form-group">
              <label for="buying-price">Buying Price</label>
              <input type="number" step="0.01" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>">
          </div>

          <div class="form-group">
              <label for="saleing-price">Selling Price</label>
              <input type="number" step="0.01" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']); ?>">
          </div>

          <div class="form-group">
              <label for="barcode">Barcode</label>
              <input type="text" class="form-control" name="barcode" value="<?php echo remove_junk($product['barcode']); ?>">
          </div>

          <div class="form-group">
              <label for="volume">Volume</label>
              <input type="text" class="form-control" name="volume" value="<?php echo remove_junk($product['volume']); ?>">
          </div>

          <div class="form-group">
              <label for="brand">Brand</label>
              <input type="text" class="form-control" name="brand" value="<?php echo remove_junk($product['brand']); ?>">
          </div>

          <div class="form-group">
              <label for="media-id">Product Photo</label>
              <select class="form-control" name="media-id">
                <option value="0">No Image</option>
                <?php foreach ($all_media as $media): ?>
                  <option value="<?php echo (int)$media['id']; ?>" <?php if($product['media_id'] === $media['id']) echo "selected"; ?>>
                    <?php echo $media['file_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if($product['media_id'] > 0): ?>
                <br>
                <img src="uploads/products/<?php echo find_by_id('media',$product['media_id'])['file_name']; ?>" 
                     class="img-thumbnail" style="max-width:150px;">
              <?php endif; ?>
          </div>

          <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
