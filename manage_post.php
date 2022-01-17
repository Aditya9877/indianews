<?php
    include_once './header.php';
    if(!isset($_SESSION['user_id'])){
        header('location: '.BASE_URL.'log-in.php');
    }
    
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $id = $_GET['id'];
        $query = mysqli_query($conn,"UPDATE posts SET status='0' WHERE id='".$id."'");
        if($query){
            $_SESSION['success'] = 'Post deleted successfully';
        } else {
            $_SESSION['error'] = "Some error occured, Please try again.";
        }
    }
?>
<div class="main-content--section pbottom--30">
    <div class="container">
        <div class="main--content">
            <div class="post--items post--items-1 pd--30-0">
                <div class="row gutter--15">
                    <div class="col-12">
                        <?php
                            if(isset($_SESSION['success']) && $_SESSION['success']  != ''){
                                ?>
                                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                                <?php
                            }

                            if(isset($_SESSION['error']) && $_SESSION['error']  != ''){
                                ?>
                                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="col-12">
                        <h3>Manage posts</h3>
                    </div>
                    <div class="col-12">
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
                                                                    <td><a href="<?= BASE_URL ?>add-post.php?id=<?= $posts['id'] ?>"><button type="button" class="btn btn-xs btn-warning">Edit</button></a> <a href="<?= BASE_URL ?>manage_post.php?id=<?= $posts['id'] ?>"><button type="button" class="btn btn-xs btn-danger">Delete</button></a></td>
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
    </div>
</div>
<?php
    include_once './footer.php';
    $_SESSION['success'] = '';
    $_SESSION['error'] = '';
?>