<?php
/* is not finished... */

class model {

  private $mysqli;
  
  public function connect() {
    $this->mysqli = new mysqli('localhost','root','', 'library');
    if ($this->mysqli->connect_errno) {
      //header ('Location: /index.php/?res=2&msg=Не удалось подключиться к MySQL: ' . $mysqli->connect_error);
      echo 'Не удалось подключиться к MySQL: ' . $mysqli->errno . $mysqli->connect_error;
      exit;
    }
    if (! $this->mysqli->set_charset("utf8")) {
      echo 'Ошибка установки набора символов utf8: ' . $mysqli->errno . $mysqli->error;
      exit;
    }
  }

  function __construct() {
   if (! isset($this->mysqli))
      $this->connect();
  }
  
  public function get_book_count() {
    $res = $this->mysqli->query("SELECT COUNT(*) AS num FROM book");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $row = $res->fetch_assoc();
    return isset($row['num']) ? $row['num'] : 0;
  }

  public function get_author_count() {
    $res = $this->mysqli->query("SELECT COUNT(*) AS num FROM author");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $row = $res->fetch_assoc();
    return isset($row['num']) ? $row['num'] : 0;
  }

  public function get_book_rows($from, $max_row) {
    $res = $this->mysqli->query("SELECT b.id, b.name FROM book b ORDER BY b.name LIMIT $from, $max_row");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $i = 1;
    $list = array();
    while ($row = $res->fetch_assoc()) {
      $list[$i] = $row;
      $i++;
    }
    return $list;
  }
  
  public function get_book_authors($id_book) {
    $res = $this->mysqli->query("SELECT a.id, a.name, ba.id_book FROM book_authors ba LEFT JOIN author a ON ba.id_author = a.id WHERE ba.id_book = $id_book");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $i = 1;
    $list = array();
    while ($row = $res->fetch_assoc()) {
      $list[$i] = $row;
      $i++;
    }
    return $list;
  }

  public function get_authors_selected($id_book) {
    $res = $this->mysqli->query("SELECT a.id, a.name, (SELECT ba.id_book FROM book_authors ba WHERE ba.id_author = a.id AND ba.id_book = $id_book) AS selected FROM author a");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $i = 1;
    $list = array();
    while ($row = $res->fetch_assoc()) {
      $list[$i] = $row;
      $i++;
    }
    return $list;
  }

  public function get_author_rows($from, $max_row) {
    $res = $this->mysqli->query("SELECT a.id, a.name FROM author a ORDER BY a.name LIMIT $from, $max_row");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $i = 1;
    $list = array();
    while ($row = $res->fetch_assoc()) {
      $list[$i] = $row;
      $i++;
    }
    return $list;
  }
  
  public function get_book($id_book) {
    $res = $this->mysqli->query("SELECT b.id, b.name FROM book b WHERE id = $id_book");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    return $res->fetch_assoc();
  }
  
  public function get_author($id_author) {
    $res = $this->mysqli->query("SELECT a.name FROM author a WHERE id = $id_author");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return 0;
    }
    $row = $res->fetch_assoc();
    return $row['name'];
  }
  
  public function get_id_author_by_name($author_name) {
    $res = $this->mysqli->query("SELECT a.id FROM author a WHERE a.name = '$author_name'");
    if ($res === false) {
      return false;
    }
    $row = $res->fetch_assoc();
    if ($row === false)
      return false;
    return $row['id'];
  }

  public function add_book_author($id_book, $id_author) {
    $res = $this->mysqli->query("INSERT INTO book_authors (id_book, id_author) VALUES ($id_book, $id_author)");
    if ($res === false)
      return false;
    return true;
  }
  
  public function add_book_authors($id_book, $authors) {
    foreach($authors as $val) {
      $id_author = $this->get_id_author_by_name($val);
      if ($id_author === false) {
        return false;
      }
      $res = $this->mysqli->query("INSERT INTO book_authors (id_book, id_author) VALUES ($id_book, $id_author)");
      if ($res === false) {
        return false;
      }
    }
    return true;
  }
  
  public function add_book($row) {
    $this->mysqli->begin_transaction();
    $res = $this->mysqli->query("INSERT INTO book (name) VALUES ('${row['name']}')");
    if ($res === false) {
      $this->mysqli->rollback();
      return false;
    }
    $id_book = $this->mysqli->insert_id;
    if ($this->add_book_authors($id_book, $row['authors']) === false) {
      $this->mysqli->rollback();
      return false;
    }
    $this->mysqli->commit();
    return true;
  }

  public function update_book($row) {
    $id_book = $row['id'];
    $this->mysqli->begin_transaction();
    $res = $this->mysqli->query("UPDATE book SET name='${row['name']}' WHERE id = ${row['id']}");
    if ($res === false) {
      $this->mysqli->rollback();
      return false;
    }
    $res = $this->mysqli->query("DELETE FROM book_authors WHERE id_book = ${row['id']}");
    if ($res === false) {
      $this->mysqli->rollback();
      return false;
    }
    if ($this->add_book_authors($id_book, $row['authors']) === false) {
      $this->mysqli->rollback();
      return false;
    }
    $this->mysqli->commit();
    return true;
  }

  public function delete_book($id_book) {
    $this->mysqli->begin_transaction();
    $res = $this->mysqli->query("DELETE FROM book WHERE id = $id_book");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      $this->mysqli->rollback();
      return false;
    }
    $res = $this->mysqli->query("DELETE FROM book_authors WHERE id_book = $id_book");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      $this->mysqli->rollback();
      return false;
    }
    $this->mysqli->commit();
    return true;
  }

  public function delete_book_author($id_book, $id_author) {
    $res = $this->mysqli->query("DELETE FROM book_authors WHERE id_book = $id_book AND id_author = $id_author");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return false;
    }
    return true;
  }

  public function add_author($row) {
    $res = $this->mysqli->query("INSERT INTO author (name) VALUES ('${row['name']}')");
    if ($res === false) {
      return false;
    }
    return true;
  }

  public function update_author($row) {
    $id_author = $row['id'];
    $res = $this->mysqli->query("UPDATE author SET name='${row['name']}' WHERE id = ${row['id']}");
    if ($res === false) {
      return false;
    }
    return true;
  }

  public function delete_author($id_author) {
    $res = $this->mysqli->query("DELETE FROM author WHERE id = $id_author");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return false;
    }
    return true;
  }

  public function is_author_used($id_author) {
    $res = $this->mysqli->query("SELECT COUNT(*) AS num FROM book_authors WHERE id_author = $id_author");
    if ($res === false) {
      echo 'Не удалось выполнить запрос к БД: ' . $mysqli->error;
      return true;
    }
    $row = $res->fetch_assoc();
    return $row['num'] > 0;
  }

  public function error() {
    return $this->mysqli->error;
  }
}
?>
