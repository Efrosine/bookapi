<?php
class TopBook
{
    private $connection;
    private $table = 'top_books';

    public $id;
    public $book_id;
    public $ranking;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // Mendapatkan semua top books
    public function read()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Menambah top book baru
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " SET book_id=:book_id, ranking=:ranking";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':ranking', $this->ranking);
        return $stmt->execute();
    }

    // Memperbarui top book
    public function update()
    {
        $query = "UPDATE " . $this->table . " SET ranking=:ranking WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':ranking', $this->ranking);
        return $stmt->execute();
    }

    // Menghapus top book
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
