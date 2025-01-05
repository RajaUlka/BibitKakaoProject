<?php
$host = 'localhost';
$dbname = 'bibitkakaodb';
$username = 'root';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}


class Profil {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function update($user_id, $username, $email, $pass = null) {
        if ($pass) {
            // Hash password baru
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            
            // SQL untuk memperbarui username, email, dan password
            $sql = "UPDATE user SET username = :username, email = :email, pass = :pass WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'pass' => $hashed_pass,  // Menggunakan password yang sudah di-hash
                'user_id' => $user_id
            ]);
        } else {
            // SQL untuk memperbarui username dan email saja (tanpa password)
            $sql = "UPDATE user SET username = :username, email = :email WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'user_id' => $user_id
            ]);
        }
    }
    
    
    

    public function getUserById($user_id) {
        $sql = "SELECT * FROM user WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDevicesByUser($user_id) {
        $sql = "SELECT * FROM device WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($username, $pass, $email, $created_at) {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, pass, email, created_at) VALUES (:username, :pass, :email, :created_at)";
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute(['username' => $username, 'pass' => $hashed_pass, 'email' => $email, 'created_at' => $created_at]);
            header("Location: ./register.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username atau email sudah digunakan.";
            } else {
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
            echo $error;
        }
    }

    public function update($user_id, $username, $email, $password = null) {
        if ($password) {
            // Hash password baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            // SQL untuk memperbarui username, email, dan password
            $sql = "UPDATE user SET username = :username, email = :email, pass = :pass WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'pass' => $hashed_password,
                'user_id' => $user_id
            ]);
        } else {
            // SQL untuk memperbarui username dan email saja
            $sql = "UPDATE user SET username = :username, email = :email WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'user_id' => $user_id
            ]);
        }
    }
    
    public function delete($user_id) {
        try {
            // Mulai transaksi untuk menjaga integritas data
            $this->pdo->beginTransaction();
    
            // Hapus semua perangkat yang terkait dengan user ini
            $deleteDevicesSQL = "DELETE FROM device WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($deleteDevicesSQL);
            $stmt->execute(['user_id' => $user_id]);
    
            // Hapus user itu sendiri
            $deleteUserSQL = "DELETE FROM user WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($deleteUserSQL);
            $stmt->execute(['user_id' => $user_id]);
    
            // Commit jika semua berhasil
            $this->pdo->commit();
        } catch (Exception $e) {
            // Rollback jika terjadi kesalahan
            $this->pdo->rollBack();
            die("Gagal menghapus user: " . $e->getMessage());
        }
    }

    public function resetPassword($user_id)
{
    $sql = "UPDATE user SET pass = :pass WHERE user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    try {
        $stmt->execute([
            'pass' => password_hash('123', PASSWORD_DEFAULT), // Menggunakan hash untuk keamanan
            'user_id' => $user_id
        ]);
        // Log jika berhasil
        error_log("Password reset berhasil untuk user_id: $user_id");
    } catch (PDOException $e) {
        // Log jika terjadi kesalahan
        error_log("Error saat reset password: " . $e->getMessage());
        throw $e;  // Lempar exception untuk ditangani lebih lanjut
    }
}

    

    public function getAllUsers() {
        $sql = "SELECT user_id, username, email FROM user";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserById($user_id) {
        $sql = "SELECT * FROM user WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

class Device {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllDevices() {
        $sql = "SELECT * FROM device";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($device_name, $device_type, $device_status, $user_id, $created_at) {
        $sql = "INSERT INTO device (device_name, device_type, device_status, user_id, created_at) VALUES (:device_name, :device_type, :device_status, :user_id, :created_at)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'device_name' => $device_name,
            'device_type' => $device_type,
            'device_status' => $device_status,
            'user_id' => $user_id,
            'created_at' => $created_at
        ]);
        header("Location: ./device.php");
        exit();
    }

    public function update($device_id, $device_name, $device_type, $device_status, $user_id) {
        $sql = "UPDATE device SET device_name = :device_name, device_type = :device_type, device_status = :device_status, user_id = :user_id WHERE device_id = :device_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'device_name' => $device_name,
            'device_type' => $device_type,
            'device_status' => $device_status,
            'user_id' => $user_id,
            'device_id' => $device_id
        ]);
        header("Location: ./device.php");
        exit();
    }

    public function delete($device_id) {
        $sql = "DELETE FROM device WHERE device_id = :device_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['device_id' => $device_id]);
        header("Location: ./device.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action == 'create_user') {
        $user = new User($pdo);
        $user->create($_POST['username'], $_POST['pass'], $_POST['email'], date('Y-m-d H:i:s'));
    } elseif ($action == 'update_user') {
        $user = new User($pdo);
        $user->update($_POST['user_id'], $_POST['username'], $_POST['email']);
    } elseif ($action == 'delete_user') {
        $user = new User($pdo);
        $user->delete($_POST['user_id']);
    } elseif ($action == 'create_device') {
        $device = new Device($pdo);
        $device->create($_POST['device_name'], $_POST['device_type'], $_POST['device_status'], $_POST['user_id'], date('Y-m-d H:i:s'));
    } elseif ($action == 'update_device') {
        $device = new Device($pdo);
        $device->update($_POST['device_id'], $_POST['device_name'], $_POST['device_type'], $_POST['device_status'], $_POST['user_id']);
    } elseif ($action == 'delete_device') {
        $device = new Device($pdo);
        $device->delete($_POST['device_id']);
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk memeriksa apakah user memiliki device
function userHasDevice($pdo, $user_id) {
    $query_device = $pdo->prepare("SELECT COUNT(*) FROM device WHERE user_id = :user_id");
    $query_device->execute(['user_id' => $user_id]);
    return $query_device->fetchColumn() > 0;
}
?>
