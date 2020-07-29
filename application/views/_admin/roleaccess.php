<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <h5>Role: <?= $role['role']; ?></h5>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr style="text-align: center;">
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($menu as $m) : ?>
                        <tr>
                            <th scope="row" style="text-align: center;"><?= $i++; ?></th>
                            <td style="text-align: center;"><?= $m['id']; ?></td>
                            <td><?= $m['menu']; ?></td>
                            <td style="text-align: center;">
                                <div class="form-check">
                                    <!-- data-role : untuk mengirimkan data role_id dan data-menu : untuk menu_id ke jquery -->
                                    <!-- letak JQuery ada di views/template/footer -->
                                    <input class="form-check-input" type="checkbox" id="changerole" <?= check_access($role['id'], $m['id']); ?> data-role="<?= $role['id']; ?>" data-menu="<?= $m['id']; ?>">
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->