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
        header('LOCATION: '.ADMIN_BASE_URL.'new-users.php');11                
                    
    }
?> 
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Posts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All post</li>
                </ol>
            </nav>
        </div>
        <div class="main-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-title">
                                    <p class="page-desc">List of all posts.</p>
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
                                                    <th scope="col">Featured image</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Parent category</th>
                                                    <th scope="col">Child category</th>
                                                    <th scope="col">Added by</th>
                                                    <th scope="col">Added at</th>
                                                    <th scope="col">Updated at</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $query = mysqli_query($conn,"SELECT * FROM posts WHERE status = '1'");
                                                    if(mysqli_num_rows($query) > 0){
                                                        $count = 1;
                                                        while($posts = mysqli_fetch_array($query)){
                                                            ?>
                                                                <tr>
                                                                    <th scope="row"><?= $count ?></th>
                                                                    <td><img src="<?= BASE_URL.$posts['image'] ?>" height="80" width="80" /></td>
                                                                    <td><?= $posts['title'] ?></td>
                                                                    <td><?= parent_cat_name($posts['pcat'], $conn) ?></td>
                                                                    <td><?= child_cat_name($posts['ccat'], $conn) ?></td>
                                                                    <td><?= author_name($posts['added_by'], $conn) ?></td>
                                                                    <td><?= date('d-m-Y h:i A', strtotime($posts['created_at'])) ?></td>
                                                                    <td><?= date('d-m-Y h:i A', strtotime($posts['updated_at'])) ?></td>
                                                                    <td><a href="<?= ADMIN_BASE_URL ?>posts.php?post_id=<?= $posts['id'] ?>"><button type="button" class="btn btn-xs btn-danger">Delete</button></a></td>
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