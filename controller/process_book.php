<?php
include_once('../model/model.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = isset($_POST["action"]) ? (integer) htmlspecialchars($_POST["action"]) : 1;
  $row = array();
  $row['id'] = isset($_POST['id_book']) ? htmlspecialchars($_POST['id_book']) : '';
  $row['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
  $row['authors'] = isset($_POST['authors']) ? $_POST['authors'] : '';

  $model = new model();
  if ($action == 1) {
    echo 'Add_<cr>';
    if ($model->add_book($row))
      header ("Location: /index.php?ctab=1");
    else
      echo 'Не удалось выполнить запрос к БД: ' . $client->error();
  } else {
    if ($model->update_book($row))
      header ("Location: /index.php?ctab=1");
    else
      echo 'Не удалось выполнить запрос к БД: ' . $client->error();
  }
}
?>
