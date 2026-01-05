<?php
session_start();
require_once '../../config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil data pengguna dari database
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM admin_users WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$user) {
    header("Location: ../login.php");
    exit();
}

// Inisialisasi variabel untuk notifikasi
$show_modal = false;
$modal_message = "";
$show_success = false;
$success_message = "";

// Proses update username & password jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_account'])) {
    $username_baru = mysqli_real_escape_string($koneksi, $_POST['username']);
    
    // Validasi username sudah digunakan oleh orang lain
    if ($username_baru != $username) {
        $check_user = mysqli_query($koneksi, "SELECT id FROM admin_users WHERE username = '$username_baru' AND id != {$user['id']}");
        if (mysqli_num_rows($check_user) > 0) {
            $error_account = "Username sudah digunakan!";
        }
    }
    
    // Jika tidak ada error, lanjutkan update
    if (!isset($error_account)) {
        $password_update = "";
        
        // Jika password diisi, maka update password
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $password_update = ", password = '$password'";
        }
        
        // Update data di database
        $update_query = "UPDATE admin_users SET 
                        username = '$username_baru' 
                        $password_update 
                        WHERE id = {$user['id']}";
        
        if (mysqli_query($koneksi, $update_query)) {
            // Set notifikasi untuk modal
            $show_modal = true;
            $modal_message = "Username/Password Berhasil diubah, silahkan login kembali";
            
            // Update session username jika username berubah
            if ($username_baru != $username) {
                $_SESSION['username'] = $username_baru;
                $username = $username_baru;
            }
        } else {
            $error_account = "Gagal memperbarui akun: " . mysqli_error($koneksi);
        }
    }
}

// Proses update profile & nama jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nama_pengguna = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    
    // Handle upload gambar
    $gambar = $user['gambar']; // default pakai gambar lama
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_size = $_FILES['gambar']['size'];
        
        // Validasi tipe file
        if (!in_array($file_ext, $allowed_ext)) {
            $error_profile = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
        } 
        // Validasi ukuran file
        elseif ($file_size > 2000000) { // Max 2MB
            $error_profile = "Ukuran file terlalu besar. Maksimal 2MB.";
        } 
        else {
            // Pastikan folder upload ada
            $upload_dir = "../../uploads/profile/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Cek apakah folder writable
            if (!is_writable($upload_dir)) {
                $error_profile = "Folder upload tidak dapat ditulisi. Hubungi administrator.";
            } 
            else {
                // Hapus gambar lama jika bukan default
                if (!empty($gambar) && $gambar != 'default.png' && file_exists("../../uploads/profile/" . $gambar)) {
                    unlink("../../uploads/profile/" . $gambar);
                }
                
                // Ambil nama asli file tanpa ekstensi
                $original_name = pathinfo($file_name, PATHINFO_FILENAME);
                
                // Format: dosen_namafile.ekstensi
                $new_file_name = 'dosen_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name) . '.' . $file_ext;
                $upload_path = "../../uploads/profile/" . $new_file_name;
                
                // Cek apakah file sudah ada, jika ya tambahkan timestamp
                $counter = 1;
                $base_name = $new_file_name;
                while (file_exists($upload_path)) {
                    $name_without_ext = pathinfo($base_name, PATHINFO_FILENAME);
                    $new_file_name = $name_without_ext . '_' . time() . '_' . $counter . '.' . $file_ext;
                    $upload_path = "../../uploads/profile/" . $new_file_name;
                    $counter++;
                }
                
                // Upload file
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    $gambar = $new_file_name;
                } else {
                    $error_profile = "Gagal mengunggah file. Coba lagi.";
                }
            }
        }
    } 
    // Jika ada error upload selain "no file"
    elseif (isset($_FILES['gambar']) && $_FILES['gambar']['error'] != 4) { // Error 4 = No file uploaded
        $upload_errors = array(
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        $error_code = $_FILES['gambar']['error'];
        $error_profile = "Error upload: " . (isset($upload_errors[$error_code]) ? $upload_errors[$error_code] : "Unknown error");
    }
    
    // Jika tidak ada error upload, lanjutkan update database
    if (!isset($error_profile)) {
        // Update data di database
        $update_query = "UPDATE admin_users SET 
                        nama = '$nama_pengguna', 
                        gambar = '$gambar' 
                        WHERE id = {$user['id']}";
        
        if (mysqli_query($koneksi, $update_query)) {
            // Update nama di tabel pengumuman jika nama berubah
            if ($nama_pengguna != $user['nama']) {
                $update_pengumuman = "UPDATE pengumuman SET penulis = '$nama_pengguna' WHERE penulis = '{$user['nama']}'";
                mysqli_query($koneksi, $update_pengumuman);
            }
            
            // Update session nama
            $_SESSION['nama'] = $nama_pengguna;
            
            // Refresh data user
            $query = mysqli_query($koneksi, "SELECT * FROM admin_users WHERE username = '$username'");
            $user = mysqli_fetch_assoc($query);
            
            // Set notifikasi success
            $show_success = true;
            $success_message = "Foto profil dan nama pengguna berhasil diperbarui!";
        } else {
            $error_profile = "Gagal memperbarui profile: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-weight: 600;
        }
        .dashboard-header {
            background-color: #f8f9fa;
            padding: 14px 25px;
            font-size: 26px;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            margin-bottom: 25px;
        }
        .dashboard-header h3 {
            font-weight: 700;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #143c8c;
        }
        .sidebar h4, .sidebar span {
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px;
            display: block;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.15);
        }
        .profile-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: 0 auto;
        }
        .profile-left {
            text-align: center;
            padding-right: 40px;
            border-right: 1px solid #e0e0e0;
        }
        .profile-picture {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #143c8c;
            margin-bottom: 25px;
            background-color: #f5f5f5;
        }
        .user-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #143c8c;
        }
        .user-level {
            background: #143c8c;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 16px;
            display: inline-block;
            margin-top: 10px;
            font-weight: 600;
        }
        .profile-right {
            padding-left: 40px;
        }
        .form-control {
            border: 2px solid #ddd;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #143c8c;
            box-shadow: 0 0 0 0.2rem rgba(20, 60, 140, 0.15);
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        .btn-primary {
            background-color: #143c8c;
            border-color: #143c8c;
            padding: 12px 40px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0d2a5c;
            border-color: #0d2a5c;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .file-input-container {
            position: relative;
            margin-bottom: 5px;
        }
        .file-input-label {
            display: block;
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            color: #666;
            transition: all 0.3s ease;
        }
        .file-input-label:hover {
            background: #e9ecef;
            border-color: #143c8c;
            color: #143c8c;
        }
        .current-file {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            display: block;
            font-style: italic;
        }
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #143c8c;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #143c8c;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #143c8c;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
        }
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
        }
        .form-section h5 {
            color: #143c8c;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .debug-info {
            display: none;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4>SAPA</h4>
        <span>Sistem Pengumuman<br>Akademik Online</span>
        <hr class="text-light">

        <a href="profile.php" class="active">Profile</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="pengumuman.php">Pengumuman</a>
        <a href="mahasiswa.php">Mahasiswa</a>
        <a href="dosen.php">Dosen</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-fill p-4">
        <div class="dashboard-header">
            <h3>Profile Akun</h3>
        </div>

        <!-- Notifikasi Success Profile -->
        <?php if($show_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <div class="row">
                <!-- Kolom Kiri: Foto Profile dan Info -->
                <div class="col-md-4 profile-left">
                    <?php
                    // Cek apakah gambar ada di database
                    if (!empty($user['gambar'])) {
                        $gambar_path = "../../uploads/profile/" . $user['gambar'];
                        // Cek apakah file benar-benar ada (FIXED: gunakan path yang benar)
                        if (file_exists($gambar_path)) {
                            $profile_pic = $gambar_path;
                        } else {
                            // Jika file tidak ada, gunakan default
                            $profile_pic = "../../uploads/profile/default.png";
                        }
                    } else {
                        $profile_pic = "../../uploads/profile/default.png";
                    }
                    
                    // Debug info (bisa diaktifkan jika perlu)
                    // echo "<!-- Debug: gambar_path = $gambar_path -->";
                    // echo "<!-- Debug: file_exists = " . (file_exists($gambar_path) ? 'true' : 'false') . " -->";
                    ?>
                    <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-picture" id="profile-preview">
                    
                    <div class="user-name"><?php echo htmlspecialchars($user['nama']); ?></div>
                    <div class="user-level">
                        <?php echo ucfirst($user['role']); ?>
                    </div>
                    
                    <div class="info-box mt-4">
                        <div class="info-label">Username Saat Ini</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
                        
                        <div class="info-label mt-3">Role User</div>
                        <div class="info-value">Dosen</div>
                        
                        <div class="info-label mt-3">Foto Profil</div>
                        <div class="info-value">
                            <?php 
                            echo !empty($user['gambar']) ? $user['gambar'] : 'default.png';
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Edit Profile -->
                <div class="col-md-8 profile-right">
                    <!-- Form 1: Username & Password -->
                    <div class="form-section">
                        <h5>Ubah Username & Password</h5>
                        
                        <?php if(isset($error_account)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $error_account; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="accountForm">
                            <input type="hidden" name="update_account" value="1">
                            
                            <div class="mb-4">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($user['username']); ?>" required
                                       placeholder="Masukkan username baru">
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Masukkan Password Baru (kosongkan jika tidak ingin mengubah)">
                                <small class="text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Ulangi Password Baru">
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Form 2: Foto Profile & Nama Pengguna -->
                    <div class="form-section">
                        <h5>Ubah Foto Profile & Nama Pengguna</h5>
                        
                        <?php if(isset($error_profile)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $error_profile; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" enctype="multipart/form-data" id="profileForm">
                            <input type="hidden" name="update_profile" value="1">
                            
                            <div class="mb-4">
                                <label for="gambar" class="form-label">Unggah Gambar</label>
                                <div class="file-input-container">
                                    <input type="file" class="form-control d-none" id="gambar" name="gambar" accept="image/*">
                                    <label for="gambar" class="file-input-label">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>Pilih File
                                    </label>
                                    <span class="current-file" id="file-name">Tidak ada file yang dipilih</span>
                                </div>
                                <small class="text-muted">
                                    Ukuran maksimal 2MB. Format: JPG, PNG, GIF<br>
                                </small>
                                <?php if(!empty($user['gambar']) && $user['gambar'] != 'default.png'): ?>
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Foto saat ini: <strong><?php echo $user['gambar']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-4">
                                <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" 
                                       value="<?php echo htmlspecialchars($user['nama']); ?>" required
                                       placeholder="Masukkan nama pengguna">
                                <small class="text-muted">Nama ini akan muncul sebagai penulis di pengumuman</small>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifikasi untuk Username/Password -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                <p class="fs-5" id="notificationMessage"><?php echo $modal_message; ?></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" id="okButton">Oke</button>
            </div>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    // Preview gambar sebelum upload
    document.getElementById('gambar').addEventListener('change', function(e) {
        const fileName = document.getElementById('file-name');
        const preview = document.getElementById('profile-preview');
        
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            fileName.textContent = 'Tidak ada file yang dipilih';
        }
    });

    // Validasi form username & password
    document.getElementById('accountForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Validasi username
        if (username.length < 3) {
            alert('Username harus minimal 3 karakter');
            e.preventDefault();
            return;
        }
        
        // Validasi password (jika diisi)
        if (password.length > 0) {
            if (password.length < 6) {
                alert('Password harus minimal 6 karakter');
                e.preventDefault();
                return;
            }
            
            if (password !== confirmPassword) {
                alert('Password dan konfirmasi password tidak cocok');
                e.preventDefault();
                return;
            }
        }
    });

    // Validasi form profile
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const nama = document.getElementById('nama_pengguna').value.trim();
        
        // Validasi nama
        if (nama.length < 2) {
            alert('Nama pengguna harus minimal 2 karakter');
            e.preventDefault();
            return;
        }
        
        // Validasi file (jika dipilih)
        const fileInput = document.getElementById('gambar');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            // Cek tipe file
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF');
                e.preventDefault();
                return;
            }
            
            // Cek ukuran file
            if (file.size > maxSize) {
                alert('Ukuran file maksimal 2MB');
                e.preventDefault();
                return;
            }
        }
    });

    // Handle modal notifikasi jika ada (untuk username/password)
    <?php if($show_modal): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            notificationModal.show();
            
            // Redirect ke logout ketika tombol Oke ditekan
            document.getElementById('okButton').addEventListener('click', function() {
                window.location.href = '../auth/logout.php';
            });
        });
    <?php endif; ?>

    // Auto-hide alerts setelah 5 detik
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>

</body>
</html>