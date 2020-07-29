<!-- Footer -->

<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; IT PAM Tirta Sanjiwani <?= date('Y'); ?> </span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Session Modal-->
<div class="modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Session Active</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php
                    echo "
                    <pre>";
                    echo print_r($this->session->userdata()); // or echo print_r($_SESSION);
                    echo "</pre>";
                    ?> </p>

                <p><?= $this->uri->segment(1); ?></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>
<script>
    //menangkap element
    function changerole(roleId, menuId) {

        $.ajax({
            url: "<?= base_url('specialadmin/changeaccess'); ?>",
            type: 'post',
            data: {
                //object data : variable
                menuId: menuId,
                roleId: roleId
            },
            success: function() {
                document.location.href = "<?= base_url('specialadmin/roleaccess/'); ?>" + roleId;
            }
        })
    };

    $('#tableMasterMano tbody').on('click', 'td', function() {
        var $this = $(this);
        var col = $this.index();
        var row = $this.closest('tr').data('id');

        if (row == null) {
            document.location.href = "<?= base_url('/masterManometer/refresh'); ?>";
        } else {
            if (col <= 9) {
                localStorage.setItem('manoEdit', 0);
                document.location.href = "<?= base_url('masterManometer/detail/'); ?>" + row;
            }
        }
    });
    $('#tablePeriode tbody').on('click', 'td', function() {
        var $this = $(this);
        var col = $this.index();
        var row = $this.closest('tr').data('id');
        if (row == null) {
            document.location.href = "<?= base_url('/hasilbaca/refresh'); ?>";
        } else {
            if (col <= 5) {
                document.location.href = "<?= base_url('hasilbaca/periode/'); ?>" + row;
            }
        }
    });

    $('#tableListPeriode tbody').on('click', 'td', function() {
        var $this = $(this);
        var col = $this.index();
        var row = $this.closest('tr').data('id');
        if (row == null) {
            document.location.href = "<?= base_url('/hasilbaca/refresh'); ?>";
        } else {
            if (col <= 5) {
                document.location.href = "<?= base_url('hasilbaca/harian/'); ?>" + row;
                //console.log([col, row].join(',')); 
            }
        }
    });
    $('#manoWilayah').change(function() {
        var wilayah = $(this).val();
        $.ajax({
            url: "<?= base_url('masterManometer/get_filterZona'); ?>",
            type: 'post',
            data: {
                wilayah: wilayah
            },
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value=""> Pilih Zona </option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value="' + data[i].zona + '">' + data[i].zona + '</option>';
                }
                $('#manoZona').html(html);
            }
        });
        $.ajax({
            url: "<?= base_url('masterManometer/get_filterPembaca'); ?>",
            type: 'post',
            data: {
                wilayah: wilayah
            },
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value=""> Pilih Pembaca </option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value="' + data[i].nama + '">' + data[i].nama + '</option>';
                }
                $('#operator').html(html);
            }
        });
        $.ajax({
            url: "<?= base_url('masterManometer/get_IDWilayah'); ?>",
            type: 'post',
            data: {
                wilayah: wilayah
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("id_kecamatan").value = data.indekareakerja;
                document.getElementById("id_zona").value = "";
            }
        });
    });
    $('#manoZona').change(function() {
        var wilayah = $('#manoWilayah').val();
        var zona = $(this).val();
        $.ajax({
            url: "<?= base_url('masterManometer/get_kodeZona'); ?>",
            type: 'post',
            data: {
                zona: zona,
                wilayah: wilayah
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("kodeZona").value = data.total_manozona + 1;
            }
        });
        $.ajax({
            url: "<?= base_url('masterManometer/get_pembacaZona'); ?>",
            type: 'post',
            data: {
                zona: zona,
                wilayah: wilayah
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("nikOperator").value = data.pembacaZona.nik;
                setSelectValue('operator', data.pembacaZona.operator);
            }
        });
        $.ajax({
            url: "<?= base_url('masterManometer/get_IDZona'); ?>",
            type: 'post',
            data: {
                zona: zona
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("id_zona").value = data.id_zona;
            }
        });
    });
    $('#operator').change(function() {
        var operator = $(this).val();
        $.ajax({
            url: "<?= base_url('masterManometer/getNikOperator'); ?>",
            type: 'post',
            data: {
                nama: operator
            },
            dataType: 'json',
            success: function(data) {
                document.getElementById("nikOperator").value = data[0].nik;
            }
        });
    });

    function editManometer(id) {
        localStorage.setItem('manoEdit', 1);
        document.location.href = "<?= base_url('masterManometer/detail/'); ?>" + id;
    }

    function setSelectValue(id, val) {
        document.getElementById(id).value = val;
    }

    function deleteManometer(id) {
        var baseURL = "<?= base_url('/masterManometer/delete/'); ?>";
        $.ajax({
            url: "<?= base_url('masterManometer/getManometerById'); ?>",
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                var Text = '';
                var Confirm = '';
                Text += '<h6>Apakah Anda yakin menghapus <b> ' + data.manometer + ' </b> ?</h6>';
                Text += '<h6>Data yang sudah dihapus <b>tidak dapat dikembalikan lagi!</b></h6>';
                Confirm += '<button type="button" class="btn btn-info" data-dismiss="modal">Tidak</button>' + '<a href="' + baseURL + id + '" class="btn btn-danger">Hapus</a>'
                $('#deleteManometerText').html(Text);
                $('#deleteManometerConfirm').html(Confirm);
            }
        });
    }
</script>
</body>

</html>