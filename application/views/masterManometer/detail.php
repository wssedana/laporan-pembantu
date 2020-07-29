 <!-- Begin Page Content -->
 <div class="container-fluid">

     <div class="row">

         <div class="col-lg-8">

             <!-- Manometer -->
             <div class="card shadow mb-4">
                 <div class="card-header py-3">
                     <h6 class="m-0 font-weight-bold text-primary">Data</h6>
                 </div>
                 <div class="card-body">
                     <form action="<?= base_url('masterManometer/update') ?>" method="POST" enctype="multipart/form-data">
                         <div class="mb-20"><?= $this->session->flashdata('message') ?></div>
                         <div class="row">
                             <div class="col-lg-8">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Nama</span>
                                     </div>
                                     <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Manometer" aria-label="nama" aria-describedby="basic-addon1" value="<?= $manometer['manometer']; ?>" disabled>
                                 </div>
                             </div>
                             <div class="col-lg-4">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <label class="input-group-text" for="flag_active">Status</label>
                                     </div>
                                     <select class="custom-select" id="flag_active" name="flag_active" disabled>
                                         <?php if ($manometer['flag_active'] == 1) { ?>
                                             <option value="1" selected>Aktif</option>
                                             <option value="0">Tidak Aktif</option>
                                         <?php  } else { ?>
                                             <option value="1">Aktif</option>
                                             <option value="0" selected>Tidak Aktif</option>
                                         <?php } ?>
                                     </select>
                                 </div>
                             </div>

                             <div class="col-lg-3">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">ID</span>
                                     </div>
                                     <input type="text" class="form-control" placeholder="ID Manometer" aria-label="basic-addon1" aria-describedby="basic-addon1" value="<?= $manometer['id_manometer']; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-3">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Kode</span>
                                     </div>
                                     <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Manometer" aria-label="kode" aria-describedby="basic-addon1" value="<?= $manometer['kode_manometer']; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-4">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">GIS</span>
                                     </div>
                                     <input type="text" class="form-control" id="gis" name="gis" placeholder="Kode GIS" aria-label="gis" aria-describedby="basic-addon1" value="<?= $manometer['kode_gis']; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-2">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Ø</span>
                                     </div>
                                     <input type="text" class="form-control" id="diameter" name="diameter" placeholder="Diameter" aria-label="diameter" aria-describedby="basic-addon1" value="<?= $manometer['diameter']; ?> '' " disabled>
                                 </div>
                             </div>
                         </div>

                         <h6 class="mt-3 mb-3">Lokasi</h6>
                         <div class="row">
                             <div class="col-lg-4">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Kecamatan</span>
                                     </div>
                                     <input type="text" class="form-control" id="kecamatan" name="kecamatan" placeholder="Kecamatan" aria-label="kecamatan" aria-describedby="basic-addon1" value="<?= $manometer['kecamatan']; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-4">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Zona</span>
                                     </div>
                                     <input type="text" class="form-control" id="zona" name="zona" placeholder="Zona" aria-label="zona" aria-describedby="basic-addon1" value="<?= $manometer['zona']; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-4">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">DMA</span>
                                     </div>
                                     <input type="text" class="form-control" id="dma" name="dma" placeholder="Lokasi DMA" aria-label="dma" aria-describedby="basic-addon1" value="<?= $manometer['nama_dma']; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-3">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Latitude</span>
                                     </div>
                                     <input type="text" class="form-control" id="latitude" name="latitude" placeholder="latitude" aria-label="latitude" aria-describedby="basic-addon1" value="<?= $manometer['latitude']; ?>" onfocus="this.value='<?= $manometer['latitude']; ?>'" onkeyup="this.value='<?= $manometer['latitude']; ?>'" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-3">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Longitude</span>
                                     </div>
                                     <input type="text" class="form-control" id="longitude" name="longitude" placeholder="longitude" aria-label="longitude" aria-describedby="basic-addon1" value="<?= $manometer['longitude']; ?>" onfocus="this.value='<?= $manometer['longitude']; ?>'" onkeyup="this.value='<?= $manometer['longitude']; ?>'" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-3">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Altitude</span>
                                     </div>
                                     <input type="text" class="form-control" id="altitude" name="altitude" placeholder="altitude" aria-label="altitude" aria-describedby="basic-addon1" value="<?= $manometer['altitude']; ?>" onfocus="this.value='<?= $manometer['altitude']; ?>'" onkeyup="this.value='<?= $manometer['altitude']; ?>'" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-3">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Akurasi</span>
                                     </div>
                                     <input type="text" class="form-control" id="akurasi" name="akurasi" aria-label="akurasi" aria-describedby="basic-addon1" value="<?= $manometer['accuracy']; ?>" disabled>
                                 </div>
                             </div>
                         </div>

                         <h6 class="mt-3 mb-3">Petugas Baca</h6>
                         <div class="row">
                             <div class="col-lg-4">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">NIK</span>
                                     </div>
                                     <input type="text" class="form-control" id="nikOperator" name="nikOperator" placeholder="NIK" aria-label="nikOperator" aria-describedby="basic-addon1" value="<?= $manometer['nik']; ?>" disabled>
                                 </div>
                             </div>

                             <div class=" col-lg-8">
                                 <div class="input-group mb-3">
                                     <div class="input-group-prepend">
                                         <label class="input-group-text" for="operator">Nama</label>
                                     </div>
                                     <select class="custom-select" id="operator" name="operator" disabled>
                                         <?php foreach ($pembaca as $p) : ?>
                                             <option value="<?= $p['nama']; ?>" <?php if ($p['nama'] == $manometer['operator']) { ?> selected <?php } ?>><?= $p['nama']; ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>
                             </div>
                         </div>

                         <div class="row">
                             <div>
                                 <input type="text" class="form-control" id="id_manometer" name="id_manometer" value="<?= $manometer['id_manometer']; ?>" style="height: 1px;;visibility:hidden;">
                             </div>
                             <div>
                                 <input type="text" class="form-control" id="nik_operator" name="nik_operator" value="<?= $manometer['nik']; ?>" style="height: 1px;visibility:hidden;">
                             </div>
                         </div>

                         <!-- Button -->
                         <div class="row">
                             <div class="col-lg-1 text-left">
                                 <a href="<?= base_url('/masterManometer') ?>" onclick="disableEdit()" class="btn btn-secondary mb-2 mt-0" data-toggle="tooltip" title="Kembali"><i class="fas fa-fw fa-caret-left text-white"></i></a>
                             </div>
                             <div class="col-lg-6 mt-0 pt-3">
                                 <i class="text text-muted">Update terakhir pada <?= format_tanggal($manometer['change_date']); ?></i>
                             </div>
                             <?php if ($this->session->userdata('role_id') != 4) { ?>
                                 <div class="col-lg text-right">
                                     <a>
                                         <button style="visibility: hidden" class="btn btn-success mb-2 mt-0" onclick="disableEdit()" id="btn_save" data-toggle="tooltip" title="Simpan Perubahan" disabled><i class="fas fa-fw fa-save"></i></button>
                                     </a>
                                     <a class="btn btn-info mb-2 mt-0 disabled" id="btn_cancel" href="javascript:void(0)" data-toggle="tooltip" title="Batal Edit" onclick="cancel(<?= $manometer['id_manometer']; ?>)"><i class="fas fa-fw fa-ban text-white"></i></a>
                                     <a onclick="enableEdit()">
                                         <button class="btn btn-info mb-2 mt-0" id="btn_edit" data-toggle="tooltip" title="Edit data"><i class="fas fa-fw fa-edit"></i></button>
                                     </a>
                                     <a class="btn btn-danger mb-2 mt-0 ml-2 text-white" data-toggle="modal" href="#deleteModal" id="btn_delete" data-toggle="tooltip" title="Hapus data">
                                         <i class="fas fa-fw fa-trash-alt"></i>
                                     </a>
                                 </div>
                             <?php }; ?>

                         </div>
                 </div>
                 <!-- end card body -->
             </div>

             <!-- Activity Manometer -->
             <div class="card shadow mb-4">
                 <!-- Card Header - Accordion -->
                 <a href="#CardHistory" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="CardHistory">
                     <h6 class="m-0 font-weight-bold text-primary" data-toggle="tooltip" title=" Klik untuk melihat History Baca">History Baca <?= $manometer['manometer']; ?></h6>
                 </a>
                 <!-- Card Content - Collapse -->
                 <div class="collapse" id="CardHistory">
                     <div class="card-body">
                         <div class="col-lg-5">
                             <div class="input-group mb-3">
                                 <div class="input-group-prepend">
                                     <label class="input-group-text" for="tahun">Tahun</label>
                                 </div>
                                 <select class="custom-select" id="tahun" name="tahun">
                                     <?php foreach ($tahunPeriode as $thPeriode) :
                                            $periode = $thPeriode['table_name'];
                                            $tahun = substr($periode, 7, 4);
                                        ?>
                                         <option value="<?= $tahun; ?>"><?= $tahun; ?></option>
                                     <?php endforeach; ?>
                                 </select>
                                 <div class="input-group-append">
                                     <button class="btn btn-outline-secondary" type="button">Tampil</button>
                                 </div>
                             </div>
                         </div>
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
                                     <?php
                                        $i = 1;
                                        foreach ($listPeriode as $lsPeriode) :

                                            $sql = "SELECT a.*,b.manometer,c.kecamatan,d.zona,e.nama_dma FROM $lsPeriode[table_name] a 
                                                LEFT JOIN m_manometer b ON a.id_manometer=b.id_manometer
                                                LEFT JOIN m_kecamatan c ON b.id_kecamatan=c.id_kecamatan 
                                                LEFT JOIN m_zona d ON b.id_zona=d.id_zona
                                                LEFT JOIN m_dma e ON b.id_dma=e.id_dma
                                                WHERE a.id_manometer ='$manometer[id_manometer]'
                                                ORDER BY a.tgl_baca ASC";

                                            $bacaan = $this->db->query($sql)->result_array();
                                        ?>
                                         <?php
                                            foreach ($bacaan as $b) : ?>
                                             <tr>
                                                 <td scope="row"><?= $i++; ?></td>
                                                 <td><?= format_periode($lsPeriode['table_name']);  ?></td>
                                                 <td><?= format_tanggal($b['tgl_baca']); ?></td>
                                                 <td><?= $b['presure']; ?> <small><i>bar</i></small></td>
                                                 <td>
                                                     <?= $b['masa'] . " "; ?>
                                                     <?php if ($b['status_masa'] == "mundur") { ?>
                                                         <text class="text text-danger"><?= $b['status_masa']; ?></text>
                                                     <?php } elseif ($b['status_masa'] == "maju") { ?>
                                                         <text class="text text-warning"><?= $b['status_masa']; ?></text>
                                                     <?php } else { ?>
                                                         <text class="text text-success"><?= $b['status_masa']; ?></text>
                                                     <?php } ?>
                                                 </td>
                                                 <td><?= $b['kondisi_baca']; ?></td>
                                                 <td><?= $b['status_baca']; ?></td>
                                             </tr>
                                     <?php endforeach;
                                        endforeach; ?>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>

         </div>

         <!-- right col -->
         <div class="col-lg-4">
             <!-- Collapsable Card Example -->
             <div class="card shadow mb-4">
                 <!-- Card Header - Accordion -->
                 <a href="#CardFoto" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="CardFoto">
                     <h6 class="m-0 font-weight-bold text-primary">Foto</h6>
                 </a>
                 <!-- Card Content - Collapse -->
                 <div class="collapse show" id="CardFoto">
                     <div class="card-body text-center">
                         <?php
                            if ($manometer['foto'] ==  "none.jpg") {
                                echo '<img class="img img-fluid" id="output" src="' . '../../../manoWS/fotomano/none.jpg' . '" alt="">';
                            } else {
                                echo '<img class="img img-fluid" id="output" src="' . '../../../manoWS/fotomano/' . $manometer['foto'] . '" alt="">';
                            }
                            ?>
                         <div class="input-group mt-3">
                             <div class="custom-file">
                                 <input type="file" class="custom-file-input" id="foto" name="foto" onchange="readURL(event)" aria-describedby="inputGroupFileAddon01" disabled>
                                 <label class="custom-file-label" for="foto">Choose file</label>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             </form>
             <!-- Collapsable Card Example -->
             <div class="card shadow mb-4">
                 <!-- Card Header - Accordion -->
                 <a href="#CardMaps" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="CardMaps">
                     <h6 class="m-0 font-weight-bold text-primary">Lokasi</h6>
                 </a>
                 <!-- Card Content - Collapse -->
                 <div class="collapse show" id="CardMaps">
                     <div class="card-body">
                         <div class=" text-center" id="googleMap" style="width:100%;height:380px;"></div>
                     </div>
                 </div>
             </div>

         </div>

     </div>

 </div>
 <!-- /.container-fluid -->
 <!-- Modal -->
 <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="deleteModalLabel">Hapus Data</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <h6>Apakah Anda yakin menghapus <b><?= $manometer['manometer']; ?></b> ?</h6>
                 <h6>Data yang sudah dihapus <b>tidak dapat dikembalikan lagi!</b></h6>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-info" data-dismiss="modal">Tidak</button>
                 <?= anchor('masterManometer/delete/' . $manometer['id_manometer'], '<button class="btn btn-danger">Hapus</button>') ?>
             </div>
         </div>
     </div>
 </div>

 <script src="http://maps.googleapis.com/maps/api/js"></script>
 <script>
     // variabel global
     var marker;
     var edit;

     var readURL = function(event) {
         var input = event.target;

         var reader = new FileReader();
         reader.onload = function() {
             var dataURL = reader.result;
             var output = document.getElementById('output');
             output.src = dataURL;
         };
         reader.readAsDataURL(input.files[0]);
     };

     function taruhMarker(peta, posisiTitik) {

         if (edit == 1) {
             if (marker) {
                 // pindahkan marker
                 marker.setPosition(posisiTitik);
             } else {
                 // buat marker baru
                 marker = new google.maps.Marker({
                     position: posisiTitik,
                     map: peta
                 });
             }

             // isi nilai koordinat ke form
             document.getElementById("latitude").value = posisiTitik.lat();
             document.getElementById("longitude").value = posisiTitik.lng();
             document.getElementById("akurasi").value = "22";
         } else {

         }

     }


     function initialize() {
         if (localStorage.getItem('manoEdit') == 1) {
             enableEdit();
         } else {}
         var propertiPeta = {
             center: new google.maps.LatLng(<?= $manometer['latitude'] ?>, <?= $manometer['longitude'] ?>),
             zoom: 15,
             mapTypeId: google.maps.MapTypeId.ROADMAP
         };

         var peta = new google.maps.Map(document.getElementById("googleMap"), propertiPeta);

         //even listner ketika peta diklik untuk mendapatkan value koordinat
         google.maps.event.addListener(peta, 'click', function(event) {
             taruhMarker(this, event.latLng);
         });

         // membuat Marker
         var marker = new google.maps.Marker({
             position: new google.maps.LatLng(<?= $manometer['latitude'] ?>, <?= $manometer['longitude'] ?>),
             map: peta
         });

     }

     // event jendela di-load  
     google.maps.event.addDomListener(window, 'load', initialize);

     function enableEdit() {
         edit = 1;
         $("#btn_cancel").removeClass("disabled");
         document.getElementById("btn_save").disabled = false;
         document.getElementById("btn_save").style.visibility = 'visible';
         document.getElementById("btn_edit").disabled = true;
         document.getElementById("nama").disabled = false;
         document.getElementById("kode").disabled = false;
         document.getElementById("gis").disabled = false;
         document.getElementById("diameter").disabled = false;
         document.getElementById("flag_active").disabled = false;
         document.getElementById("latitude").disabled = false;
         document.getElementById("longitude").disabled = false;
         document.getElementById("akurasi").disabled = false;
         document.getElementById("operator").disabled = false;
         document.getElementById("foto").disabled = false;
     }

     function cancel(id) {
         edit = 0;
         localStorage.setItem('manoEdit', 0);
         window.location = '<?= base_url('masterManometer/detail/') ?>' + id;
     }

     function disableEdit() {
         edit = 0;
         localStorage.setItem('manoEdit', 0);
     }
 </script>