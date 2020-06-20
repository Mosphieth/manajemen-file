 <!-- Begin Page Content -->
 <div class="container-fluid">

     <!-- Page Heading -->
     <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
     <div class="row">
         <div class="col-lg-10">
             <?= $this->session->flashdata('message'); ?>
             <table class="table table-hover">
                 <thead>
                     <tr>
                         <th scope="col">#</th>
                         <th scope="col">User name</th>
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
                             <td><?= $f['nama']; ?></td>
                             <td><?= $f['category']; ?></td>
                             <td><?= $f['file_name']; ?></td>
                             <td><a href="./assets/file/<?= $f['file']; ?>">Download</a></td>
                             <td><?= date('d F Y', $f['date_created']); ?></td>
                             <td><?= $f['status']; ?></td>
                             <td>
                                 <a href="<?= base_url('kaprodi/accept/' . $f['id']) ?>" class="badge badge-primary">Accept</a>
                                 <a href="<?= base_url('kaprodi/refuse/' . $f['id']) ?>" class="badge badge-danger">Refuse</a>
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