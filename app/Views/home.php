<?php
/**
 * @var array $products
 */
?>
<?= $this->extend('layout')?>
<?= $this->section('content')?>

<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>

<!-- Table with stripped rows -->
<div class="row">
        <?php 
            $active_discount = get_active_discount();
        ?>
        <?php foreach ($products ?? [] as $key => $item) : ?>         
            <?php 
                $harga_asli = $item['harga'];
                $harga_diskon = $harga_asli - $active_discount;
                if ($harga_diskon < 0) $harga_diskon = 0; // Prevent negative price
            ?>
            <div class="col-lg-6">

                <?= form_open('keranjang') ?>
                <?= form_hidden([
                    'id'    => (string)$item['id'],
                    'nama'  => $item['nama'],
                    'harga' => (string)$harga_asli,
                    'foto'  => $item['foto']]) ?>

                <div class="card">
                    <div class="card-body">
                        <img src="<?= base_url() . "img/" . $item['foto'] ?>" alt="..." width="50%">
                        <h5 class="card-title">
                            <?= $item['nama'] ?><br>
                            <?php if ($active_discount > 0): ?>
                                <span class="text-danger" style="text-decoration: line-through;">
                                    <small><?= number_to_currency($harga_asli, 'IDR') ?></small>
                                </span>
                                <span class="text-success ms-2">
                                    <?= number_to_currency($harga_diskon, 'IDR') ?>
                                </span>
                            <?php else: ?>
                                <?= number_to_currency($harga_asli, 'IDR') ?>
                            <?php endif; ?>
                        </h5>
                        <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                    </div>
                </div>
                <?= form_close() ?>
            </div> 
        <?php endforeach ?> 
</div>
<!-- End Table with stripped rows -->
<?= $this->endSection() ?>