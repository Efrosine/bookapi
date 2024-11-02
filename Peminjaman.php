<?php
class Peminjaman
{
    private $connection;
    private $table = 'peminjaman';

    public $id;
    public $user_id;
    public $book_id;
    public $tanggal_peminjaman;
    public $tanggal_pengembalian;
    public $status;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // Mendapatkan semua peminjaman
    public function read()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat peminjaman baru
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " SET user_id=:user_id, book_id=:book_id, tanggal_peminjaman=:tanggal_peminjaman, status='dipinjam'";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':tanggal_peminjaman', $this->tanggal_peminjaman);
        return $stmt->execute();
    }

    // Memperbarui status peminjaman
    public function update()
    {
        $query = "UPDATE " . $this->table . " SET status=:status, tanggal_pengembalian=:tanggal_pengembalian WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':tanggal_pengembalian', $this->tanggal_pengembalian);
        return $stmt->execute();
    }

    // Menghapus peminjaman
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
