<?php 
    include_once './header.php'; 
    if(!isset($_SESSION['admin_id'])){
        header('LOCATION: '.ADMIN_BASE_URL.'log-in.php'); 
    } 

    if(isset($_GET['del_id']) && $_GET['del_id'] > 0){
        $user_id = $_GET['del_id'];
        $query = mysqli_query($conn,"UPDATE users SET varified='0', status='0' WHERE id='".$user_id."'");
        if($query){
            $_SESSION['user_deleted'] = 'User deleted successfully';
        } else {
            $_SESSION['user_not_deleted'] = "Some error occured, Please try again.";
        }
        header('LOCATION: '.ADMIN_BASE_URL.'approved-users.php');
    }

    if(isset($_GET['uapp_id']) && $_GET['uapp_id'] > 0){
        $user_id = $_GET['uapp_id'];
        $query = mysqli_query($conn,"UPDATE users SET varified='0' WHERE id='".$user_id."'");
        if($query){
            $_SESSION['user_uapp'] = 'User Unapproved successfully';
        } else {
            $_SESSION['user_not_uapp'] = "Some error occured, Please try again.";
        }
        header('LOCATION: '.ADMIN_BASE_URL.'approved-users.php');
    }
?> 
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Approved user</li>
                </ol>
            </nav>
        </div>
        <div class="main-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-title">
                                    <p class="page-desc">Approved user list.</p>
                                </div>
                                <?php if(isset($_SESSION['user_deleted']) && $_SESSION['user_deleted'] != '') { ?>
                                <div class="alert alert-success" role="alert">
                                    <?= $_SESSION['user_deleted'] ?>
                                </div>
                            <?php } else if(isset($_SESSION['user_not_deleted']) && $_SESSION['user_not_deleted'] != ''){ ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $_SESSION['user_not_deleted'] ?>
                                </div>
                            <?php } ?>

                            <!-- user unapprove session code start -->

                             <?php if(isset($_SESSION['user_uapp']) && $_SESSION['user_uapp'] != '') { ?>
                                <div class="alert alert-success" role="alert">
                                    <?= $_SESSION['user_uapp'] ?>
                                </div>
                            <?php } else if(isset($_SESSION['user_not_uapp']) && $_SESSION['user_not_uapp'] != ''){ ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $_SESSION['user_not_uapp'] ?>
                                </div>
                            <?php } ?>


                            <!-- user unapprove session code end -->
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
                                                    $query = mysqli_query($conn,"SELECT * FROM users WHERE status = '1' AND varified = '1'");
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
                                                                    <td><a href="<?= ADMIN_BASE_URL ?>approved-users.php?uapp_id=<?= $client['id'] ?>"><button type="button" class="btn btn-xs btn-primary">Unapprove</button></a> <a href="<?= ADMIN_BASE_URL ?>approved-users.php?del_id=<?= $client['id'] ?>"><button type="button" class="btn btn-xs btn-danger">Delete</button></a></td>
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
$_SESSION['user_deleted'] = '';
$_SESSION['user_not_deleted'] = '';
session_unset($_SESSION['user_deleted']);
session_unset($_SESSION['user_not_deleted']);
$_SESSION['user_uapp'] = '';
$_SESSION['user_not_uapp'] = '';
session_unset($_SESSION['user_uapp']);
session_unset($_SESSION['user_not_uapp']);
?>                