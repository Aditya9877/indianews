<?php 
    include_once './header.php'; 
    if(!isset($_SESSION['admin_id'])){
        header('LOCATION: '.ADMIN_BASE_URL.'log-in.php'); 
    } 

    if(isset($_GET['app_id']) && $_GET['app_id'] > 0){
        $user_id = $_GET['app_id'];
        $query = mysqli_query($conn,"UPDATE users SET varified='1' WHERE status='1' AND id='".$user_id."'");
        if($query){
            $_SESSION['user_approved'] = 'User approved successfully';
        } else {
            $_SESSION['user_not_approved'] = "Some error occured, Please try again.";
        }
        header('LOCATION: '.ADMIN_BASE_URL.'new-users.php');
    }
?> 
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New user</li>
                </ol>
            </nav>
        </div>
        <div class="main-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-title">
                                    <p class="page-desc">Pending user list waiting for approval.</p>
                                </div>
                                <?php if(isset($_SESSION['user_approved']) && $_SESSION['user_approved'] != '') { ?>
                                <div class="alert alert-success" role="alert">
                                    <?= $_SESSION['user_approved'] ?>
                                </div>
                            <?php } else if(isset($_SESSION['user_not_approved']) && $_SESSION['user_not_approved'] != ''){ ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $_SESSION['user_not_approved'] ?>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Phone number</th>
                                                    <th scope="col">Date of birth</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $query = mysqli_query($conn,"SELECT * FROM users WHERE status = '1' AND varified = '0'");
                                                    if(mysqli_num_rows($query) > 0){
                                                        $count = 1;
                                                        while($client = mysqli_fetch_array($query)){
                                                            ?>
                                                                <tr>
                                                                    <th scope="row"><?= $count ?></th>
                                                                    <td><?= $client['name'] ?></td>
                                                                    <td><?= $client['email'] ?></td>
                                                                    <td><?= $client['phone_no'] ?></td>
                                                                    <td><?= $client['dob'] ?></td>
                                                                    <td><a href="<?= ADMIN_BASE_URL ?>new-users.php?app_id=<?= $client['id'] ?>"><button type="button" class="btn btn-xs btn-success">Approve</button></a></td>
                                                                </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='7' class='text-center'>No result found!!</td></tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
<?php
include_once 'footer.php';
$_SESSION['user_approved'] = '';
$_SESSION['user_not_approved'] = '';
session_unset($_SESSION['user_approved']);
session_unset($_SESSION['user_not_approved']);
?>                