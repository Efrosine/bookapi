### **Endpoint: `/api/books`**

#### 1. **GET /api/books**
   - **Deskripsi**: Mendapatkan daftar semua buku.
   - **Metode**: GET
   - **Respons Sukses**:
     ```json
     [
       {
         "id": 1,
         "title": "Judul Buku",
         "author": "Nama Penulis",
         "publisher": "Penerbit",
         "copyNumber": 2,
         "fileSize": "500KB",
         "readers": 120,
         "reviews": 45,
         "readTime": "2h",
         "published_at": "2024-01-01",
         "imageUrl": "http://domain/uploads/nama_file.jpg"
       }
     ]
     ```

#### 2. **GET /api/books/{id}**
   - **Deskripsi**: Mendapatkan detail buku berdasarkan ID.
   - **Metode**: GET
   - **Parameter Path**:
     - `{id}`: ID buku yang ingin diambil.
   - **Respons Sukses**:
     ```json
     {
       "id": 1,
       "title": "Judul Buku",
       "author": "Nama Penulis",
       "publisher": "Penerbit",
       ...
     }
     ```
   - **Respons Error**:
     ```json
     {
       "message": "Book not found."
     }
     ```

#### 3. **POST /api/books**
   - **Deskripsi**: Menambahkan buku baru dengan file gambar.
   - **Metode**: POST
   - **Headers**:
     - Content-Type: multipart/form-data (karena mengunggah gambar).
   - **Body (Form Data)**:
     - `title`: Judul buku (wajib).
     - `author`: Penulis buku (wajib).
     - `publisher`: Penerbit buku (wajib).
     - `copyNumber`, `fileSize`, `readers`, `reviews`, `readTime`, `published_at`.
     - `image`: File gambar buku (wajib, mendukung format JPG, JPEG, PNG, GIF).
   - **Respons Sukses**:
     ```json
     {
       "message": "Book created successfully.",
       "image_url": "http://domain/uploads/nama_file.jpg"
     }
     ```
   - **Respons Error**:
     ```json
     {
       "message": "No file uploaded."
     }
     ```

#### 4. **GET /api/books?search={query}**
   - **Deskripsi**: Mencari buku berdasarkan query nama judul.
   - **Metode**: GET
   - **Parameter Query**:
     - `search`: Kata kunci untuk mencari buku berdasarkan judul.
   - **Respons Sukses**:
     ```json
     [
       {
         "id": 1,
         "title": "Judul Buku",
         ...
       }
     ]
     ```

---

### **Endpoint: `/api/top_books`**

#### 1. **GET /api/top_books**
   - **Deskripsi**: Mendapatkan daftar top books.
   - **Metode**: GET
   - **Respons Sukses**:
     ```json
     [
       {
         "book_id": 1,
         "ranking": 5
       }
     ]
     ```

#### 2. **POST /api/top_books**
   - **Deskripsi**: Menambahkan buku ke dalam daftar top books.
   - **Metode**: POST
   - **Body**:
     - `book_id`: ID buku (wajib).
     - `ranking`: Peringkat buku (wajib).
   - **Respons Sukses**:
     ```json
     {
       "message": "Top book added successfully."
     }
     ```

#### 3. **PUT /api/top_books/{id}**
   - **Deskripsi**: Mengubah peringkat buku pada daftar top books.
   - **Metode**: PUT
   - **Parameter Path**:
     - `{id}`: ID buku yang akan diubah peringkatnya.
   - **Body**:
     - `ranking`: Peringkat baru.
   - **Respons Sukses**:
     ```json
     {
       "message": "Top book updated successfully."
     }
     ```

#### 4. **DELETE /api/top_books/{id}**
   - **Deskripsi**: Menghapus buku dari daftar top books berdasarkan ID.
   - **Metode**: DELETE
   - **Parameter Path**:
     - `{id}`: ID buku yang akan dihapus.
   - **Respons Sukses**:
     ```json
     {
       "message": "Top book deleted successfully."
     }
     ```

---

### **Endpoint: `/api/users`**

#### 1. **GET /api/users**
   - **Deskripsi**: Mendapatkan daftar semua pengguna.
   - **Metode**: GET
   - **Respons Sukses**:
     ```json
     [
       {
         "id": 1,
         "username": "user123",
         "email": "user@example.com"
       }
     ]
     ```

#### 2. **GET /api/users/{id}**
   - **Deskripsi**: Mendapatkan detail pengguna berdasarkan ID.
   - **Metode**: GET
   - **Parameter Path**:
     - `{id}`: ID pengguna.
   - **Respons Sukses**:
     ```json
     {
       "id": 1,
       "username": "user123",
       "email": "user@example.com"
     }
     ```

#### 3. **POST /api/users**
   - **Deskripsi**: Menambahkan pengguna baru.
   - **Metode**: POST
   - **Body**:
     - `username`: Nama pengguna (wajib).
     - `email`: Email pengguna (wajib).
     - `password`: Kata sandi pengguna (wajib).
   - **Respons Sukses**:
     ```json
     {
       "message": "User created successfully."
     }
     ```

#### 4. **PUT /api/users/{id}**
   - **Deskripsi**: Memperbarui data pengguna berdasarkan ID.
   - **Metode**: PUT
   - **Parameter Path**:
     - `{id}`: ID pengguna.
   - **Body**:
     - `username`: Nama pengguna.
     - `email`: Email pengguna.
     - `password`: Kata sandi baru (opsional).
   - **Respons Sukses**:
     ```json
     {
       "message": "User updated successfully."
     }
     ```

#### 5. **DELETE /api/users/{id}**
   - **Deskripsi**: Menghapus pengguna berdasarkan ID.
   - **Metode**: DELETE
   - **Parameter Path**:
     - `{id}`: ID pengguna.
   - **Respons Sukses**:
     ```json
     {
       "message": "User deleted successfully."
     }
     ```

---

### **Endpoint: `/api/peminjaman`**

#### 1. **GET /api/peminjaman**
   - **Deskripsi**: Mendapatkan semua data peminjaman.
   - **Metode**: GET
   - **Respons Sukses**:
     ```json
     [
       {
         "id": 1,
         "user_id": 1,
         "book_id": 1,
         "tanggal_peminjaman": "2024-01-01",
         "tanggal_pengembalian": "2024-01-10",
         "status": "dipinjam"
       }
     ]
     ```

#### 2. **GET /api/peminjaman/{id}**
   - **Deskripsi**: Mendapatkan detail peminjaman berdasarkan ID.
   - **Metode**: GET
   - **Parameter Path**:
     - `{id}`: ID peminjaman.
   - **Respons Sukses**:
     ```json
     {
       "id": 1,
       "user_id": 1,
       "book_id": 1,
       "tanggal_peminjaman": "2024-01-01",
       "tanggal_pengembalian": "2024-01-10",
       "status": "dipinjam"
     }
     ```

#### 3. **POST /api/peminjaman**
   - **Deskripsi**: Menambahkan peminjaman baru.
   - **Metode**: POST
   - **Body**:
     - `user_id`: ID pengguna yang meminjam buku (wajib).
     - `book_id`: ID buku yang dipinjam (wajib).
     - `tanggal_peminjaman`: Tanggal peminjaman (wajib).
   - **Respons Sukses**:
     ```json
     {
       "message": "Peminjaman created successfully."
     }
     ```

#### 4. **PUT /api/peminjaman/{id}**
   - **Deskripsi**: Memperbarui

 status dan tanggal pengembalian peminjaman.
   - **Metode**: PUT
   - **Parameter Path**:
     - `{id}`: ID peminjaman.
   - **Body**:
     - `status`: Status peminjaman (contoh: "dikembalikan").
     - `tanggal_pengembalian`: Tanggal pengembalian.
   - **Respons Sukses**:
     ```json
     {
       "message": "Peminjaman updated successfully."
     }
     ```

#### 5. **DELETE /api/peminjaman/{id}**
   - **Deskripsi**: Menghapus peminjaman berdasarkan ID.
   - **Metode**: DELETE
   - **Parameter Path**:
     - `{id}`: ID peminjaman.
   - **Respons Sukses**:
     ```json
     {
       "message": "Peminjaman deleted successfully."
     }
     ```
