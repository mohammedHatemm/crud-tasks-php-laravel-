<?php
class Category
{

  private $conn;
  private $table_name = "categories";


  public $id;
  public $name;
  public $description;
  public $parent_id;
  public $created_at;
  public $updated_at;


  public function __construct($db)
  {
    $this->conn = $db;
  }

  // OK
  public function read()
  {
    $query = "SELECT c.id, c.name, c.description, c.parent_id,
                  p.name as parent_name, c.created_at, c.updated_at
                FROM " . $this->table_name . " c
                LEFT JOIN " . $this->table_name . " p
                ON c.parent_id = p.id
                ORDER BY c.name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  // ok

  public function readOne()
  {
    $query = "SELECT c.id, c.name, c.description, c.parent_id,
                  p.name as parent_name, c.created_at, c.updated_at
                FROM " . $this->table_name . " c
                LEFT JOIN " . $this->table_name . " p
                ON c.parent_id = p.id
                WHERE c.id = ?
                LIMIT 0,1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->id = $row['id'];
      $this->name = $row['name'];
      $this->description = $row['description'];
      $this->parent_id = $row['parent_id'];
      $this->created_at = $row['created_at'];
      $this->updated_at = $row['updated_at'];
      return true;
    }

    return false;
  }

  // ok
  public function create()
  {
    $query = "INSERT INTO " . $this->table_name . "
                (name, description, parent_id)
                VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($query);


    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->description = htmlspecialchars(strip_tags($this->description));


    $stmt->bindParam(1, $this->name);
    $stmt->bindParam(2, $this->description);
    $stmt->bindParam(3, $this->parent_id);


    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }

    return false;
  }
  // ok

  public function update()
  {
    $query = "UPDATE " . $this->table_name . "
                SET name = ?, description = ?, parent_id = ?
                WHERE id = ?";

    $stmt = $this->conn->prepare($query);


    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->id = htmlspecialchars(strip_tags($this->id));


    $stmt->bindParam(1, $this->name);
    $stmt->bindParam(2, $this->description);
    $stmt->bindParam(3, $this->parent_id);
    $stmt->bindParam(4, $this->id);


    if ($stmt->execute()) {
      return true;
    }

    return false;
  }

  // ok

  public function delete()
  {

    $query = "UPDATE " . $this->table_name . "
                SET parent_id = NULL
                WHERE parent_id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();


    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }


  // ok

  public function readForDropdown()
  {
    $query = "SELECT id, name FROM " . $this->table_name . " ORDER BY name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }


  // ok

  public function readChildren()
  {
    $query = "SELECT id, name, description, parent_id, created_at, updated_at
                FROM " . $this->table_name . "
                WHERE parent_id = ?
                ORDER BY name";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    return $stmt;
  }


  public function readRootCategories()
  {
    $query = "SELECT id, name, description, parent_id, created_at, updated_at
                FROM " . $this->table_name . "
                WHERE parent_id IS NULL
                ORDER BY name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }
}
