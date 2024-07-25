<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mahasiswa";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("tidak bisa terkoneksi ke database: " . mysqli_connect_error());
}

$nim = "";
$nama = "";
$alamat = "";
$prodgi = "";
$success = "";
$error = "";

if (isset($_POST["submit"])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $prodgi = $_POST['prodgi'];

    if ($nim && $nama && $alamat && $prodgi) {
        // Pakai prepared statement buat keamanan
        $stmt = $conn->prepare("INSERT INTO tb_mhs (nim, nama, alamat, prodgi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nim, $nama, $alamat, $prodgi);

        if ($stmt->execute()) {
            $success = "Data sukses masuk";
        } else {
            $error = "Data error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Data tidak terisi";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OHIO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-primary">
        <div class="container">
            <a class="navbar-brand text-white" href="#">OHIO</a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="mx-auto">
            <div class="card">
                <div class="card-header text-white bg-primary">EDIT / ADD</div>
                <div class="card-body">
                    <?php if ($error) { ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                    <?php } ?>
                    <?php if ($success) { ?>
                        <div class="alert alert-primary" role="alert"><?php echo $success; ?></div>
                    <?php } ?>
                    <form action="" method="POST">
                        <div class="mb-3 row">
                            <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nim" id="nim" maxlength="11" value="<?php echo $nim ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="alamat" id="alamat" value="<?php echo $alamat ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="prodgi" class="col-sm-2 col-form-label">Progdi</label>
                            <div class="col-sm-10">
                                <input class="form-control" list="datalistOptions" name="prodgi" id="prodgi" placeholder="Type to search...">
                                <datalist id="datalistOptions">
                                    <option value="sistem_informasi" <?php if ($prodgi == "sistem_informasi") echo "selected"; ?>>Sistem Informasi</option>
                                    <option value="teknik_informatika" <?php if ($prodgi == "teknik_informatika") echo "selected"; ?>>Teknik Informatika</option>
                                    <option value="manajemen_informasi" <?php if ($prodgi == "manajemen_informasi") echo "selected"; ?>>Manajemen Informasi</option>
                                    <option value="akutansi_bisnis" <?php if ($prodgi == "akutansi_bisnis") echo "selected"; ?>>Akutansi Bisnis</option>
                                </datalist>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header text-white bg-primary">table</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">NIM</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Progdi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt2 = $conn->prepare("SELECT * FROM tb_mhs ORDER BY id ASC");
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();

                            if ($result2->num_rows > 0) {
                                while ($row = $result2->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["nim"] . "</td>";
                                    echo "<td>" . $row["nama"] . "</td>";
                                    echo "<td>" . $row["alamat"] . "</td>";
                                    echo "<td>" . $row["prodgi"] . "</td>";
                                    echo "<td>
                        <a href='edit.php?nim=" . $row["nim"] . "' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='delete.php?nim=" . $row["nim"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Hapus</a>
                      </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>0 hasil</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>











                    </table>




                </div>

            </div>
        </div>
    </div>
</body>

</html>