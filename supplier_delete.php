<?php
  require_once('includes/load.php');
  page_require_level(1);

  $supplier = find_by_id('suppliers',(int)$_GET['id']);
  if(!$supplier){
    $session->msg("d","Missing Supplier id.");
    redirect('supplier.php');
  }

  $query = "DELETE FROM suppliers WHERE id='{$supplier['id']}'";
  if($db->query($query)){
      $session->msg("s","Supplier deleted.");
      redirect('supplier.php');
  } else {
      $session->msg("d","Supplier deletion failed.");
      redirect('supplier.php');
  }
?>
