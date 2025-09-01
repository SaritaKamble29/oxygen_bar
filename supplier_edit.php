<?php
  $page_title = 'Edit Supplier';
  require_once('includes/load.php');
  page_require_level(1);

  $supplier = find_by_id('suppliers',(int)$_GET['id']);
  if(!$supplier){
    $session->msg("d","Missing supplier id.");
    redirect('supplier.php');
  }

  if(isset($_POST['update_supplier'])){
    $req_fields = array('name');
    validate_fields($req_fields);

    if(empty($errors)){
      $name           = remove_junk($db->escape($_POST['name']));
      $contact_person = remove_junk($db->escape($_POST['contact_person']));
      $phone          = remove_junk($db->escape($_POST['phone']));
      $email          = remove_junk($db->escape($_POST['email']));
      $address        = remove_junk($db->escape($_POST['address']));

      $query  = "UPDATE suppliers SET ";
      $query .= "name='{$name}', contact_person='{$contact_person}', phone='{$phone}', email='{$email}', address='{$address}' ";
      $query .= "WHERE id='{$supplier['id']}'";
      if($db->query($query)){
        $session->msg('s',"Supplier updated ");
        redirect('supplier.php', false);
      } else {
        $session->msg('d',' Sorry failed to update!');
        redirect('supplier_edit.php?id='.$supplier['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('supplier_edit.php?id='.$supplier['id'],false);
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
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-pencil"></span> Edit Supplier</strong>
      </div>
      <div class="panel-body">
        <form method="post" action="supplier_edit.php?id=<?php echo (int)$supplier['id'];?>">
          <div class="form-group">
            <label>Supplier Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk($supplier['name']);?>" required>
          </div>
          <div class="form-group">
            <label>Contact Person</label>
            <input type="text" class="form-control" name="contact_person" value="<?php echo remove_junk($supplier['contact_person']);?>">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" name="phone" value="<?php echo remove_junk($supplier['phone']);?>">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo remove_junk($supplier['email']);?>">
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" name="address"><?php echo remove_junk($supplier['address']);?></textarea>
          </div>
          <button type="submit" name="update_supplier" class="btn btn-primary">Update Supplier</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
