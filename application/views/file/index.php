 <!-- Begin Page Content -->
 <div class="container-fluid">

     <!-- Page Heading -->
     <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
     <div class="row">
         <div class="col-lg-10">
             <?php if (validation_errors()) : ?>
                 <div class="alert alert-danger alert-dismissible fade show pb-0" role="alert">
                     <?= validation_errors(); ?>
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
             <?php endif; ?>
             <?= $this->session->flashdata('message'); ?>
             <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubmenuModal">Add new file</a>
             <table class="table table-hover">
                 <thead>
                     <tr>
                         <th scope="col">#</th>
                         <th scope="col">File category</th>
                         <th scope="col">File name</th>
                         <th scope="col">File</th>
                         <th scope="col">Date created</th>
                         <th scope="col">Status</th>
                         <th scope="col">Action</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php $i = 1; ?>
                     <?php foreach ($file as $f) : ?>
                         <tr>
                             <th scope="row"><?= $i; ?></th>
                             <td><?= $f['category']; ?></td>
                             <td><?= $f['file_name']; ?></td>
                             <td><a href="./assets/file/<?= $f['file']; ?>">Download</a></td>
                             <td><?= date('d F Y', $f['date_created']); ?></td>
                             <td><?= $f['status']; ?></td>
                             <td>
                                 <a onclick="deleteConfirm('<?= base_url('dosen/deletefile/' . $f['id']); ?>')" href="#" class="badge badge-danger">Delete</a>
                                 <a href="<?= base_url('dosen/viewPdf/' . $f['file']) ?>" class="badge badge-primary">View</a>
                             </td>
                         </tr>
                         <?php $i++ ?>
                     <?php endforeach; ?>
                 </tbody>
             </table>
         </div>
     </div>



 </div>
 <!-- /.container-fluid -->
 </div>
 <!-- End of Main Content -->



 <!-- Modal -->
 <div class="modal fade" id="newSubmenuModal" tabindex="-1" role="dialog" aria-labelledby="newSubmenuModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="newSubmenuModalLabel">Add New File</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <form action="<?php base_url('dosen'); ?>" method="POST" enctype="multipart/form-data">
                 <div class="modal-body">
                     <div class="form-group">
                         <input type="text" class="form-control" id="filename" name="filename" placeholder="File name" pattern="^\S+$">
                     </div>
                     <div class="form-group">
                         <select class="custom-select" id="category" name="category">
                             <option selected>File Category</option>
                             <option value="Soal Ujian">Soal Ujian</option>
                             <option value="Surat Izin">Surat Izin</option>
                             <option value="Laporan">Laporan</option>
                         </select>
                     </div>
                     <!-- <div class="form-group">
                         <input type="file" class="form-control" id="userfile" name="userfile" size="20" accept=".pdf">
                     </div> -->
                     <div class="input-group">
                         <div class="input-group-prepend">
                             <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                         </div>
                         <div class="custom-file">
                             <input type="file" class="form-control" id="userfile" name="userfile" size="20" accept=".pdf">
                             <label class="custom-file-label" for="userfile">Choose file</label>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-primary">Add File</button>
                 </div>
             </form>
         </div>
     </div>
 </div>

 <!-- Delete file modal -->
 <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="n" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="n">Delete File</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>

             <div class="modal-body">
                 <h4>Are you sure want to delete this file?</h4>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                 <a href="" id="btn-delete" class="btn btn-primary">Delete</a>
             </div>
             </form>
         </div>
     </div>
 </div>