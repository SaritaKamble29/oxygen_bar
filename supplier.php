<?php
  $page_title = 'Manage Suppliers';
  require_once('includes/load.php');
  // Check permission (adjust level as per your system)
  page_require_level(1);

  // Fetch all suppliers
  $suppliers = find_all('suppliers');
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-plus"></span>
          <span>Add New Supplier</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="supplier_add.php">
          <div class="form-group">
            <label>Supplier Name</label>
            <input type="text" class="form-control" name="name" placeholder="Supplier Name" required>
          </div>
          <div class="form-group">
            <label>Contact Person</label>
            <input type="text" class="form-control" name="contact_person" placeholder="Contact Person">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" name="phone" placeholder="Phone Number">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" name="address" placeholder="Supplier Address"></textarea>
          </div>
          <button type="submit" name="add_supplier" class="btn btn-success">Add Supplier</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Suppliers</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Name</th>
              <th class="text-center">Contact Person</th>
              <th class="text-center">Phone</th>
              <th class="text-center">Email</th>
              <th class="text-center">Address</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($suppliers as $supplier): ?>
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td><?php echo remove_junk($supplier['name']); ?></td>
              <td><?php echo remove_junk($supplier['contact_person']); ?></td>
              <td><?php echo remove_junk($supplier['phone']); ?></td>
              <td><?php echo remove_junk($supplier['email']); ?></td>
              <td><?php echo remove_junk($supplier['address']); ?></td>
              <td class="text-center">
                <a href="supplier_edit.php?id=<?php echo (int)$supplier['id'];?>" class="btn btn-info btn-xs" title="Edit">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a href="supplier_delete.php?id=<?php echo (int)$supplier['id'];?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure?');">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
