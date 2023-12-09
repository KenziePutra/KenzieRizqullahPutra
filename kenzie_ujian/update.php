<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/NPM/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <?php

    //Include file koneksi, untuk koneksikan ke database
    include "koneksi.php";

    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Cek apakah ada nilai yang dikirim menggunakan methos GET dengan NAMA NPM
    if (isset($_GET['NPM'])) {
        $NPM=input($_GET["NPM"]);

        $sql="select * from mahasiswa where NPM=$NPM";
        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_assoc($hasil);


    }

    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $NPM=htmlspecialchars($_POST["NPM"]);
        $NAMA=input($_POST["NAMA"]);


        //Query update data pada tabel anggota
        $sql="update mahasiswa set
			NAMA='$NAMA',
			where NPM=$NPM";

        //Mengeksekusi atau menjalankan query diatas
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hasil) {
            header("Location:index.php");
        }
        else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

        }

    }

    ?>
    <h2>Update Data</h2>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <div class="form-group">
            <label>NAMA:</label>
            <input type="text" name="NAMA" class="form-control" placeholder="Masukan NAMA" required />

        </div>
        <input type="hidden" name="NPM" value="<?php echo $data['NPM']; ?>" />

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>