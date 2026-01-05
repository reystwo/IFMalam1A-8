<?php
session_start();
require_once '../../config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard-Admin</title>
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
            position: fixed;
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
        #confirm-judul, #confirm-kategori, #confirm-penulis, #confirm-status {
            font-weight: normal;
            color: #333;
        }
        .modal-body .text-start p {
            margin-bottom: 5px;
        }
        .table-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .pdf-badge {
            background-color: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }
        .isi-preview {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
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

        <a href="profile.php">Profile</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="pengumuman.php" class="active">Pengumuman</a>
        <a href="mahasiswa.php">Mahasiswa</a>
        <a href="dosen.php">Dosen</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content p-4">
        <div class="dashboard-header">
            <h3>Daftar Pengumuman</h3>
        </div>

        <!-- Alert Notifikasi -->
        <?php if(isset($_GET['notif']) && isset($_GET['msg'])): ?>
            <div class="alert alert-<?php echo $_GET['notif'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                <?php echo $_GET['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tombol Tambah Data -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
            <i class="fas fa-plus-circle me-2"></i>TAMBAH DATA PENGUMUMAN
        </button>

        <!-- Modal Tambah Data -->
        <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahDataLabel">Tambah Data Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../admin/action/add-pengumuman.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Pengumuman</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="isi" class="form-label">Isi Pengumuman</label>
                                <textarea class="form-control" id="isi" name="isi" rows="4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Akademik">Akademik</option>
                                            <option value="Non Akademik">Non Akademik</option>
                                            <option value="Beasiswa">Beasiswa</option>
                                            <option value="Kemahasiswaan">Kemahasiswaan</option>
                                            <option value="Lomba">Lomba</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label">Gambar (opsional)</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pdf" class="form-label">File PDF (opsional)</label>
                                        <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Data -->
        <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDataLabel">Edit Data Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST" enctype="multipart/form-data" action="../../admin/action/edit-pengumuman.php">
                            <input type="hidden" id="edit-id" name="id">
                            <div class="mb-3">
                                <label for="edit-judul" class="form-label">Judul Pengumuman</label>
                                <input type="text" class="form-control" id="edit-judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-isi" class="form-label">Isi Pengumuman</label>
                                <textarea class="form-control" id="edit-isi" name="isi" rows="4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-kategori" class="form-label">Kategori</label>
                                        <select class="form-control" id="edit-kategori" name="kategori" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Akademik">Akademik</option>
                                            <option value="Non Akademik">Non Akademik</option>
                                            <option value="Beasiswa">Beasiswa</option>
                                            <option value="Kemahasiswaan">Kemahasiswaan</option>
                                            <option value="Lomba">Lomba</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-status" class="form-label">Status</label>
                                        <select class="form-control" id="edit-status" name="status">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-gambar" class="form-label">Gambar</label>
                                        <input type="file" class="form-control" id="edit-gambar" name="gambar" accept="image/*">
                                        <div id="current-gambar-info" class="mt-2"></div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="hapus_gambar" value="1" id="hapus-gambar">
                                            <label class="form-check-label text-danger" for="hapus-gambar">
                                                Hapus gambar saat ini
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-pdf" class="form-label">File PDF</label>
                                        <input type="file" class="form-control" id="edit-pdf" name="pdf" accept=".pdf">
                                        <div id="current-pdf-info" class="mt-2"></div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="hapus_pdf" value="1" id="hapus-pdf">
                                            <label class="form-check-label text-danger" for="hapus-pdf">
                                                Hapus PDF saat ini
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Pengumuman -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">NO</th>
                        <th scope="col">JUDUL</th>
                        <th scope="col">ISI</th>
                        <th scope="col">KATEGORI</th>
                        <th scope="col">TANGGAL</th>
                        <th scope="col">FILE</th>
                        <th scope="col">PENULIS</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY date DESC");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                        // Ambil 12 kata pertama dari isi
                        $isi_preview = $data['isi'];
                        $words = explode(' ', $isi_preview);
                        $preview = implode(' ', array_slice($words, 0, 12));
                        if (count($words) > 12) {
                            $preview .= '...';
                        }
                        
                        // Format tanggal
                        $tanggal = date('d/m/Y H:i', strtotime($data['date']));
                        
                        // Tampilkan file
                        $file_tampil = '';
                        if (!empty($data['gambar'])) {
                            $file_tampil = '<img src="../../uploads/images/' . $data['gambar'] . '" class="table-img" alt="Gambar">';
                        } elseif (!empty($data['pdf'])) {
                            $file_tampil = '<span class="pdf-badge"><i class="fas fa-file-pdf me-1"></i>' . $data['pdf'] . '</span>';
                        } else {
                            $file_tampil = '-';
                        }
                        
                        // Status badge
                        $status_badge = $data['status'] == 'published' 
                            ? '<span class="badge bg-success">Published</span>' 
                            : '<span class="badge bg-secondary">Draft</span>';
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($data['judul']); ?></td>
                            <td class="isi-preview" title="<?php echo htmlspecialchars($data['isi']); ?>">
                                <?php echo htmlspecialchars($preview); ?>
                            </td>
                            <td><?php echo $data['kategori']; ?></td>
                            <td><?php echo $tanggal; ?></td>
                            <td><?php echo $file_tampil; ?></td>
                            <td><?php echo $data['penulis']; ?></td>
                            <td><?php echo $status_badge; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1 edit-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDataModal"
                                        data-id="<?php echo $data['id']; ?>"
                                        data-judul="<?php echo htmlspecialchars($data['judul']); ?>"
                                        data-isi="<?php echo htmlspecialchars($data['isi']); ?>"
                                        data-kategori="<?php echo $data['kategori']; ?>"
                                        data-status="<?php echo $data['status']; ?>"
                                        data-gambar="<?php echo $data['gambar']; ?>"
                                        data-pdf="<?php echo $data['pdf']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-button"
                                        onclick="confirmDelete(<?php echo $data['id']; ?>, '<?php echo htmlspecialchars(addslashes($data['judul'])); ?>')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    // Fungsi untuk edit data
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const isi = this.getAttribute('data-isi');
                const kategori = this.getAttribute('data-kategori');
                const status = this.getAttribute('data-status');
                const gambar = this.getAttribute('data-gambar');
                const pdf = this.getAttribute('data-pdf');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-judul').value = judul;
                document.getElementById('edit-isi').value = isi;
                document.getElementById('edit-kategori').value = kategori;
                document.getElementById('edit-status').value = status;
                
                // Reset checkbox
                document.getElementById('hapus-gambar').checked = false;
                document.getElementById('hapus-pdf').checked = false;
                
                // Tampilkan info file saat ini
                const gambarInfo = document.getElementById('current-gambar-info');
                const pdfInfo = document.getElementById('current-pdf-info');
                
                if (gambar) {
                    gambarInfo.innerHTML = '<small class="text-success">File saat ini: ' + gambar + '</small>';
                } else {
                    gambarInfo.innerHTML = '<small class="text-muted">Tidak ada gambar</small>';
                }
                
                if (pdf) {
                    pdfInfo.innerHTML = '<small class="text-success">File saat ini: ' + pdf + '</small>';
                } else {
                    pdfInfo.innerHTML = '<small class="text-muted">Tidak ada PDF</small>';
                }
            });
        });
    });
    
    // Fungsi untuk konfirmasi hapus
    function confirmDelete(id, judul) {
        if (confirm('Apakah Anda yakin ingin menghapus pengumuman "' + judul + '"?')) {
            window.location.href = '../../admin/action/delete-pengumuman.php?id=' + id;
        }
    }
</script>

</body>
</html>