 <!-- Begin Page Content -->
 <div class="container-fluid">
     <form action="<?= base_url('masterManometer/tambah') ?>" method="POST" enctype="multipart/form-data">
         <div class="row">

             <div class="col-lg-8">
                 <!-- Manometer -->
                 <div class="card shadow mb-4">
                     <div class="card-header py-3">
                         <h6 class="m-0 font-weight-bold text-primary">Data</h6>
                     </div>
                     <div class="card-body">
                         <div class="mb-20"><?= $this->session->flashdata('message') ?></div>
                         <div class="row">
                             <div class="col-lg-8 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Nama</span>
                                     </div>
                                     <input type="text" class="form-control" id="nama_manometer" name="nama_manometer" value="<?= set_value('nama_manometer') ?>" placeholder="Nama Manometer" aria-label="nama_manometer" aria-describedby="basic-addon1">
                                 </div>
                                 <?= form_error('nama_manometer', '<small class="text-danger">', '</small>') ?>
                             </div>
                             <div class="col-lg-4 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <label class="input-group-text" for="flag_active">Status</label>
                                     </div>
                                     <select class="custom-select" id="flag_active" name="flag_active">
                                         <option value="1" selected>Aktif</option>
                                         <option value="0">Tidak Aktif</option>
                                     </select>
                                 </div>
                             </div>

                             <div class="col-lg-3 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">ID</span>
                                     </div>
                                     <input type="text" class="form-control" placeholder="ID Manometer" aria-label="basic-addon1" aria-describedby="basic-addon1" value="<?= $total_manometer + 1; ?>" disabled>
                                 </div>
                             </div>

                             <div class="col-lg-3 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Kode</span>
                                     </div>
                                     <?php if ($this->session->userdata('kodeZona') != null) { ?>
                                         <input type="text" class="form-control" id="kodeZona" name="kodeZona" value="<?= $total_manozona + 1; ?>" placeholder="Kode Manometer" aria-label="kodeZona" aria-describedby="basic-addon1">
                                     <?php } else { ?>
                                         <input type="text" class="form-control" id="kodeZona" name="kodeZona" value="<?= set_value('kodeZona') ?>" placeholder="Kode Manometer" aria-label="kodeZona" aria-describedby="basic-addon1">
                                     <?php } ?>
                                 </div>
                                 <?= form_error('kodeZona', '<small class="text-danger">', '</small>') ?>
                             </div>

                             <div class="col-lg-3 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">GIS</span>
                                     </div>
                                     <input type="text" class="form-control" id="gis" name="gis" placeholder="Kode GIS" aria-label="gis" aria-describedby="basic-addon1">
                                 </div>
                             </div>

                             <div class="col-lg-3 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Ø</span>
                                     </div>
                                     <input type="text" class="form-control" id="diameter" name="diameter" value="<?= set_value('diameter') ?>" placeholder="Diameter" aria-label="diameter" aria-describedby="basic-addon1">
                                 </div>
                                 <?= form_error('diameter', '<small class="text-danger">', '</small>') ?>
                             </div>
                         </div>

                         <h6 class="mt-2 mb-3">Lokasi</h6>
                         <div class="row">
                             <div class="col-lg-5 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <label class="input-group-text" for="manoWilayah">Wilayah</label>
                                     </div>
                                     <select class="custom-select" id="manoWilayah" name="manoWilayah">
                                         <?php if ($this->session->userdata('wilayah') == "KANTOR PUSAT") { ?>
                                             <option value="">Pilih Wilayah</option>
                                         <?php } ?>
                                         <?php foreach ($wilayah as $w) : ?>
                                             <option value="<?= $w['kecamatan']; ?>" <?php if ($w['kecamatan'] == $this->session->userdata('kodeWilayah')) { ?> selected <?php } ?>><?= $w['kecamatan']; ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>
                                 <?= form_error('manoWilayah', '<small class="text-danger">', '</small>') ?>
                             </div>

                             <div class="col-lg-4 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <label class="input-group-text" for="manoZona">Zona</label>
                                     </div>
                                     <select class="custom-select" id="manoZona" name="manoZona">
                                         <option value="">Pilih Zona</option>
                                         <?php foreach ($zona as $z) : ?>
                                             <option value="<?= $z['zona']; ?>" <?php if ($z['zona'] == $this->session->userdata('kodeZona') || $z['zona'] == set_value('manoZona')) { ?> selected <?php } ?>><?= $z['zona']; ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>
                                 <?= form_error('manoZona', '<small class="text-danger">', '</small>') ?>
                             </div>

                             <div class="col-lg-3 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">DMA</span>
                                     </div>
                                     <input type="text" class="form-control" id="dma" name="dma" placeholder="Lokasi DMA" aria-label="dma" aria-describedby="basic-addon1">
                                 </div>
                             </div>

                             <div class="col-lg-4 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Latitude</span>
                                     </div>
                                     <input type="text" class="form-control" id="latitude" name="latitude" value="<?= set_value('latitude') ?>" placeholder="latitude" aria-label="latitude" aria-describedby="basic-addon1">
                                 </div>
                                 <?= form_error('latitude', '<small class="text-danger">', '</small>') ?>
                             </div>

                             <div class="col-lg-5 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Longitude</span>
                                     </div>
                                     <input type="text" class="form-control" id="longitude" name="longitude" value="<?= set_value('longitude') ?>" placeholder="longitude" aria-label="longitude" aria-describedby="basic-addon1">
                                 </div>
                                 <?= form_error('longitude', '<small class="text-danger">', '</small>') ?>
                             </div>

                             <div class="col-lg-3 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">Akurasi</span>
                                     </div>
                                     <input type="text" class="form-control" id="akurasi" name="akurasi" value="<?= set_value('akurasi'); ?>" placeholder="Akurasi" aria-label="akurasi" aria-describedby="basic-addon1">
                                 </div>
                                 <?= form_error('akurasi', '<small class="text-danger">', '</small>') ?>
                             </div>
                             <div class="col-lg">
                                 <p class="text text-danger"><small><i>*Untuk mendapatkan Latitude, Longitude, dan Akurasi silahkan tentukan lokasi manometer pada peta di bawah ini</i></small></p>
                                 <div class="text-center" id="googleMap" style="width:100%;height:380px;"></div>
                                 <hr />
                             </div>
                         </div>

                         <h6 class="mt-3 mb-3">Petugas Baca</h6>
                         <div class="row">
                             <div class="col-lg-4 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text" id="basic-addon1">NIK</span>
                                     </div>
                                     <input type="text" class="form-control disabled" id="nikOperator" name="nikOperator" value="<?= set_value('nikOperator') ?><?= $pembacaZona['nik']; ?>" aria-label="nikOperator" aria-describedby="basic-addon1">
                                 </div>
                                 <?= form_error('nikOperator', '<small class="text-danger">', '</small>') ?>
                             </div>

                             <div class="col-lg-8 mb-2">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <label class="input-group-text" for="operator">Nama</label>
                                     </div>
                                     <select class="custom-select" id="operator" name="operator">
                                         <option value="" selected>Pilih Pembaca</option>
                                         <?php foreach ($pembaca as $p) : ?>
                                             <option value="<?= $p['nama']; ?>" <?php if ($pembacaZona['operator'] == $p['nama'] || $p['nama'] == set_value('operator')) { ?> selected <?php } ?>><?= $p['nama']; ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>
                                 <?= form_error('operator', '<small class="text-danger">', '</small>') ?>
                             </div>

                         </div>

                         <!-- Button -->
                         <div class="row">
                             <div class="col-lg-1 text-left">
                                 <a href="<?= base_url('/masterManometer') ?>" onclick="disableEdit()" class="btn btn-secondary mb-2 mt-4" data-toggle="tooltip" title="Kembali"><i class="fas fa-fw fa-caret-left text-white"></i></a>
                             </div>

                             <div class="col-lg text-right">
                                 <a>
                                     <button class="btn btn-success mb-2 mt-4" onclick="disableEdit()" id="btn_save" data-toggle="tooltip" title="Simpan data baru"><i class="fas fa-fw fa-save"></i></button>
                                 </a>

                             </div>

                         </div>
                     </div>
                     <!-- end card body -->
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
                             <?= '<img class="img img-fluid" id="output" src="' . '../../../manoWS/fotomano/none.jpg' . '" alt="">';
                                ?>
                             <div class="input-group mt-3">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="foto" name="foto" onchange="readURL(event)" aria-describedby="inputGroupFileAddon01">
                                     <label class="custom-file-label" for="foto">Choose file</label>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                     <div class="col-lg-4">
                         <input type="text" class="form-control" id="id_manometer" name="id_manometer" value="<?= $total_manometer + 1; ?>" placeholder="ID Manometer" aria-label="id_manometer" aria-describedby="basic-addon1" style="visibility: hidden">
                     </div>
                     <div class="col-lg-4">
                         <input type="text" class="form-control" id="id_kecamatan" name="id_kecamatan" value="<?= $areakerja['indekareakerja']; ?>" placeholder="ID Kecamatan" aria-label="id_kecamatan" aria-describedby="basic-addon1">
                     </div>
                     <div class="col-lg-4">
                         <input type="text" class="form-control" id="id_zona" name="id_zona" value="<?= set_value('id_zona') ?><?= $IDzona['id_zona']; ?>" placeholder="ID Zona" aria-label="id_zona" aria-describedby="basic-addon1" style="visibility: hidden">
                     </div>
                 </div>

             </div>
         </div>
     </form>
 </div>
 <!-- /.container-fluid -->

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
             console.log(dataURL);
         };
         reader.readAsDataURL(input.files[0]);
     };

     function taruhMarker(peta, posisiTitik) {

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

     }


     function initialize() {
         if (localStorage.getItem('manoEdit') == 1) {
             enableEdit();
         } else {}
         var propertiPeta = {
             center: new google.maps.LatLng(<?= $areakerja['latitude'] ?>, <?= $areakerja['longitude'] ?>),
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
             position: new google.maps.LatLng(<?= $areakerja['latitude'] ?>, <?= $areakerja['longitude'] ?>),
             map: peta
         });

     }

     // event jendela di-load  
     google.maps.event.addDomListener(window, 'load', initialize);
 </script>