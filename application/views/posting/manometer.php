<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalPostingMano">
                Posting
            </button>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover" id="manometer-posting">
                <thead>
                    <th>Manometer Periode</th>
                    <th>Di Posting Oleh</th>
                    <th>Tanggal Posting</th>
                    <th>Jumlah Manometer</th>
                </thead>
                <tbody style="cursor: pointer;">
                    <?php foreach ($list_manometer_posting as $row) { ?>
                        <tr data-nama="<?= $row['nama'];?>" data-nama_table="<?= $this->libfunction->format_periode2($row['table_name']); ?>" data-create_time="<?= $row['create_time']; ?>" data-jumlah_mano="<?= $this->posting_model->jumlahManoPosting($row['table_name']); ?>">
                            <td><?= $this->libfunction->format_periode2($row['table_name']); ?></td>
                            <td><?= $row['nama'];?></td>
                            <td><?= $row['create_time'];?></td>
                            <td><?= $jumlahMano = $this->posting_model->jumlahManoPosting($row['table_name'])." Manometer";?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>


<div class="modal fade" id="modalDetailPostingMano" role="dialog" aria-labelledby="mySmallModal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Manometer Posting</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success">
                                <li class="fas fa-fw fa-calendar-alt" style="color: white;"></li>
                                </span>
                            </div>
                            <input type="text" name="detailPostingMano" id="detailPostingMano" class="form-control" style="cursor: pointer;" readonly>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success">
                                <li class="fas fa-fw fa-user-alt" style="color: white;"></li>
                                </span>
                            </div>
                            <input type="text" name="creator" id="creator" class="form-control" style="cursor: pointer;" readonly>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success">
                                <li class="fas fa-fw fa-calendar-alt" style="color: white;"></li>
                                </span>
                            </div>
                            <input type="text" name="tgl-posting" id="tgl-posting" class="form-control" style="cursor: pointer;" readonly>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success">
                                   <li class="fas fa-fw fa-tachometer-alt" style="color: white;"></li>
                                </span>
                            </div>
                            <input type="text" name="jumlah-manometer" id="jumlah-manometer" class="form-control" style="cursor: pointer;" readonly>
                        </div>
                    </div>
                </div>
                <hr/>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPostingMano" role="dialog" aria-labelledby="mySmallModal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Manometer Posting</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6 style="text-align: center; color:red;" class="alert alert-warning"><strong>PROSES POSTING DILAKUKAN SETIAP AKHIR BULAN DI SETIAP PERIODE</strong></h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <li class="fas fa-fw fa-calendar-alt"></li>
                                </span>
                            </div>
                            <input type="text" name="periodePostingMano" id="periodePostingMano" class="form-control" style="cursor: pointer;">
                            <div class="input-group-append">
                                <button class="btn btn-success" name="postingMano" id="postingMano">Posting</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#manometer-posting tbody tr').click(function() {
            var nama_table = $(this).data('nama_table');
            var tgl_posting = $(this).data('create_time');
            var jumlah_mano = $(this).data('jumlah_mano');
            var nama = $(this).data('nama');
            $('#detailPostingMano').val(nama_table);
            $('#creator').val(nama);
            $('#tgl-posting').val(tgl_posting);
            $('#jumlah-manometer').val(jumlah_mano + " Manometer");
            $('#modalDetailPostingMano').modal('show');
        });
        $("#periodePostingMano").datepicker({
            format: 'yyyymm',
            viewMode: "months",
            minViewMode: "months",
            autoClose: true
        });

        $('#postingMano').click(function() {
            var periodePostingMano = $('#periodePostingMano').val();
            if (periodePostingMano == "") {
                alert("Anda Belum Memilih Periode Yang Akan Diposting !!!");
            } else {
                $.ajax({
                    url: '<?= base_url('posting/prosesPostingMano') ?>',
                    method: 'post',
                    data: {
                        periodePostingMano: periodePostingMano
                    },
                    success: function(data) {
                        alert(data);
                        window.location.replace("<?= base_url('posting/manometer') ?>");
                    }
                })
            }
        });
    });
</script>