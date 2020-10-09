<?php 
include('../template/curl.php'); 
include('../../config.php');
include('../template/head.php');
on_system();
$token = $_SESSION['access_token']; 

if(isset($_POST['btn-kirim']))
{
    $packageSend = getData(api_url()."/api/v1/tracking/send",$token,$_POST);
    $packageSend = json_decode($packageSend,true);

    // $send = array(
    //     'target_id' => $_POST['target_id']
    // );

    
}

if(isset($_POST['btn-mass-kirim']))
{
    $testing = array(
        'list' => $_POST['checkPackage']
    );
    $multiple = getData(api_url()."/api/v1/tracking/mutiple-send",$token,$testing);
    $multiple = json_decode($multiple,true);
}
$package = getData(api_url()."/api/v1/package/all",$token,NULL);
$package = json_decode($package,true);

?>
<body>

<!-- page-wrapper Start-->
<div class="page-wrapper">

    <?php include('../template/navbar.php') ?>

    <!-- Page Body Start-->
    <div class="page-body-wrapper">

        <?php include('../template/sidebar.php');?>

        <div class="page-body">
            <!-- Content start here -->
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="page-header-left">
                                <h3>Paket 
                                    <small>Padistik Admin panel</small>
                                </h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ol class="breadcrumb pull-right">
                                <li class="breadcrumb-item"><a href="<?= base_url().'/admin'?>"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">Paket</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->

            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Kirim Paket</h5><br>                   
                                <form method="POST" id="formCheckSingle">
                                    <div class="form">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="target_id" placeholder="Nomor Resi">
                                                    <?php 
                                                        if(isset($_POST['btn-mass-kirim'])){
                                                            if(isset($multiple['status']))
                                                            {
                                                                if($multiple['status']==1){
                                                                    echo '<p class="badge badge-success">Sukses!</p>';
                                                                }
                                                            }
                                                        }
                                                        if(isset($_POST['btn-kirim'])){
                                                            if(isset($packageSend['status'])){
                                                                if($packageSend['status']==0){
                                                                    echo '<p class="badge badge-warning">Paket tidak disini!</p>';
                                                                }
                                                                else if($packageSend['status']==1){
                                                                    echo '<p class="badge badge-success">Paket berhasil dikirim!</p>';
                                                                }
                                                            }
                                                            else {
                                                                echo '<p class="badge badge-danger">Nomor resi tidak ditemukan!</p>';
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary btn-sm" name="btn-kirim">Kirim Paket</button>
                                                </div>
                                            </div>
                                        </div>                                
                                    </div>
                                </form>        
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-original-title="test" data-target="#scanPackage">Scan Paket</button>     
                            </div>
                            <div class="card-body">
                                <div id="basicScenario" class="product-list">
                                    <?php 
                                    
                                    if(!empty($package['data'])){ 
                                        $package = $package['data'];
                                        ?>   
                                        <form method="POST">
                                        <div class="table-responsive">
                                            <table id="example" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th><button class="btn btn-primary btn-sm" name="btn-mass-kirim">Kirim Paket</button></th>
                                                        <th>No Resi</th>
                                                        <th>Asal</th>
                                                        <th>Tujuan</th>
                                                        <th>Waktu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        for($i=0; $i<sizeof($package); $i++)
                                                        {
                                                    ?>
                                                
                                                    <tr>
                                                        <td><input type="checkbox" value="<?= $package[$i]['no_resi'] ?>" name="checkPackage[]"></td>
                                                        <td><?= $package[$i]['no_resi'] ?></td>
                                                        <td><?= $package[$i]['origin'] ?></td>
                                                        <td><?= $package[$i]['receiver_city'] ?></td>
                                                        <td><?= dateIndo($package[$i]['created_at']) ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="modal fade" id="scanPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title f-w-600" id="exampleModalLabel">Scan Paket</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form">
                    <div style="width: 100%" id="reader"></div>
                    <p class="badge badge-secondary form-control" id="message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
        <?php include('../template/footer.php');?>
    </div>

</div>

<?php include('../template/script.php') ?>
<script src="qr.js"></script>
<script>
    $('#formCheckSingle').on('submit',function(){
        if($('input[name="target_id"').val() == ''){
            return false;
        }
    })

    function onScanSuccess(qrCodeMessage) {
	// handle on success condition with the decoded message
    $.ajax({
        'url' : 'action-kirim.php',
        'method' : 'POST',
        'dataType' : 'json',
        'data' : {
            'target_id' : qrCodeMessage
        },
        success : function(data){
            if(typeof data.status !== 'undefined'){
                if(data.status == 1){
                    $('#message').html('Paket berhasil dikirim!');
                }
                else if(data.status == 0)
                {
                    $('#message').html('Paket tidak disini!');
                }   
            }
            else {
                $('#message').html('Nomor resi tidak terdaftar!');
            }
            setTimeout(function(){ $('#message').html(''); }, 2000);
        }
    });
}

    var html5QrcodeScanner = new Html5QrcodeScanner(
	"reader", { fps: 7, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
</body>
</html>
