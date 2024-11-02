<?php
class User
{
    private $connection;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // Mendapatkan semua pengguna
    public function read()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat pengguna baru
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " SET username=:username, email=:email, password=:password";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_BCRYPT));  // Hash password
        return $stmt->execute();
    }

    // Memperbarui pengguna
    public function update()
    {
        $query = "UPDATE " . $this->table . " SET username=:username, email=:email, password=:password WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        if (!empty($this->password)) {
            $stmt->bindParam(':password', password_hash($this->password, PASSWORD_BCRYPT));
        }
        return $stmt->execute();
    }

    // Menghapus pengguna
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
