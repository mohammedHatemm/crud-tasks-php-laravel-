<?php
class News
{

  private $conn;
  private $table_name = "news";
  private $category_relation_table = "news_category";


  public $id;
  public $name;
  public $content;
  public $categories = array();
  public $created_at;
  public $updated_at;


  public function __construct($db)
  {
    $this->conn = $db;
  }

  // function of reading all news is ok
  public function read()
  {
    $query = "SELECT n.id, n.name, n.content, n.created_at, n.updated_at
                FROM " . $this->table_name . " n
                ORDER BY n.created_at DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }


  // function of reading one news is ok

  public function readOne()
  {
    $query = "SELECT n.id, n.name, n.content, n.created_at, n.updated_at
                FROM " . $this->table_name . " n
                WHERE n.id = ?
                LIMIT 0,1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->content = $row['content'];
      $this->created_at = $row['created_at'];
      $this->updated_at = $row['updated_at'];


      $this->categories = $this->getNewsCategories();

      return true;
    }

    return false;
  }


  // function of creating news is ok

  public function create()
  {

    $this->conn->beginTransaction();

    try {

      $query = "INSERT INTO " . $this->table_name . "
                    (name, content)
                    VALUES (?, ?)";

      $stmt = $this->conn->prepare($query);


      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->content = htmlspecialchars(strip_tags($this->content));


      $stmt->bindParam(1, $this->name);
      $stmt->bindParam(2, $this->content);


      $stmt->execute();
      $this->id = $this->conn->lastInsertId();


      if (!empty($this->categories)) {
        $this->updateNewsCategories();
      }


      $this->conn->commit();
      return true;
    } catch (Exception $e) {

      $this->conn->rollBack();
      return false;
    }
  }


  public function update()
  {

    $this->conn->beginTransaction();

    try {

      $query = "UPDATE " . $this->table_name . "
                    SET name = ?, content = ?
                    WHERE id = ?";

      $stmt = $this->conn->prepare($query);


      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->content = htmlspecialchars(strip_tags($this->content));
      $this->id = htmlspecialchars(strip_tags($this->id));


      $stmt->bindParam(1, $this->name);
      $stmt->bindParam(2, $this->content);
      $stmt->bindParam(3, $this->id);


      $stmt->execute();


      $this->updateNewsCategories();


      $this->conn->commit();
      return true;
    } catch (Exception $e) {

      $this->conn->rollBack();
      return false;
    }
  }


  // function of deleting news is ok
  public function delete()
  {

    $this->conn->beginTransaction();

    try {

      $query = "DELETE FROM " . $this->category_relation_table . " WHERE news_id = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(1, $this->id);
      $stmt->execute();


      $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(1, $this->id);
      $stmt->execute();


      $this->conn->commit();
      return true;
    } catch (Exception $e) {

      $this->conn->rollBack();
      return false;
    }
  }

  // function of reading news by category is ok
  public function readByCategory($category_id)
  {
    $query = "SELECT n.id, n.name, n.content, n.created_at, n.updated_at
                FROM " . $this->table_name . " n
                JOIN " . $this->category_relation_table . " nc ON n.id = nc.news_id
                WHERE nc.category_id = ?
                ORDER BY n.created_at DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $category_id);
    $stmt->execute();

    return $stmt;
  }

  // function of reading newa by category is ok
  private function getNewsCategories()
  {
    $categories = array();

    $query = "SELECT category_id FROM " . $this->category_relation_table . "
                WHERE news_id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $categories[] = $row['category_id'];
    }

    return $categories;
  }

  // updating news categories is ok
  private function updateNewsCategories()
  {

    $query = "DELETE FROM " . $this->category_relation_table . " WHERE news_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();


    if (!empty($this->categories)) {
      $query = "INSERT INTO " . $this->category_relation_table . " (news_id, category_id) VALUES (?, ?)";
      $stmt = $this->conn->prepare($query);

      foreach ($this->categories as $category_id) {
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $category_id);
        $stmt->execute();
      }
    }

    return true;
  }
}
