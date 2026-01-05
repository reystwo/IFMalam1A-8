<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard-Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
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
        .card-custom {
            color: white;
            border-radius: 14px;
            height: 150px;
            position: relative;
            font-weight: 500;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }
        .card-custom h2 {
            font-size: 32px;
        }
        .bg-purple {
            background: linear-gradient(135deg, #5f5ad9, #807be9);
        }

        .bg-blue {
            background: linear-gradient(135deg, #1976d2, #42a5f5);
        }

        .bg-orange {
            background: linear-gradient(135deg, #f9a825, #ffd54f);
        }

        .bg-red {
            background: linear-gradient(135deg, #d32f2f, #ef5350);
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
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="pengumuman.php">Pengumuman</a>
        <a href="mahasiswa.php">Mahasiswa</a>
        <a href="dosen.php">Dosen</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-fill p-4">
        <div class="dashboard-header">
            <h3>Dashboard</h3>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card card-custom bg-purple p-3">
                    <h2>
                        <?php
                        require_once '../../config/koneksi.php';
                        $query_pengumuman = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengumuman");
                        $data_pengumuman = mysqli_fetch_assoc($query_pengumuman);
                        echo $data_pengumuman['total'];
                        ?>
                    </h2>
                    <p>Total Berita</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom bg-blue p-3">
                    <h2>
                        <?php
                        $query_mahasiswa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mahasiswa");
                        $data_mahasiswa = mysqli_fetch_assoc($query_mahasiswa);
                        echo $data_mahasiswa['total'];
                        ?>
                    </h2>
                    <p>Total Mahasiswa</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom bg-orange p-3">
                    <h2>
                        <?php
                        $query_dosen = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM dosen");
                        $data_dosen = mysqli_fetch_assoc($query_dosen);
                        echo $data_dosen['total'];
                        ?>
                    </h2>
                    <p>Total Dosen</p>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>