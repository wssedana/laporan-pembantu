<!-- Begin Page Content -->
<div class="container-fluid">
    <div style="text-align: center">
        <h1 class="h3 mb-0 text-gray-800"><?= $idKecamatan; ?></h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <form action="<?= base_url('Admin/dash3'); ?>" method="POST" autocomplete="off">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="periode" id="periode" placeholder="Pilih Periode . . ." autocomplete="off">
                        <div class="input-group-append">
                            <input type="submit" name="submit" class="btn btn-success">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <small class="text-mute">Total Zona Terbaca :<?= $total_zona; ?></small>
    <small class="text-mute"> (<?= $periodeBaca; ?>)</small>
    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <?php foreach ($perZona as $row) {  ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <a href="<?= base_url('Admin/getPresurePerMasa/' . $periode . '/' . $row['id_zona']); ?>">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Zona <?= $row['zona']; ?></div>
                                </a>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $row['rerata_presure']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tachometer-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?= $this->pagination->create_links(); ?>
        </div>
        <div class="col-md-4"></div>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Area Chart presure air perzona -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Presentase Presure Air / Zona</h6>

                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="chart-area">
                                <canvas id="chartPerZona">

                                </canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Bagus
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-warning"></i> Cukup
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-danger"></i> Buruk
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--Akhir Area Chart presure air perzona -->

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Direct
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Social
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Referral
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rating</h6>
                </div>
                <div class="card-body">
                    <?php foreach ($perZona as $row) {
                        $width = $row['rumus'] . "%";
                        if ($row['rumus'] >= $rumus['awalSbaik'] && $row['rumus'] <= $rumus['akhirSbaik']) {
                            $class = 'class="progress-bar bg-success"';
                        } else if ($row['rumus'] < $rumus['akhirKurang'] && $row['rumus'] >= $rumus['awalKurang']) {
                            $class = 'class="progress-bar bg-warning"';
                        } else {
                            $class = 'class="progress-bar bg-danger"';
                        } ?>
                        <h4 class="small font-weight-bold"><?= $row['zona']; ?><span class="float-right"><?= number_format($row['rumus'], 1, ",", "."); ?>%</span></h4>
                        <div class="progress mb-4">
                            <div <?= $class; ?> role="progressbar" style="width: <?= $width; ?>" aria-valuenow="<?= $row['rumus']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    <?php  } ?>
                </div>
            </div>

            <!-- Color System -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body">
                            Primary
                            <div class="text-white-50 small">#4e73df</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Success
                            <div class="text-white-50 small">#1cc88a</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body">
                            Info
                            <div class="text-white-50 small">#36b9cc</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card bg-warning text-white shadow">
                        <div class="card-body">
                            Warning
                            <div class="text-white-50 small">#f6c23e</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body">
                            Danger
                            <div class="text-white-50 small">#e74a3b</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card bg-secondary text-white shadow">
                        <div class="card-body">
                            Secondary
                            <div class="text-white-50 small">#858796</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6 mb-4">

            <!-- Approach -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Periode Baca Manometer</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover">
                        <thead>
                            <tr style="text-align: center">
                                <th scope="col">#</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Create Time</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($listPeriode as $lsPeriode) : ?>
                                <tr style="text-align: center">
                                    <th scope="row"><?= $i++; ?></th>
                                    <td><?= $lsPeriode['table_name']; ?></td>
                                    <td><?= $lsPeriode['create_time']; ?></td>
                                    <td>
                                        <a href="" class="badge badge-success">edit</a>
                                        <a href="" class="badge badge-danger">delete</a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Illustrations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
                    </div>
                    <p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that
                        you can use completely free and without attribution!</p>
                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script type="text/javascript">
    var ctx = document.getElementById('chartPerZona').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php
                if (count($perZonaNoLimit) > 0) {
                    foreach ($perZonaNoLimit as $row) {
                        echo "'" . $row['zona'] . "',";
                    }
                }
                ?>
            ],
            datasets: [{
                label: 'Ranting',
                data: [
                    <?php
                    if (count($perZonaNoLimit) > 0) {
                        foreach ($perZonaNoLimit as $row) {
                            echo $row['rumus'] . ", ";
                            if ($row['rumus'] >= $rumus['awalSbaik'] && $row['rumus'] <= $rumus['akhirSbaik']) {
                                $bg[] = "#66ff33";
                            } else if ($row['rumus'] < $rumus['akhirKurang'] && $row['rumus'] >= $rumus['awalKurang']) {
                                $bg[] = "#ffff00";
                            } else {
                                $bg[] = "#ff0000";
                            }
                        }
                    }
                    ?>
                ],
                backgroundColor: [
                    <?php
                    for ($i = 0; $i < count($perZonaNoLimit); $i++) {
                        echo "'" . $bg[$i] . "',";
                    }

                    ?>
                ]


            }]
        },
    });

    $(function() {
        $("#periode").datepicker({
            format: 'yyyymm',
            viewMode: "months",
            minViewMode: "months",
            autoClose: true
        });
    });
</script>