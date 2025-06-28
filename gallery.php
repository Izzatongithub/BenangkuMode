<?php
// $conn = mysqli_connect("localhost", "root", "", "benangkumode_db");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

require_once 'config/database.php';

// Ambil kategori galeri
$categories = [];
$result = mysqli_query($conn, "SELECT * FROM gallery_categories WHERE is_active = 1 ORDER BY name");
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Ambil filter kategori jika ada
$category_id = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;
if ($category_id) {
    $result = mysqli_query($conn, "SELECT * FROM gallery_images WHERE is_active = 1 AND category_id = $category_id ORDER BY created_at DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM gallery_images WHERE is_active = 1 ORDER BY created_at DESC");
}
$images = [];
while ($row = mysqli_fetch_assoc($result)) {
    $images[] = $row;
}

function getCategoryName($id, $categories) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $id) return $cat['name'];
    }
    return '-';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - BenangkuMode</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container" style="margin-top: 110px;">
        <h1 class="section-title">Galeri</h1>
        <form class="gallery-filter" method="get">
            <select name="kategori" onchange="this.form.submit()">
                <option value="0">Semua Kategori</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category_id==$cat['id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="gallery-grid">
            <?php if(count($images)): foreach($images as $img): ?>
                <div class="gallery-card" onclick="openModal('<?= htmlspecialchars($img['image_path']) ?>', '<?= htmlspecialchars($img['title']) ?>', '<?= getCategoryName($img['category_id'],$categories) ?>')">
                    <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="<?= htmlspecialchars($img['title']) ?>" class="gallery-img">
                    <div class="gallery-info">
                        <div class="gallery-title"><?= htmlspecialchars($img['title']) ?></div>
                        <div class="gallery-category">Kategori: <?= getCategoryName($img['category_id'],$categories) ?></div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="gallery-empty">Belum ada gambar di galeri.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal" id="galleryModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <img src="" alt="" class="modal-img" id="modalImg">
            <div id="modalTitle" class="modal-title"></div>
            <div id="modalCat" class="modal-cat"></div>
        </div>
    </div>
    <script>
    function openModal(src, title, cat) {
        document.getElementById('galleryModal').classList.add('active');
        document.getElementById('modalImg').src = src;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalCat').innerText = 'Kategori: ' + cat;
    }
    function closeModal() {
        document.getElementById('galleryModal').classList.remove('active');
    }
    window.onclick = function(event) {
        var modal = document.getElementById('galleryModal');
        if (event.target == modal) closeModal();
    }
    window.isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/products.js"></script>
</body>
</html> 