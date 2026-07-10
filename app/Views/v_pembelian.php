<?php helper('number'); ?> <?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="pagetitle">
        <h1>Pembelian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                <li class="breadcrumb-item active">Pembelian</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">History Transaksi Pembelian</h5>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Pembelian</th>
                                <th>Pembeli</th>
                                <th>Waktu Pembelian</th>
                                <th>Total Bayar</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($transaksi as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['id'] ?></td>
                                <td><?= esc($row['username']) ?></td>
                                <td><?= esc($row['created_at']) ?></td> 
                                <td><?= number_to_currency($row['total_harga'], 'IDR', 'id_ID', 0) ?></td>
                                <td><?= esc($row['alamat']) ?></td>
                                <td>
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge bg-warning text-dark">Belum Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Sudah Selesai</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success">Detail</button>
                                    
                                    <form action="<?= base_url('pembelian/ubah-status/' . $row['id']) ?>" method="post" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-info text-white">Ubah Status</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>