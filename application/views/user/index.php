<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="http://125.162.138.4:8008/pdam_srv/photo/employe/<?= $user['foto']; ?>" class="card-img">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?= $user['nama']; ?></h5>
                    <p class="card-tex">Sebagai: <?= $user['username']; ?></p>
                    <p class="card-text"><?= $user['nik']; ?></p>
                    <p class="card-text"><?= $user['level'] . " " . $user['role_id'] ?> </p>
                    <p class="card-text"><?= $user['areakerja']; ?></p>
                    <p class="card-text"><?= $user['tugas']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Manometer -->
    <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#CardHistory" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="CardHistory">
            <h6 class="m-0 font-weight-bold text-primary" data-toggle="tooltip" title=" Klik untuk melihat History Baca">Manometer</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse" id="CardHistory">
            <div class="card-body">
                <div class="col-lg-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr style="text-align: center">
                                <th scope="col">#</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Tanggal Baca</th>
                                <th scope="col">Presure</th>
                                <th scope="col">Masa</th>
                                <th scope="col">Kondisi</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->