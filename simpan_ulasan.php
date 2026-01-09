<?php
/*
 * Script untuk menyimpan ulasan dengan penanganan error yang lebih baik.
 */

// 1. Cek apakah request yang masuk adalah POST
if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    // Ambil data dari form
    $nama = htmlspecialchars($_POST['nama']);
    $rating = (int)$_POST['rating'];
    $komentar = htmlspecialchars($_POST['komentar']);
    $tanggal = date("d M Y, H:i");

    // Buat blok HTML untuk ulasan baru
    $stars = str_repeat("★", $rating) . str_repeat("☆", 5 - $rating);
    
    $htmlUlasan = "
    <div style='background:#fffbeb; padding:15px; border-radius:15px; margin-bottom:15px; border: 1px solid #fde68a; box-shadow: 0 2px 4px rgba(0,0,0,0.05);'>
        <div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;'>
            <strong style='color:#78350f;'>$nama</strong>
            <span style='color:#fbbf24; font-size:1.1rem;'>$stars</span>
        </div>
        <p style='margin:5px 0; color:#4b5563; font-style:italic;'>\"$komentar\"</p>
        <small style='color:#9ca3af; font-size:0.8rem;'>$tanggal</small>
    </div>
    <!-- ULASAN_BARU_DISINI -->"; // Marker ini penting untuk ulasan berikutnya

    $file = 'index.html';
    
    // 2. Cek apakah file index.html bisa ditulis (writable)
    if (is_writable($file)) {
        $content = file_get_contents($file);
        $content = str_replace("<!-- ULASAN_BARU_DISINI -->", $htmlUlasan, $content);
        file_put_contents($file, $content);

        // 3. Redirect kembali ke halaman utama setelah berhasil
        header("Location: index.html?review_success=1");
        exit();
    } else {
        // Jika file tidak bisa ditulis, tampilkan error
        die("Error Kritis: Gagal menyimpan ulasan karena file 'index.html' tidak bisa ditulis (permission denied). Silakan cek izin folder 'warungmakan'.");
    }

} else {
    // Jika ada yang mencoba mengakses file ini langsung (bukan via POST)
    header("HTTP/1.1 405 Method Not Allowed");
    header("Allow: POST");
    echo "<h1>Error 405: Method Not Allowed</h1>";
    echo "<p>Halaman ini tidak bisa diakses secara langsung. Silakan kembali ke <a href='index.html'>halaman utama</a>.</p>";
    exit();
}
?>