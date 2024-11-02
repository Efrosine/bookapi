<?php
class Book
{
    private $connection;
    private $table = 'books';

    public $id;
    public $imageUrl;
    public $title;
    public $author;
    public $publisher;
    public $copyNumber;
    public $fileSize;
    public $readers;
    public $reviews;
    public $readTime;
    public $published_at;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    // Mendapatkan semua buku
    public function read()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mendapatkan satu buku berdasarkan ID
    public function readSingle()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Mencari buku berdasarkan judul
    public function searchByTitle($search_query)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE title LIKE :search_query";
        $stmt = $this->connection->prepare($query);
        $search_term = "%" . $search_query . "%";  // Pencarian dengan LIKE
        $stmt->bindParam(':search_query', $search_term);
        $stmt->execute();
        return $stmt;
    }


    // Membuat buku baru
    public function create()
    {
        $query = "INSERT INTO " . $this->table . "
                  SET imageUrl=:imageUrl, title=:title, author=:author, publisher=:publisher,
                  copyNumber=:copyNumber, fileSize=:fileSize, readers=:readers,
                  reviews=:reviews, readTime=:readTime, published_at=:published_at";

        $stmt = $this->connection->prepare($query);

        // Bind data
        $stmt->bindParam(':imageUrl', $this->imageUrl);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':publisher', $this->publisher);
        $stmt->bindParam(':copyNumber', $this->copyNumber);
        $stmt->bindParam(':fileSize', $this->fileSize);
        $stmt->bindParam(':readers', $this->readers);
        $stmt->bindParam(':reviews', $this->reviews);
        $stmt->bindParam(':readTime', $this->readTime);
        $stmt->bindParam(':published_at', $this->published_at);

        // Eksekusi query
        return $stmt->execute();
    }

    // Update buku
    public function update()
    {
        $query = "UPDATE " . $this->table . "
                  SET imageUrl=:imageUrl, title=:title, author=:author, publisher=:publisher,
                  copyNumber=:copyNumber, fileSize=:fileSize, readers=:readers,
                  reviews=:reviews, readTime=:readTime, published_at=:published_at
                  WHERE id=:id";
        $stmt = $this->connection->prepare($query);

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':imageUrl', $this->imageUrl);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':publisher', $this->publisher);
        $stmt->bindParam(':copyNumber', $this->copyNumber);
        $stmt->bindParam(':fileSize', $this->fileSize);
        $stmt->bindParam(':readers', $this->readers);
        $stmt->bindParam(':reviews', $this->reviews);
        $stmt->bindParam(':readTime', $this->readTime);
        $stmt->bindParam(':published_at', $this->published_at);

        return $stmt->execute();
    }

    // Menghapus buku
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}