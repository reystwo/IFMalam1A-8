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
        #confirm-nim, #confirm-nama, #confirm-jurusan, #confirm-angkatan {
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
        <a href="mahasiswa.php" class="active">Mahasiswa</a>
        <a href="dosen.php">Dosen</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-fill p-4">
        <div class="dashboard-header">
            <h3>Daftar Mahasiswa</h3>
        </div>

        <!-- Tombol Tambah Data -->
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
            <i class="fas fa-plus-circle me-2"></i>TAMBAH DATA MAHASISWA
        </button>

        <!-- Modal Tambah Data -->
        <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahDataLabel">Tambah Data Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../admin/action/add-mhs.php" method="POST">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                            </div>
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="text" class="form-control" id="angkatan" name="angkatan" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi add-mhs -->
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

        <!-- Modal Notifikasi Konfirmasi edit-mhs -->
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
                            <p><strong>NIM:</strong> <span id="confirm-nim"></span></p>
                            <p><strong>Nama:</strong> <span id="confirm-nama"></span></p>
                            <p><strong>Jurusan:</strong> <span id="confirm-jurusan"></span></p>
                            <p><strong>Angkatan:</strong> <span id="confirm-angkatan"></span></p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirm-edit-btn">Ya, Update Data</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Konfirmasi delete-mhs -->
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
                            <p><strong>NIM:</strong> <span id="delete-nim"></span></p>
                            <p><strong>Nama:</strong> <span id="delete-nama"></span></p>
                            <p><strong>Jurusan:</strong> <span id="delete-jurusan"></span></p>
                            <p><strong>Angkatan:</strong> <span id="delete-angkatan"></span></p>
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

        <!-- Modal edit-mhs -->
        <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDataLabel">Edit Data Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form id="editForm" method="POST">
                            <input type="hidden" id="edit-nim" name="nim">
                            <div class="mb-3">
                                <label for="edit-nama" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="edit-nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-jurusan" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="edit-jurusan" name="jurusan" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-angkatan" class="form-label">Angkatan</label>
                                <input type="text" class="form-control" id="edit-angkatan" name="angkatan" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="show-confirm-btn">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Mahasiswa -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">NIM</th>
                    <th scope="col">NAMA MAHASISWA</th>
                    <th scope="col">JURUSAN</th>
                    <th scope="col">ANGKATAN</th>
                    <th scope="col">AKSI</th>
                </tr>
            </thead>
            <tbody>

                <?php
                require_once '../../config/koneksi.php';

                $query = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
                $no = 1;
                while ($data = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['nim']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['jurusan']; ?></td>
                        <td><?php echo $data['angkatan']; ?></td>
                        <td>
                            <!-- edit-mhs-btn -->
                            <button class="btn btn-success btn-sm me-1 edit-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDataModal"
                                    data-nim="<?php echo $data['nim']; ?>"
                                    data-nama="<?php echo $data['nama']; ?>"
                                    data-jurusan="<?php echo $data['jurusan']; ?>"
                                    data-angkatan="<?php echo $data['angkatan']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <!-- delete-mhs-btn -->
                            <button class="btn btn-danger btn-sm delete-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal"
                                    data-nim="<?php echo $data['nim']; ?>"
                                    data-nama="<?php echo $data['nama']; ?>"
                                    data-jurusan="<?php echo $data['jurusan']; ?>"
                                    data-angkatan="<?php echo $data['angkatan']; ?>">
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

<!-- JS add-mhs -->
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

<!-- JS edit-mhs -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const nim = this.getAttribute('data-nim');
                const nama = this.getAttribute('data-nama');
                const jurusan = this.getAttribute('data-jurusan');
                const angkatan = this.getAttribute('data-angkatan');

                document.getElementById('edit-nim').value = nim;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-jurusan').value = jurusan;
                document.getElementById('edit-angkatan').value = angkatan;
            });
        });
    });
</script>


<!-- JS Konfirmasi edit-mhs -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let editFormData = null;
        
        const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editDataModal'));
        const confirmEditModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmEditModal'), {
            backdrop: 'static',
            keyboard: false
        });
        
        // Tombol edit di tabel
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const nim = this.getAttribute('data-nim');
                const nama = this.getAttribute('data-nama');
                const jurusan = this.getAttribute('data-jurusan');
                const angkatan = this.getAttribute('data-angkatan');

                // Isi form edit
                document.getElementById('edit-nim').value = nim;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-jurusan').value = jurusan;
                document.getElementById('edit-angkatan').value = angkatan;
                
                editFormData = { nim, nama, jurusan, angkatan };
            });
        });

        // update-btn 
        document.getElementById('show-confirm-btn').addEventListener('click', function() {
            const formNim = document.getElementById('edit-nim').value;
            const formNama = document.getElementById('edit-nama').value;
            const formJurusan = document.getElementById('edit-jurusan').value;
            const formAngkatan = document.getElementById('edit-angkatan').value;
            
            document.getElementById('confirm-nim').textContent = formNim;
            document.getElementById('confirm-nama').textContent = formNama;
            document.getElementById('confirm-jurusan').textContent = formJurusan;
            document.getElementById('confirm-angkatan').textContent = formAngkatan;
            
            editModal.hide();

            setTimeout(() => {
                confirmEditModal.show();
            }, 300);
        });

        // confitm-edit-btn
        document.getElementById('confirm-edit-btn').addEventListener('click', function() {
            confirmEditModal.hide();
            
            document.getElementById('editForm').action = '../../admin/action/edit-mhs.php';
            document.getElementById('editForm').submit();
        });
    });
</script>

<!-- JS Konfirmasi delete-mhs -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmDeleteModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmDeleteModal'), {
            backdrop: 'static',
            keyboard: false
        });
        
        let deleteUrl = '';
        
        // delete-btn
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                
                const nim = this.getAttribute('data-nim');
                const nama = this.getAttribute('data-nama');
                const jurusan = this.getAttribute('data-jurusan');
                const angkatan = this.getAttribute('data-angkatan');
                
                document.getElementById('delete-nim').textContent = nim;
                document.getElementById('delete-nama').textContent = nama;
                document.getElementById('delete-jurusan').textContent = jurusan;
                document.getElementById('delete-angkatan').textContent = angkatan;
                
                deleteUrl = `../../admin/action/delete-mhs.php?nim=${nim}`;
            });
        });

        // confirm-delete-btn
        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            confirmDeleteModal.hide();
            
            if (deleteUrl) {
                window.location.href = deleteUrl;
            }
        });
        
        // batal-and-x-btn
        document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function () {
            deleteUrl = '';
        });
    });
</script>

</body>
</html>