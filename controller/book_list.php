<?php
include_once('../model/model.php');
include_once('templ.php');

$data = array();
$data['header'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/main.html');

$model = new model();
$total_rows = $model->get_book_count();
if ($total_rows == 0) {
  $tmpl = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/empty_book_list.html');
  process_template($tmpl, $data);
  exit;
}

$page = (integer) htmlspecialchars($_GET["num"]);
$page_size = 3;
$page_num = ceil($total_rows / $page_size);
$page_rest = ceil($total_rows % $page_size);
$data['pages'] = array();
for ($i = 1; $i <= $page_num; $i++) {
  $data['pages'][$i] = (string)$i;
}
  
$from = $page_size * ($page-1);
$max_row = $page_rest == 0 || $page < $page_num ? $page_size : $page_rest;
$dataset = $model->get_book_rows($from, $max_row);
$books = array();
for($i = 1; $i <= $max_row; $i++) {
  $b = array();
  $b['id'] = $dataset[$i]['id'];
  $b['name'] = $dataset[$i]['name'];
  $books[$i] = $b;
}
$data['books'] = $books;

$tmpl = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/book_list.html');
process_template($tmpl, $data);
?>
