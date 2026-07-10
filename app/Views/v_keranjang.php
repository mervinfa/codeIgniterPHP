<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
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
<?php echo form_open('keranjang/edit') ?>
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th> 
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $grandTotal = 0; // Variabel baru untuk menghitung total keseluruhan
        
        // Ambil nominal diskon dari controller (jika ada)
        $nominalDiskon = isset($activeDiscount) ? $activeDiscount['nominal'] : 0;

        if (!empty($items)) :
            foreach ($items as $index => $item) :
                $hargaAsli = $item['price'];
                $hargaDiskon = $hargaAsli - $nominalDiskon;
                
                // Jaga-jaga agar harga tidak minus jika diskon lebih besar dari harga barang
                if ($hargaDiskon < 0) {
                    $hargaDiskon = 0;
                }
                
                // Subtotal per item menggunakan harga yang sudah didiskon
                $subtotalItem = $hargaDiskon * $item['qty'];
                
                // Tambahkan ke Total Keseluruhan
                $grandTotal += $subtotalItem;
        ?>
                <tr>
                    <td><?php echo $item['name'] ?></td>
                    <td><img src="<?php echo base_url() . "img/" . $item['options']['foto'] ?>" width="100px"></td>
                    <td>
                        <?php if ($nominalDiskon > 0) : ?>
                            <del class="text-danger"><?php echo number_to_currency($hargaAsli, 'IDR') ?></del><br>
                            <?php echo number_to_currency($hargaDiskon, 'IDR') ?>
                        <?php else : ?>
                            <?php echo number_to_currency($hargaAsli, 'IDR') ?>
                        <?php endif; ?>
                    </td> 
                    <td><input type="number" min="1" name="qty<?php echo $i++ ?>" class="form-control" value="<?php echo $item['qty'] ?>"></td>
                    <td><?php echo number_to_currency($subtotalItem, 'IDR') ?></td>
                    <td>
                        <a href="<?php echo base_url('keranjang/delete/' . $item['rowid'] . '') ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
        <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table> 

<div class="alert alert-info">
    <?php echo "Total = " . number_to_currency($grandTotal, 'IDR') ?>
</div>

<button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
<a class="btn btn-warning" href="<?php echo base_url() ?>keranjang/clear">Kosongkan Keranjang</a>
<?php if (!empty($items)) : ?>
    <a class="btn btn-success" href="<?php echo base_url() ?>checkout">Selesai Belanja</a>
<?php endif; ?>
<?php echo form_close() ?>
<?= $this->endSection() ?>