<?php
// Set header untuk JSON response dan CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db.php';  // Koneksi database
include_once 'Book.php';  // Model buku
include_once 'TopBook.php';  // Model top_books
include_once 'User.php';  // Model users
include_once 'Peminjaman.php';  // Model peminjaman

// Mendapatkan URL path
$request_uri = str_replace('/api', '', $_SERVER['REQUEST_URI']);
$request_method = $_SERVER['REQUEST_METHOD'];
// $input_data = json_decode(file_get_contents("php://input"), true);
$input_data = [];

// Tentukan apakah input adalah JSON (Dari Dio atau API lainnya)
if ($request_method === 'POST' && strpos($request_uri, '/books') === false) {
    // Jika request POST dan bukan endpoint /books, terima data JSON
    $input_data = json_decode(file_get_contents("php://input"), true);
} elseif ($request_method === 'POST' && strpos($request_uri, '/books') !== false) {
    // Jika request POST dan ada file, gunakan form-data (khusus endpoint /books)
    $input_data = $_POST;  // Mengambil data form
    $image_data = $_FILES;  // Mengambil data file gambar yang diunggah
}

// Function untuk mengirim response JSON
function send_json_response($data, $status_code = 200)
{
    http_response_code($status_code);
    echo json_encode($data);
}

// Routing berdasarkan endpoint yang ketat
if (preg_match("/^\/books(\/\d+)?$/", $request_uri, $matches)) {
    $book = new Book($connection);
    handle_books($book, $matches);
} elseif (preg_match("/^\/top_books(\/\d+)?$/", $request_uri, $matches)) {
    $topBook = new TopBook($connection);
    handle_top_books($topBook, $matches);
} elseif (preg_match("/^\/books\/(\d+)\/image$/", $request_uri, $matches)) {
    $book = new Book($connection);
    handle_book_image($book, $matches[1]);
} elseif (preg_match("/^\/users(\/\d+)?$/", $request_uri, $matches)) {
    $user = new User($connection);
    handle_users($user, $matches);
} elseif (preg_match("/^\/peminjaman(\/\d+)?$/", $request_uri, $matches)) {
    $peminjaman = new Peminjaman($connection);
    handle_peminjaman($peminjaman, $matches);
} else {
    // Jika URL tidak valid, kembalikan 404 Not Found
    send_json_response(array("message" => "Invalid endpoint.", "uri" => $request_uri), 404);
}


// Function handler untuk books
function handle_books($book, $matches)
{
    global $input_data, $image_data, $request_method;

    if ($request_method === 'GET') {
        // Cek jika ada query parameter untuk search berdasarkan nama buku
        if (isset($_GET['search'])) {
            $search_query = $_GET['search'];
            $stmt = $book->searchByTitle($search_query);  // Panggil metode pencarian dari Book.php
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            send_json_response($books ? $books : array("message" => "No books found."), $books ? 200 : 404);

        } elseif (!empty($matches[1])) {  // GET /api/books/{id}
            $book->id = intval(trim($matches[1], '/'));
            $stmt = $book->readSingle();  // Metode untuk mendapatkan satu buku berdasarkan ID dari Book.php
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            send_json_response($row ? $row : array("message" => "Book not found."), $row ? 200 : 404);

        } else {  // GET /api/books
            $stmt = $book->read();  // Mendapatkan semua buku
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            send_json_response($books);
        }
    } elseif ($request_method === 'POST') {
        // Proses upload file gambar dan tambahkan buku baru
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_directory = "uploads/";
            $file_name = basename($_FILES['image']['name']);
            $target_file = $target_directory . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
            if (!in_array($imageFileType, $allowed_types)) {
                send_json_response(array("message" => "Invalid file type.ah yang bener  Only JPG, JPEG, PNG & GIF files are allowed."), 400);
                return;
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $domain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];  // URL domain
                $book->imageUrl = $domain . '/uploads/' . $file_name;
            } else {
                send_json_response(array("message" => "Error uploading file."), 500);
                return;
            }
        } else {
            send_json_response(array("message" => "No file uploaded."), 400);
            return;
        }

        // Validasi dan simpan data lainnya
        if (validate_book_input($input_data)) {
            $book->title = $input_data['title'];
            $book->author = $input_data['author'];
            $book->publisher = $input_data['publisher'];
            $book->copyNumber = $input_data['copyNumber'];
            $book->fileSize = $input_data['fileSize'];
            $book->readers = $input_data['readers'];
            $book->reviews = $input_data['reviews'];
            $book->readTime = $input_data['readTime'];
            $book->published_at = $input_data['published_at'];

            if ($book->create()) {
                send_json_response(array("message" => "Book created successfully.", "image_url" => $book->imageUrl), 201);
            } else {
                send_json_response(array("message" => "Error creating book."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input.", "data" => $input_data), 400);
        }
    }
}

// Fungsi validasi input untuk buku
function validate_book_input($input)
{
    return isset($input['title'], $input['author'], $input['publisher'], $input['copyNumber'], $input['fileSize'], $input['readers'], $input['reviews'], $input['readTime'], $input['published_at']);
}

// Function handler untuk top_books
function handle_top_books($topBook, $matches)
{
    global $input_data, $request_method;

    if ($request_method === 'GET') {
        $stmt = $topBook->read();  // Panggil metode read() dari TopBook.php
        $top_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        send_json_response($top_books);
    } elseif ($request_method === 'POST') {
        if (isset($input_data['book_id']) && isset($input_data['ranking'])) {
            $topBook->book_id = $input_data['book_id'];
            $topBook->ranking = $input_data['ranking'];

            if ($topBook->create()) {  // Panggil metode create() dari TopBook.php
                send_json_response(array("message" => "Top book added successfully."), 201);
            } else {
                send_json_response(array("message" => "Error adding top book."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input."), 400);
        }
    } elseif ($request_method === 'PUT' && !empty($matches[1])) { // PUT /api/top_books/{id}
        $topBook->id = intval(trim($matches[1], '/'));
        if (isset($input_data['ranking'])) {
            $topBook->ranking = $input_data['ranking'];

            if ($topBook->update()) {  // Panggil metode update() dari TopBook.php
                send_json_response(array("message" => "Top book updated successfully."));
            } else {
                send_json_response(array("message" => "Error updating top book."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input."), 400);
        }
    } elseif ($request_method === 'DELETE' && !empty($matches[1])) { // DELETE /api/top_books/{id}
        $topBook->id = intval(trim($matches[1], '/'));
        if ($topBook->delete()) {  // Panggil metode delete() dari TopBook.php
            send_json_response(array("message" => "Top book deleted successfully."));
        } else {
            send_json_response(array("message" => "Error deleting top book."), 500);
        }
    }
}

// Function untuk mendapatkan imageUrl dari buku berdasarkan ID
function handle_book_image($book, $id)
{
    // Ambil buku berdasarkan ID
    $book->id = intval($id);
    $stmt = $book->readSingle();  // Menggunakan metode readSingle() dari Book.php
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && isset($row['imageUrl'])) {
        // Kembalikan URL gambar jika ditemukan
        send_json_response(array("imageUrl" => $row['imageUrl']), 200);
    } else {
        // Jika buku atau gambar tidak ditemukan
        send_json_response(array("message" => "Book not found or image URL not available."), 404);
    }
}


function handle_users($user, $matches)
{
    global $input_data, $request_method;

    if ($request_method === 'GET') {
        if (!empty($matches[1])) {  // GET /api/users/{id}
            $user->id = intval(trim($matches[1], '/'));
            $stmt = $user->readSingle();  // Metode readSingle() dari User.php
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            send_json_response($row ? $row : array("message" => "User not found."), $row ? 200 : 404);
        } else {  // GET /api/users
            $stmt = $user->read();  // Mendapatkan semua pengguna
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            send_json_response($users);
        }
    } elseif ($request_method === 'POST') {
        // Membuat pengguna baru
        if (isset($input_data['username'], $input_data['email'], $input_data['password'])) {
            $user->username = $input_data['username'];
            $user->email = $input_data['email'];
            $user->password = password_hash($input_data['password'], PASSWORD_BCRYPT);  // Hash password

            if ($user->create()) {
                send_json_response(array("message" => "User created successfully."), 201);
            } else {
                send_json_response(array("message" => "Error creating user."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input. Username, email, and password are required."), 400);
        }
    } elseif ($request_method === 'PUT' && !empty($matches[1])) {  // PUT /api/users/{id}
        $user->id = intval(trim($matches[1], '/'));

        // Memperbarui data pengguna
        if (isset($input_data['username'], $input_data['email'])) {
            $user->username = $input_data['username'];
            $user->email = $input_data['email'];

            if (isset($input_data['password'])) {
                $user->password = password_hash($input_data['password'], PASSWORD_BCRYPT);  // Update password jika ada
            }

            if ($user->update()) {
                send_json_response(array("message" => "User updated successfully."));
            } else {
                send_json_response(array("message" => "Error updating user."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input. Username and email are required."), 400);
        }
    } elseif ($request_method === 'DELETE' && !empty($matches[1])) {  // DELETE /api/users/{id}
        $user->id = intval(trim($matches[1], '/'));

        if ($user->delete()) {
            send_json_response(array("message" => "User deleted successfully."));
        } else {
            send_json_response(array("message" => "Error deleting user."), 500);
        }
    }
}

function handle_peminjaman($peminjaman, $matches)
{
    global $input_data, $request_method;

    if ($request_method === 'GET') {
        if (!empty($matches[1])) {  // GET /api/peminjaman/{id}
            $peminjaman->id = intval(trim($matches[1], '/'));
            $stmt = $peminjaman->readSingle();  // Metode readSingle() dari Peminjaman.php
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            send_json_response($row ? $row : array("message" => "Peminjaman not found."), $row ? 200 : 404);
        } else {  // GET /api/peminjaman
            $stmt = $peminjaman->read();  // Mendapatkan semua data peminjaman
            $peminjamans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            send_json_response($peminjamans);
        }
    } elseif ($request_method === 'POST') {
        // Membuat peminjaman baru
        if (isset($input_data['user_id'], $input_data['book_id'], $input_data['tanggal_peminjaman'])) {
            $peminjaman->user_id = $input_data['user_id'];
            $peminjaman->book_id = $input_data['book_id'];
            $peminjaman->tanggal_peminjaman = $input_data['tanggal_peminjaman'];

            if ($peminjaman->create()) {
                send_json_response(array("message" => "Peminjaman created successfully."), 201);
            } else {
                send_json_response(array("message" => "Error creating peminjaman."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input. User ID, Book ID, and Tanggal Peminjaman are required."), 400);
        }
    } elseif ($request_method === 'PUT' && !empty($matches[1])) {  // PUT /api/peminjaman/{id}
        $peminjaman->id = intval(trim($matches[1], '/'));

        // Memperbarui status peminjaman
        if (isset($input_data['status'], $input_data['tanggal_pengembalian'])) {
            $peminjaman->status = $input_data['status'];
            $peminjaman->tanggal_pengembalian = $input_data['tanggal_pengembalian'];

            if ($peminjaman->update()) {
                send_json_response(array("message" => "Peminjaman updated successfully."));
            } else {
                send_json_response(array("message" => "Error updating peminjaman."), 500);
            }
        } else {
            send_json_response(array("message" => "Invalid input. Status and Tanggal Pengembalian are required."), 400);
        }
    } elseif ($request_method === 'DELETE' && !empty($matches[1])) {  // DELETE /api/peminjaman/{id}
        $peminjaman->id = intval(trim($matches[1], '/'));

        if ($peminjaman->delete()) {
            send_json_response(array("message" => "Peminjaman deleted successfully."));
        } else {
            send_json_response(array("message" => "Error deleting peminjaman."), 500);
        }
    }
}
