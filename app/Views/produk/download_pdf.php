<h1>Data Produk</h1>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Foto</th>
    </tr>

    <?php foreach ($products as $index => $produk) : ?>
        <?php
            $imageSrc = '';
            $imagePath = FCPATH . 'img/' . $produk['foto'];
            $maxFileSize = 2 * 1024 * 1024; // Max 2MB untuk base64 encode di PDF
            
            if ($produk['foto'] && file_exists($imagePath)) {
                $fileSize = filesize($imagePath);
                
                // Hanya encode kalau file <= 2MB
                if ($fileSize <= $maxFileSize) {
                    $imageData = file_get_contents($imagePath);
                    $mimeType = mime_content_type($imagePath) ?: 'image/jpeg';
                    $imageSrc = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }
        ?>
        <tr>
            <td align="center"><?= $index + 1 ?></td>
            <td><?= $produk['nama'] ?></td>
            <td align="right">Rp <?= number_format($produk['harga'], 2, ",", ".") ?></td>
            <td align="center"><?= $produk['jumlah'] ?></td>
            <td align="center">
                <?php if ($imageSrc) : ?>
                    <img src="<?= $imageSrc ?>" width="50">
                <?php else : ?>
                    [Gambar tidak ditampilkan]
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
Downloaded on <?= date("Y-m-d H:i:s") ?>