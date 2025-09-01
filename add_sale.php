<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  // Check user permission
  page_require_level(3);

  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total','date');
    validate_fields($req_fields);

    if(empty($errors)){
      $p_id    = $db->escape((int)$_POST['s_id']);
      $s_qty   = $db->escape((int)$_POST['quantity']);
      $s_total = $db->escape($_POST['total']);
      $date    = $db->escape($_POST['date']);
      $s_date  = make_date();

      $sql  = "INSERT INTO sales (product_id,qty,price,date) VALUES (";
      $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$s_date}')";

      if($db->query($sql)){
        update_product_qty($s_qty,$p_id);
        $session->msg('s',"Sale added. ");
        redirect('add_sale.php', false);
      } else {
        $session->msg('d',' Sorry failed to add!');
        redirect('add_sale.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_sale.php',false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    
    <!-- Barcode input -->
    <form method="post" id="barcode-form" autocomplete="off">
      <div class="form-group">
        <div class="input-group">
          <input type="text" id="barcode_input" class="form-control" name="barcode" placeholder="Scan or Enter Barcode">
          <span class="input-group-btn">
            <button type="button" class="btn btn-success" id="barcode_btn">Scan</button>
          </span>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Entry</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
          <table class="table table-bordered">
            <thead>
              <th> Item </th>
              <th> Price </th>
              <th> Qty </th>
              <th> Total </th>
              <th> Date</th>
              <th> Action</th>
            </thead>
            <tbody id="product_info"></tbody>
            <tfoot>
              <!-- <tr>
                <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
                <td><input type="text" id="grand_total" class="form-control" value="0" readonly></td>
                <td colspan="2"></td>
              </tr> -->
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

  // Barcode scanning
  $('#barcode_btn').on('click', function(){
      var code = $('#barcode_input').val();
      if(code.length > 0){
          $.ajax({
              url: 'ajax.php',
              method: 'POST',
              dataType: 'json',
              data: {barcode: code},
              success: function(res){
                  if(res.error){
                      alert(res.error);
                  } else {
                      addProduct(res.id, res.name, res.sale_price);
                  }
                  $('#barcode_input').val('');
              }
          });
      }
  });

  // Allow pressing Enter in barcode input
  $('#barcode_input').keypress(function(e){
      if(e.which == 13){
          e.preventDefault();
          $('#barcode_btn').click();
      }
  });

  // Add product row helper
  function addProduct(id, name, price){
      var row = '<tr>'+
        '<td><input type="hidden" name="s_id" value="'+id+'">'+name+'</td>'+
        '<td><input type="text" name="price" class="form-control" value="'+price+'" readonly></td>'+
        '<td><input type="number" name="quantity" class="form-control qty" value="1" min="1"></td>'+
        '<td><input type="text" name="total" class="form-control total" value="'+price+'" readonly></td>'+
        '<td><input type="date" name="date" class="form-control" value="<?php echo date("Y-m-d"); ?>"></td>'+
        '<td><button type="submit" name="add_sale" class="btn btn-success">Add</button> '+
        '<button type="button" class="btn btn-danger btn-sm remove-row">Cancel</button></td>'+
      '</tr>';
      $('#product_info').append(row);
      updateGrandTotal();
  }

  // Update total when qty changes
  $(document).on('input', '.qty', function(){
      var qty = $(this).val();
      var price = $(this).closest('tr').find('input[name="price"]').val();
      var total = qty * price;
      $(this).closest('tr').find('.total').val(total);
      updateGrandTotal();
  });

  // Cancel product row
  $(document).on('click', '.remove-row', function(){
      $(this).closest('tr').remove();
      updateGrandTotal();
  });

  // Calculate and update grand total
  function updateGrandTotal(){
      var grand = 0;
      $('.total').each(function(){
          grand += parseFloat($(this).val()) || 0;
      });
      $('#grand_total').val(grand.toFixed(2));
  }

});
</script>

<?php include_once('layouts/footer.php'); ?>
