<?php
session_start();

$ADMIN_KEY = '16153015admin';          // Şifren (buradan değiştirirsin)
$dataFile = 'data.json';

// Şifre kontrolü
if (isset($_POST['admin_key'])) {
    if ($_POST['admin_key'] === $ADMIN_KEY) {
        $_SESSION['nova_admin'] = true;
    } else {
        $error = "Yanlış şifre!";
    }
}

// Çıkış
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Giriş kontrolü
if (!isset($_SESSION['nova_admin']) || $_SESSION['nova_admin'] !== true):
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nova Online - Admin Giriş</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
  <div class="login-container">
    <h1>Nova Online Admin</h1>
    <?php if (isset($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <form method="post">
      <input type="password" name="admin_key" placeholder="Şifreni gir..." required autofocus>
      <button type="submit">Giriş Yap</button>
    </form>
  </div>
</body>
</html>
<?php exit; endif;

// Giriş yapıldı → Yönetim
$items = json_decode(file_get_contents($dataFile), true) ?? [];

// Ekleme
if (isset($_POST['add'])) {
    $new = [
        'title'       => trim($_POST['title'] ?? ''),
        'type'        => $_POST['type'] ?? 'Film',
        'year'        => trim($_POST['year'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'link'        => trim($_POST['link'] ?? ''),
        'poster'      => trim($_POST['poster'] ?? '')   // Poster URL'si (isteğe bağlı)
    ];
    if (!empty($new['title'])) {
        $items[] = $new;
        file_put_contents($dataFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header("Location: admin.php");
        exit;
    }
}

// Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (isset($items[$id])) {
        array_splice($items, $id, 1);
        file_put_contents($dataFile, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nova Online - Yönetim Paneli</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <header>
    <div class="container">
      <h1>Nova Online <small>Yönetim Paneli</small></h1>
      <nav>
        <a href="?logout=1">Çıkış Yap</a> | 
        <a href="index.php" target="_blank">Siteyi Görüntüle</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <section class="admin-form">
      <h2>Yeni İçerik Ekle</h2>
      <form method="post">
        <input type="text" name="title" placeholder="Başlık *" required>
        <select name="type">
          <option value="Film">Film</option>
          <option value="Dizi">Dizi</option>
        </select>
        <input type="text" name="year" placeholder="Yıl (örn: 2025)">
        <textarea name="description" placeholder="Açıklama / Konu" rows="4"></textarea>
        <input type="url" name="link" placeholder="İzleme linki (Netflix, Youtube vb.)">
        <input type="url" name="poster" placeholder="Poster URL (isteğe bağlı)">
        <button type="submit" name="add"><i class="fas fa-plus"></i> Ekle</button>
      </form>
    </section>

    <section>
      <h2>Mevcut İçerikler (<?= count($items) ?> adet)</h2>
      <?php if (empty($items)): ?>
        <p>Henüz içerik yok.</p>
      <?php else: ?>
        <div class="admin-list">
          <?php foreach ($items as $idx => $item): ?>
            <div class="admin-card">
              <div>
                <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                <small><?= $item['type'] ?> • <?= $item['year'] ?? '—' ?></small>
              </div>
              <a href="?delete=<?= $idx ?>" class="delete-btn" onclick="return confirm('Silmek istediğine emin misin?')">
                <i class="fas fa-trash"></i> Sil
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
