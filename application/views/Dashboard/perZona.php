<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    </div>
    <small class="text-mute">Total Zona Terbaca :<?= $total_zona; ?></small>
    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <?php foreach ($perZona as $row) {  ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <a href="<?= base_url('Dashboard/getPerDma/' . $row['id_zona']) ?>">
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
                            if ($row['rumus'] >= 80) {
                                $bg[] = "#66ff33";
                            } else if ($row['rumus'] < 80 && $row['rumus'] >= 50) {
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
</script>