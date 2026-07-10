<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-6">
        <?= form_open('checkout/buy', 'class="row g-3"') ?> <?= form_hidden('username', session()->get('username')) ?>
        <input type="hidden" name="total_harga" id="total_harga" value="">

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'nama',
                'id'       => 'nama',
                'class'    => 'form-control',
                'value'    => session()->get('username'),
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'alamat',
                'id'    => 'alamat',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'ongkir',
                'id'       => 'ongkir',
                'class'    => 'form-control',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?= form_close() ?>
    </div>
    
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Variabel untuk menyimpan total belanja yang sudah dipotong diskon
                $grandTotalCheckout = 0;
                $nominalDiskon = isset($activeDiscount) ? $activeDiscount['nominal'] : 0;

                if (!empty($items)) :
                    foreach ($items as $index => $item) :
                        // Perhitungan diskon per item
                        $hargaAsli = $item['price'];
                        $hargaDiskon = $hargaAsli - $nominalDiskon;
                        if ($hargaDiskon < 0) $hargaDiskon = 0; // Jaga-jaga agar tidak minus

                        $subtotalItem = $hargaDiskon * $item['qty'];
                        $grandTotalCheckout += $subtotalItem;
                ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td>
                                <?php if ($nominalDiskon > 0) : ?>
                                    <del class="text-danger"><?= number_to_currency($hargaAsli, 'IDR') ?></del><br>
                                    <?= number_to_currency($hargaDiskon, 'IDR') ?>
                                <?php else : ?>
                                    <?= number_to_currency($hargaAsli, 'IDR') ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($subtotalItem, 'IDR') ?></td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($grandTotalCheckout, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Total</td>
                    <td><span id="total"><?= number_to_currency($grandTotalCheckout, 'IDR') ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>  
<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        let ongkir = 0;
        
        // PENTING: Mengubah $total bawaan keranjang menjadi $grandTotalCheckout yang sudah didiskon
        let subtotal = <?= isset($grandTotalCheckout) ? $grandTotalCheckout : 0 ?>; 
        
        hitungTotal();

        function hitungTotal() {
            let total = subtotal + ongkir;

            $("#ongkir").val(ongkir);
            $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
            $("#total_harga").val(total);
        }
        
        $('#kelurahan').select2({
            placeholder: 'Cari daerah tujuan',
            minimumInputLength: 3,
            ajax: {
                url: '<?= site_url('ajax/destinations') ?>',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return data;
                },
                cache: true
            }
        });
        
        $("#kelurahan").on('change', function() {
            let id_kelurahan = $(this).val();

            $("#layanan").empty();
            ongkir = 0;
            hitungTotal();

            $.ajax({
                url: "<?= site_url('ajax/costs') ?>",
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function(data) {
                    data.forEach(function(item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                            })
                        );
                    });
                }
            });
        });
        
        $("#layanan").on('change', function() {
            ongkir = parseInt($(this).val());
            hitungTotal();
        });
    });
</script>
<?= $this->endSection() ?>