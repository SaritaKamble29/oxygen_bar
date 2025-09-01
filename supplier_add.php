<?php
  require_once('includes/load.php');
  page_require_level(1);

  if(isset($_POST['add_supplier'])){
    $req_fields = array('name');
    validate_fields($req_fields);

    if(empty($errors)){
      $name           = remove_junk($db->escape($_POST['name']));
      $contact_person = remove_junk($db->escape($_POST['contact_person']));
      $phone          = remove_junk($db->escape($_POST['phone']));
      $email          = remove_junk($db->escape($_POST['email']));
      $address        = remove_junk($db->escape($_POST['address']));

      $query  = "INSERT INTO suppliers (name,contact_person,phone,email,address) ";
      $query .= "VALUES ('{$name}','{$contact_person}','{$phone}','{$email}','{$address}')";
      if($db->query($query)){
        $session->msg('s',"Supplier added ");
        redirect('supplier.php', false);
      } else {
        $session->msg('d',' Sorry failed to add!');
        redirect('supplier.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('supplier.php',false);
    }
  }
?>
