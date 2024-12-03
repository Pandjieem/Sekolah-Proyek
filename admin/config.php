<?php

class Database {
    private mysqli $conn;
    
    public function __construct(
        private string $host = "localhost",
        private string $username = "root",
        private string $password = "",
        private string $database = "user_db"
    ) {
        $this->connect();
    }
    
    private function connect(): void {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
    
    public function query(string $sql): mysqli_result|bool {
        return $this->conn->query($sql);
    }
    
    public function escape(string $string): string {
        return $this->conn->real_escape_string($string);
    }
}

class ImageHandler {
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
    
    public function __construct(
        private Database $db,
        private string $coverPath = '../../assets/cover/',
        private string $imagePath = '../../assets/img/',
        private string $organPath = '../../assets/organ/'
    ) {
        $this->checkDirectories();
    }
    
    private function checkDirectories(): void {
        if (!is_dir($this->coverPath)) mkdir($this->coverPath, 0777, true);
        if (!is_dir($this->imagePath)) mkdir($this->imagePath, 0777, true);
    }
    
    private function validateImageType(array $file): bool {
        return in_array($file['type'], self::ALLOWED_TYPES);
    }
    
    private function uploadImage(array $file, string $target): bool {
        if (!$this->validateImageType($file)) {
            throw new Exception("Hanya file gambar yang diperbolehkan (JPEG, PNG, GIF).");
        }
        return move_uploaded_file($file['tmp_name'], $target);
    }
    
    public function updateCover(array $post, array $files): bool {
        $title = htmlspecialchars($post['title']);
        $image = $files['cover_image']['name'];
        $target = $this->coverPath . basename($image);
        
        if ($this->uploadImage($files['cover_image'], $target)) {
            $sql = "SELECT * FROM cover_image WHERE id = 1";
            $result = $this->db->query($sql);
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (file_exists($row['image_path'])) {
                    unlink($row['image_path']);
                }
                $sql = "UPDATE cover_image SET title='$title', image_path='$target' WHERE id=1";
            } else {
                $sql = "INSERT INTO cover_image (title, image_path) VALUES ('$title', '$target')";
            }
            
            return (bool)$this->db->query($sql);
        }
        return false;
    }
    
    public function addImage(array $post, array $files): bool {
        $title = htmlspecialchars($post['title']);
        $description = htmlspecialchars($post['description']);
        $instagram = htmlspecialchars($post['instagram']);
        $image = $files['image']['name'];
        $target = $this->imagePath . basename($image);
        $img = basename($image);
        
        if ($this->uploadImage($files['image'], $target)) {
            $sql = "INSERT INTO images (title, description, instagram, image_path, img_name) 
                    VALUES ('$title', '$description', '$instagram', '$target', '$img')";
            return (bool)$this->db->query($sql);
        }
        return false;
    }
    
    public function updateImage(int $imageId, array $post, array $files): bool {
        $title = htmlspecialchars($post['title']);
        $description = htmlspecialchars($post['description']);
        $instagram = htmlspecialchars($post['instagram']);
        
        $sql = "SELECT image_path FROM images WHERE id=$imageId";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $oldImage = $row['image_path'];
        
        $imagePath = $oldImage;
        $imgName = basename($oldImage);
        
        if (!empty($files['image']['name'])) {
            if (file_exists($oldImage)) {
                unlink($oldImage);
            }
            
            $target = $this->imagePath . basename($files['image']['name']);
            if ($this->uploadImage($files['image'], $target)) {
                $imagePath = $target;
                $imgName = basename($files['image']['name']);
            } else {
                return false;
            }
        }
        
        $sql = "UPDATE images SET 
                title='$title', 
                description='$description', 
                instagram='$instagram', 
                image_path='$imagePath', 
                img_name='$imgName' 
                WHERE id=$imageId";
                
        return (bool)$this->db->query($sql);
    }
    
    public function deleteImage(int $imageId): bool {
        $sql = "SELECT image_path FROM images WHERE id=$imageId";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        
        if (file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
        
        return (bool)$this->db->query("DELETE FROM images WHERE id=$imageId");
    }

    public function updateOrgan(array $post, array $files, int $organId): bool {
        $title = htmlspecialchars($post['title']);
        $image = $files['organ_image']['name'];
        $target = $this->organPath . basename($image);
        
        if ($this->uploadImage($files['organ_image'], $target)) {
            $sql = "SELECT * FROM organ WHERE id = $organId";
            $result = $this->db->query($sql);
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (file_exists($row['image_path'])) {
                    unlink($row['image_path']);
                }
                $sql = "UPDATE organ SET title='$title', image_path='$target' WHERE id=$organId";
            } else {
                $sql = "INSERT INTO organ (id, title, image_path) VALUES ($organId, '$title', '$target')";
            }
            
            return (bool)$this->db->query($sql);
        }
        return false;
    }
    
    public function getOrgan(int $organId): ?array {
        $result = $this->db->query("SELECT * FROM organ WHERE id = $organId");
        return $result->fetch_assoc();
    }    
    
    public function getAllImages(): mysqli_result|bool {
        return $this->db->query("SELECT * FROM images");
    }
    
    public function getCover(): ?array {
        $result = $this->db->query("SELECT * FROM cover_image WHERE id = 1");
        return $result->fetch_assoc();
    }
}

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
    
    public function redirect(string $location): never {
        header("Location: $location");
        exit();
    }
}

$db = new Database();
$imageHandler = new ImageHandler($db);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update_cover'])) {
            if ($imageHandler->updateCover($_POST, $_FILES)) {
                $_SESSION['success_message'] = "Gambar berhasil disimpan!";
            } else {
                $error = "Gagal mengupdate cover image";
            }
        } elseif (isset($_POST['add_image'])) {
            if ($imageHandler->addImage($_POST, $_FILES)) {
                $_SESSION['success_message'] = "Gambar berhasil ditambahkan!";
            } else {
                $error = "Gagal menambah image";
            }
        } elseif (isset($_POST['update_image'])) {
            $imageId = (int)$_POST['image_id'];
            if ($imageHandler->updateImage($imageId, $_POST, $_FILES)) {
                $_SESSION['success_message'] = "Gambar berhasil diperbarui!";
            } else {
                $error = "Gagal mengubah image";
            }
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        $session->redirect($_SERVER['PHP_SELF']);
    }
}

// Handle DELETE requests
if (isset($_GET['delete'])) {
    if ($imageHandler->deleteImage((int)$_GET['delete'])) {
        $_SESSION['success_message'] = "Gambar berhasil dihapus!";
    } else {
        $error = "Gagal menghapus image";
    }
}

// Tampilkan pesan sukses jika ada
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

// Tampilkan pesan error jika ada
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}

// Get data
$cover = $imageHandler->getCover();
$images = $imageHandler->getAllImages();

?>
