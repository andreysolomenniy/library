<?php
include_once('../model/model.php');
include_once('./templ.php');

$data = array();

$model = new model();

$id_book = isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : 0;
$data['id_book'] = $id_book;

$total_rows = $model->get_author_count();
$ds_authors = $model->get_authors_selected($id_book);

$authors = array();
for($i = 1; $i <= $total_rows; $i++) {
  $a = array();
  $a['name'] = $ds_authors[$i]['name'];
  $authors[$i] = $a;
}
$data['authors'] = $authors;

$tmpl = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/book_author_form.html');
process_template($tmpl, $data);
?>
