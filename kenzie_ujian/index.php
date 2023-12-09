<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "kenzie_ujian";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$NPM          = "";
$NAMA       = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql  = "delete from mahasiswa where NPM= '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='index.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from mahasiswa where NPM= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $NPM= $r['NPM'];
    $NAMA = $r['NAMA'];
}

if (isset($_POST['simpan'])) {
    $NPM  = $_POST['NPM'];
    $NAMA = $_POST['NAMA'];

    // Check if ID mahasiswa already exists for updating
    $check_sql = "SELECT * FROM mahasiswa WHERE NPM= '$NPM'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID mahasiswa exists, proceed with the UPDATE
        $update_sql = "UPDATE mahasiswa SET NAMA = '$NAMA' WHERE NPM= '$NPM'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data mahasiswa Berhasil Diedit";
        } else {
            $error = "Data mahasiswa Gagal Diedit";
        }
    } else {
        // ID mahasiswa doesn't exist, insert new data
        $insert_sql = "INSERT INTO mahasiswa (NPM, NAMA) VALUES ('$NPM', '$NAMA')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data mahasiswa Berhasil Disimpan";
        } else {
            $error = "Data mahasiswa Gagal Disimpan";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 950px
        }

        .card {
            margin-top: 20px
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="index.php" method="POST">
                    <div class="mb-3 row">
                        <label for="NPM" class="col-sm-2 col-form-label">NPM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NPM" name="NPM" value="<?php echo $NPM?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="NAMA" class="col-sm-2 col-form-label">NAMA</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NAMA" name="NAMA" value="<?php echo $NAMA ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NPM</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM mahasiswa ORDER BY NPM ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $NPM  = $r2['NPM'];
                            $NAMA = $r2['NAMA'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $NPM?></td>
                                <td scope="row"><?php echo $NAMA ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $NPM?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $NPM?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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
</body>

</html>