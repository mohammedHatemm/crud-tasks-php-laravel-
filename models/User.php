<?php

class User
{
  private $conn;
  private $table_name = "users";

  public $id;
  public $username;
  public $email;
  public $password;
  public $confirm_password;
  public $phone;
  public $role;
  public $created_at;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function register()
  {
    // إزالة التحقق من البريد الإلكتروني هنا لأنه يتم في register_process.php
    $query = "INSERT INTO " . $this->table_name . " (username, email, password, phone, role)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($query);

    // تنظيف البيانات
    $this->username = htmlspecialchars(strip_tags($this->username));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->phone = htmlspecialchars(strip_tags($this->phone));
    $this->role = htmlspecialchars(strip_tags($this->role));

    // تشفير كلمة المرور
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

    // ربط المعاملات
    $stmt->bindParam(1, $this->username);
    $stmt->bindParam(2, $this->email);
    $stmt->bindParam(3, $password_hash);
    $stmt->bindParam(4, $this->phone);
    $stmt->bindParam(5, $this->role);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  public function emailExists()
  {
    $query = "SELECT id, username, email, password, role FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->id = $row['id'];
      $this->username = $row['username'];
      $this->email = $row['email'];
      $this->role = $row['role'];
      $this->password = $row['password'];
      return true;
    }
    return false;
  }

  // إضافة دالة للتحقق من وجود اسم المستخدم
  public function usernameExists()
  {
    $query = "SELECT id FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->username);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  // دالة للتحقق من صحة تسجيل الدخول
  public function login()
  {
    $query = "SELECT id, username, email, password, role FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // التحقق من كلمة المرور
      if (password_verify($this->password, $row['password'])) {
        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->role = $row['role'];
        return true;
      }
    }
    return false;
  }

  public function readOne()
  {
    $query = "SELECT id, username, email, phone, role, created_at FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->username = $row['username'];
      $this->email = $row['email'];
      $this->phone = $row['phone'];
      $this->role = $row['role'];
      $this->created_at = $row['created_at'];
      return true;
    }
    return false;
  }

  public function read()
  {
    $query = "SELECT id, username, email, phone, role, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function update()
  {
    $query = "UPDATE " . $this->table_name . " SET username = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);

    $this->username = htmlspecialchars(strip_tags($this->username));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->phone = htmlspecialchars(strip_tags($this->phone));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $this->username);
    $stmt->bindParam(2, $this->email);
    $stmt->bindParam(3, $this->phone);
    $stmt->bindParam(4, $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  public function updatePassword()
  {
    $query = "UPDATE " . $this->table_name . " SET password = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);

    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(1, $password_hash);
    $stmt->bindParam(2, $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  public function delete()
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(1, $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }
}
