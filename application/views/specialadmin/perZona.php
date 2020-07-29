<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div style="text-align: center">
        <h1 class="h3 mb-0 text-gray-800"><?= $idKecamatan; ?></h1>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($row['rerata_presure'], 1, ",", "."); ?></div>
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
                    <div class="chart-area">
                        <canvas id="chartPerZona">

                        </canvas>
                    </div>

                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle sbaik"></i> Sangat Baik
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle baik"></i> Baik
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle kurang"></i> Kurang
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle buruk"></i> Buruk
                        </span>
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
                    <?php foreach ($perZona as $row) {
                        $id_zona = $row['id_zona'];
                        $hitungMano = $this->Dashboard_model->jumlahManoZona($id_zona, $periode);
                        $standar = $this->Dashboard_model->getNewPresureZona($periode, $id_zona);
                        $rumus2 = ($standar / $hitungMano['total_manometer_zona']) * 100;
                        $width = $rumus2 . "%";
                        if ($rumus2 >= $rumus['awalSbaik'] && $rumus2 <= $rumus['akhirSbaik']) {
                            $class = 'class="progress-bar bg-success"';
                        } else if ($rumus2 >= $rumus['awalBaik'] && $rumus2 < $rumus['akhirBaik']) {
                            $class = 'class="progress-bar bg-primary"';
                        } else if ($rumus2 < $rumus['akhirKurang'] && $rumus2 >= $rumus['awalKurang']) {
                            $class = 'class="progress-bar bg-warning"';
                        } else {
                            $class = 'class="progress-bar bg-danger"';
                        } ?>
                        <h4 class="small font-weight-bold"><?= $row['zona']; ?><span class="float-right"><?= number_format($rumus2, 1, ",", "."); ?>%</span></h4>
                        <div class="progress mb-4">
                            <div <?= $class; ?> role="progressbar" style="width: <?= $width; ?>" aria-valuenow="<?= $rumus2; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    <?php  } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

        </div>

        <div class="col-lg-6 mb-4">

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
                        $id_zona = $row['id_zona'];
                        $hitungMano = $this->Dashboard_model->jumlahManoZona($id_zona, $periode);
                        $standar = $this->Dashboard_model->getNewPresureZona($periode, $id_zona);
                        $rumus2 = ($standar / $hitungMano['total_manometer_zona']) * 100;
                        echo "'" . $row['zona'] . " " . number_format($rumus2, 1, ",", ".") . "%" . "',";
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
                            $id_zona = $row['id_zona'];
                            $hitungMano = $this->Dashboard_model->jumlahManoZona($id_zona, $periode);
                            $standar = $this->Dashboard_model->getNewPresureZona($periode, $id_zona);
                            $rumus2 = ($standar / $hitungMano['total_manometer_zona']) * 100;
                            echo $rumus2 . ", ";
                            if ($rumus2 >= $rumus['awalSbaik'] && $rumus2 <= $rumus['akhirSbaik']) {
                                $bg[] = "#66ff33";
                            } else if ($rumus2 < $rumus['akhirBaik'] && $rumus2 >= $rumus['awalBaik']) {
                                $bg[] = "#035efc";
                            } else if ($rumus2 < $rumus['akhirKurang'] && $rumus2 >= $rumus['awalKurang']) {
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
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'top'
            }
        }
    });
</script>