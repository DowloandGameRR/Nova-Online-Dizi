<?php
$dataFile = 'data.json';
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}
$items = json_decode(file_get_contents($dataFile), true) ?? [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nova Online - Film ve Dizi Platformu</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <header>
    <div class="container">
      <h1>Nova Online</h1>
      <nav>
        <a href="admin.php">Admin Paneli</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <section class="hero">
      <h2>En Yeni ve Popüler Film & Diziler</h2>
      <p>Nova Online'da yüzlerce içerik seni bekliyor!</p>
    </section>

    <section class="grid">
      <?php if (empty($items)): ?>
        <p class="empty">Henüz içerik eklenmemiş. Admin panelinden ekleyebilirsin.</p>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <div class="card">
            <div class="card-img-placeholder">
              <?php if (!empty($item['poster'])): ?>
                <img src="<?= htmlspecialchars($item['poster']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
              <?php else: ?>
                <div class="no-poster"><i class="fas fa-film"></i></div>
              <?php endif; ?>
            </div>
            <div class="card-info">
              <h3><?= htmlspecialchars($item['title']) ?></h3>
              <div class="meta">
                <span><i class="fas fa-<?= $item['type'] === 'Film' ? 'clapperboard' : 'tv' ?>"></i> <?= $item['type'] ?></span>
                <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($item['year'] ?? '—') ?></span>
              </div>
              <p class="desc"><?= nl2br(htmlspecialchars(substr($item['description'] ?? '', 0, 120))) ?>...</p>
              <?php if (!empty($item['link'])): ?>
                <a href="<?= htmlspecialchars($item['link']) ?>" target="_blank" class="btn">İzlemeye Git →</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>

  <footer>
    <div class="container">
      <p>&copy; <?= date('Y') ?> Nova Online. Tüm hakları saklıdır.</p>
    </div>
  </footer>
</body>
</html>
