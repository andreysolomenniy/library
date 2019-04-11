<?php
include_once('./model/model.php');
include_once('./controller/templ.php');

$data = array();
$model = new model();

$checked_tab = isset($_GET["ctab"]) ? htmlspecialchars($_GET["ctab"]) : 1;
if ($checked_tab == 1) {
  $data['checked_tab1'] = 'checked = "true"';
  $data['checked_tab2'] = '';
} else {
  $data['checked_tab1'] = '';
  $data['checked_tab2'] = 'checked = "true"';
}

$total_rows = $model->get_book_count();
if ($total_rows == 0) {
  exit;
}

//$page = (integer) htmlspecialchars($_GET["num"]);
$page = 1;
$page_size = 10;
$page_num = ceil($total_rows / $page_size);
$page_rest = ceil($total_rows % $page_size);
$data['pages'] = array();
for ($i = 1; $i <= $page_num; $i++) {
  $data['pages'][$i] = (string)$i;
}

$from = $page_size * ($page-1);
$max_row = $page_rest == 0 || $page < $page_num ? $page_size : $page_rest;

$ds_books = $model->get_book_rows($from, $max_row);
$books = array();
for($i = 1; $i <= $max_row; $i++) {
  $b = array();
  $b['id'] = $ds_books[$i]['id'];
  $b['name'] = $ds_books[$i]['name'];
  $ba = $model->get_book_authors($b['id']);
  for ($j = 1; $j < count($ba); $j++) {
    $ba[$j]['name'] = $ba[$j]['name'] . ',';
  }
  $b['book_authors'] = $ba;
  $books[$i] = $b;
}

$total_rows = $model->get_author_count();
$page = 1;
$page_size = 10;
$page_num = ceil($total_rows / $page_size);
$page_rest = ceil($total_rows % $page_size);
$from = $page_size * ($page-1);
$max_row = $page_rest == 0 || $page < $page_num ? $page_size : $page_rest;
$ds_authors = $model->get_author_rows($from, $max_row);
$authors = array();

for($i = 1; $i <= $max_row; $i++) {
  $a = array();
  $a['id'] = $ds_authors[$i]['id'];
  $a['name'] = $ds_authors[$i]['name'];
  $authors[$i] = $a;
}

$data['books'] = $books;
$data['authors'] = $authors;

$tmpl = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/main_page.html');
process_template($tmpl, $data);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
?>


