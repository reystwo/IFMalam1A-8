<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard-Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
        #confirm-nik, #confirm-nama, #confirm-prodi, #confirm-jabatan {
            font-weight: normal;
            color: #333;
        }
        .modal-body .text-start p {
            margin-bottom: 5px;
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
        <a href="pengumuman.php">Pengumuman</a>
        <a href="mahasiswa.php">Mahasiswa</a>
        <a href="dosen.php" class="active">Dosen</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-fill p-4">
        <div class="dashboard-header">
            <h3>Daftar Dosen</h3>
        </div>

        <!-- Tombol Tambah Data -->
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
            <i class="fas fa-plus-circle me-2"></i>TAMBAH DATA DOSEN
        </button>

        <!-- Modal Tambah Data -->
        <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahDataLabel">Tambah Data Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../admin/action/add-dosen.php" method="POST">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Dosen</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input type="text" class="form-control" id="prodi" name="prodi" required>
                            </div>
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi add-dosen -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">Notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <p class="fs-5" id="notificationMessage">Data Berhasil Disimpan</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Oke</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Konfirmasi edit-dosen -->
        <div class="modal fade" id="confirmEditModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="confirmEditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmEditModalLabel">Konfirmasi Edit Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <p class="fs-5">Pastikan data yang akan diubah sudah benar?</p>
                        <div class="text-start mt-3">
                            <p><strong>Data yang akan diubah:</strong></p>
                            <p><strong>NIK:</strong> <span id="confirm-nik"></span></p>
                            <p><strong>Nama:</strong> <span id="confirm-nama"></span></p>
                            <p><strong>Program Studi:</strong> <span id="confirm-prodi"></span></p>
                            <p><strong>Jabatan:</strong> <span id="confirm-jabatan"></span></p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirm-edit-btn">Ya, Update Data</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Konfirmasi delete-dosen -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                        <p class="fs-5">Apakah Anda yakin ingin menghapus data ini?</p>
                        <div class="text-start mt-3">
                            <p><strong>Data yang akan dihapus:</strong></p>
                            <p><strong>NIK:</strong> <span id="delete-nik"></span></p>
                            <p><strong>Nama:</strong> <span id="delete-nama"></span></p>
                            <p><strong>Program Studi:</strong> <span id="delete-prodi"></span></p>
                            <p><strong>Jabatan:</strong> <span id="delete-jabatan"></span></p>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Perhatian:</strong> Data yang dihapus tidak dapat dikembalikan!
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <a href="#" class="btn btn-danger" id="confirm-delete-btn">Ya, Hapus Data</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal edit-dosen -->
        <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDataLabel">Edit Data Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <form id="editForm" method="POST">
                            <input type="hidden" id="edit-nik" name="nik">
                            <div class="mb-3">
                                <label for="edit-nama" class="form-label">Nama Dosen</label>
                                <input type="text" class="form-control" id="edit-nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-prodi" class="form-label">Program Studi</label>
                                <input type="text" class="form-control" id="edit-prodi" name="prodi" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="edit-jabatan" name="jabatan" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="show-confirm-btn">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Dosen -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">NIK</th>
                    <th scope="col">NAMA DOSEN</th>
                    <th scope="col">PROGRAM STUDI</th>
                    <th scope="col">JABATAN</th>
                    <th scope="col">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../../config/koneksi.php';

                $query = mysqli_query($koneksi, "SELECT * FROM dosen");
                $no = 1;
                while ($data = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['nik']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['prodi']; ?></td>
                        <td><?php echo $data['jabatan']; ?></td>
                        <td>
                            <!-- edit-dosen-btn -->
                            <button class="btn btn-success btn-sm me-1 edit-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDataModal"
                                    data-nik="<?php echo $data['nik']; ?>"
                                    data-nama="<?php echo $data['nama']; ?>"
                                    data-prodi="<?php echo $data['prodi']; ?>"
                                    data-jabatan="<?php echo $data['jabatan']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <!-- delete-dosen-btn -->
                            <button class="btn btn-danger btn-sm delete-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal"
                                    data-nik="<?php echo $data['nik']; ?>"
                                    data-nama="<?php echo $data['nama']; ?>"
                                    data-prodi="<?php echo $data['prodi']; ?>"
                                    data-jabatan="<?php echo $data['jabatan']; ?>">
                                <i class="fas fa-trash-alt"></i> Delete
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

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- JS add-dosen -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const notif = urlParams.get('notif');
        const msg = urlParams.get('msg');

        if (notif && msg) {
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const messageElement = document.getElementById('notificationMessage');

            messageElement.textContent = msg;

            const icon = document.querySelector('#notificationModal .modal-body i');
            if (notif === 'success') {
                icon.className = 'fas fa-check-circle text-success fa-3x mb-3';
            } else {
                icon.className = 'fas fa-exclamation-circle text-danger fa-3x mb-3';
            }
            notificationModal.show();

            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>

<!-- JS edit-dosen -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const nik = this.getAttribute('data-nik');
                const nama = this.getAttribute('data-nama');
                const prodi = this.getAttribute('data-prodi');
                const jabatan = this.getAttribute('data-jabatan');

                document.getElementById('edit-nik').value = nik;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-prodi').value = prodi;
                document.getElementById('edit-jabatan').value = jabatan;
            });
        });
    });
</script>

<!-- JS Konfirmasi edit-dosen -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let editFormData = null;
        
        const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editDataModal'));
        const confirmEditModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmEditModal'), {
            backdrop: 'static',
            keyboard: false
        });
        
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const nik = this.getAttribute('data-nik');
                const nama = this.getAttribute('data-nama');
                const prodi = this.getAttribute('data-prodi');
                const jabatan = this.getAttribute('data-jabatan');

                document.getElementById('edit-nik').value = nik;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-prodi').value = prodi;
                document.getElementById('edit-jabatan').value = jabatan;
                
                editFormData = { nik, nama, prodi, jabatan };
            });
        });

        document.getElementById('show-confirm-btn').addEventListener('click', function() {
            const formNik = document.getElementById('edit-nik').value;
            const formNama = document.getElementById('edit-nama').value;
            const formProdi = document.getElementById('edit-prodi').value;
            const formJabatan = document.getElementById('edit-jabatan').value;
            
            document.getElementById('confirm-nik').textContent = formNik;
            document.getElementById('confirm-nama').textContent = formNama;
            document.getElementById('confirm-prodi').textContent = formProdi;
            document.getElementById('confirm-jabatan').textContent = formJabatan;
            
            editModal.hide();

            setTimeout(() => {
                confirmEditModal.show();
            }, 300);
        });

        document.getElementById('confirm-edit-btn').addEventListener('click', function() {
            confirmEditModal.hide();
            
            document.getElementById('editForm').action = '../../admin/action/edit-dosen.php';
            document.getElementById('editForm').submit();
        });
    });
</script>

<!-- JS Konfirmasi delete-dosen -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmDeleteModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmDeleteModal'), {
            backdrop: 'static',
            keyboard: false
        });
        
        let deleteUrl = '';
        
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                
                const nik = this.getAttribute('data-nik');
                const nama = this.getAttribute('data-nama');
                const prodi = this.getAttribute('data-prodi');
                const jabatan = this.getAttribute('data-jabatan');
                
                document.getElementById('delete-nik').textContent = nik;
                document.getElementById('delete-nama').textContent = nama;
                document.getElementById('delete-prodi').textContent = prodi;
                document.getElementById('delete-jabatan').textContent = jabatan;
                
                deleteUrl = `../../admin/action/delete-dosen.php?nik=${nik}`;
            });
        });

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            confirmDeleteModal.hide();
            
            if (deleteUrl) {
                window.location.href = deleteUrl;
            }
        });
        
        document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function () {
            deleteUrl = '';
        });
    });
</script>

</body>
</html>