<div class="row">
    <div class="col-lg-12">
        <input type="hidden" id="id_zona" name="id_zona" value="<?= $result['id_zona']; ?>">
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Zona</span>
                    </div>
                    <input type="text" name="zona" id="zona" class="form-control" value="<?= $result['zona']; ?>" disabled>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Kecamatan</span>
                    </div>
                    <select id="kecamatan" name="kecamatan" class="custom-select" disabled>
                        <?php foreach ($wilayah as $row) { ?>
                            <option value="<?= $row['id_kecamatan']; ?>" <?php if ($result['id_kecamatan'] == $row['id_kecamatan']) { ?> selected <?php } ?>><?= $row['kecamatan']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-fw fa-flag"></i></span>
                    </div>
                    <select class="custom-select" id="status" name="status" disabled>
                        <option value="1" <?php if ($result['flag_active'] == 1) { ?> selected <?php } ?>>Aktif</option>
                        <option value="0" <?php if ($result['flag_active'] == 0) { ?> selected <?php } ?>>Tidak Aktif</option>

                    </select>
                </div>
            </div>
        </div>

        <hr />
        <div class="row">
            <div class="col-lg-5">
                <button id="save" name="save" class="btn btn-success" disabled><i class="fas fa-fw fa-check"></i></button>
                <button id="delete" name="delete" class="btn btn-danger" disabled><i class="fas fa-fw fa-trash-alt"></i></button>
            </div>
            <div class="col-lg-7">
                <label for="chkEdit">
                    <input type="checkbox" name="chkEdit" id="chkEdit">
                    Check untuk edit/hapus data
                </label>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#chkEdit').click(function() {
            if ($(this).is(':checked')) {
                $('#zona').removeAttr('disabled');
                $('#kecamatan').removeAttr('disabled');
                $('#status').removeAttr('disabled');
                $('#save').removeAttr('disabled');
                $('#delete').removeAttr('disabled');
            } else {
                $('#zona').attr('disabled', 'disabled');
                $('#kecamatan').attr('disabled', 'disabled');
                $('#status').attr('disabled', 'disabled');
                $('#save').attr('disabled', 'disabled');
                $('#delete').attr('disabled', 'disabled');
            }

        });

        $('#save').click(function() {
            var id_zona = $('#id_zona').val();
            var zona = $('#zona').val();
            var id_kecamatan = $('#kecamatan').val();
            var status = $('#status').val();

            if (zona == "" || kecamatan == "" || status == "") {
                alert("Masih ada data yang kosong");
            } else {
                if (confirm("Yakin akan simpan data ?")) {
                    $.ajax({
                        url: '<?= base_url('masterData/update') ?>',
                        method: 'post',
                        data: {
                            id_zona: id_zona,
                            zona: zona,
                            id_kecamatan: id_kecamatan,
                            status: status
                        },
                        success: function(data) {
                            if (data == "success") {
                                alert("Success update data zona");
                            } else {
                                alert("Gagal update data zona");
                            }
                            window.location.replace('<?= base_url('masterData/zona') ?>');
                        }
                    });
                }

            }
        });
    });
</script>