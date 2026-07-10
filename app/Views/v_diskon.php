<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Daftar Diskon Toko</h5>
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Diskon Baru
        </button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($discounts as $key => $d) : ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $d['tanggal'] ?></td>
                        <td><?= number_to_currency($d['nominal'], 'IDR') ?></td>
                        <td>
                            <button class="btn btn-success btn-md px-4" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $d['id'] ?>">Edit</button>
                            <a href="<?= base_url('diskon/delete/' . $d['id']) ?>" class="btn btn-danger btn-md" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit<?= $d['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="<?= base_url('diskon/update/' . $d['id']) ?>" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Nominal Diskon</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Tanggal Diskon (Tidak Dapat Diubah)</label>
                                            <input type="date" class="form-control" value="<?= $d['tanggal'] ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label>Nominal Baru (IDR)</label>
                                            <input type="number" name="nominal" class="form-control" value="<?= $d['nominal'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('diskon/store') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nominal Potongan (IDR)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="Contoh: 50000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Diskon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>