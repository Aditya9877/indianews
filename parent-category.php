<?php 
    include_once './header.php'; 
    if(!isset($_SESSION['admin_id'])){
        header('LOCATION: '.ADMIN_BASE_URL.'log-in.php'); 
    } 

    if(isset($_POST['add_cat'])){
        $cat_name = trim(htmlspecialchars($_POST['cat_name']));
        $check = mysqli_query($conn, "SELECT * FROM post_categories WHERE name='".$cat_name."' and status='1'");
        if(mysqli_num_rows($check) > 0){
            $_SESSION['error'] = 'Category already exist.';
        } else {
            $query = mysqli_query($conn, "INSERT INTO post_categories SET name='".$cat_name."', url_slug='".strtolower($cat_name)."', status='1'");
            if($query){
                $_SESSION['success'] = 'Category created successfully';
            } else {
                $_SESSION['error'] = 'Error occured';
            } 
        }
    }

    if(isset($_POST['update_cat'])){
        $cat_name = trim(htmlspecialchars($_POST['cat_name']));
        $cat_id = $_POST['cat_id'];
        $check = mysqli_query($conn, "SELECT * FROM post_categories WHERE name='".$cat_name."' and status='1'");
        if(mysqli_num_rows($check) > 0){
            $_SESSION['error'] = 'Category already exist.';
        } else {
            $query = mysqli_query($conn, "UPDATE post_categories SET name='".$cat_name."', url_slug='".strtolower($cat_name)."', status='1' WHERE id = '".$cat_id."'");
            if($query){
                $_SESSION['success'] = 'Category updated successfully';
            } else {
                $_SESSION['error'] = 'Error occured';
            } 
        }
    }

    if(isset($_GET['del_id']) && $_GET['del_id'] > 0){
        $cat_id = $_GET['del_id'];
        $query = mysqli_query($conn,"UPDATE post_categories SET status='0' WHERE id='".$cat_id."'");
        if($query){
            $_SESSION['success'] = 'Category deleted successfully';
        } else {
            $_SESSION['error'] = "Some error occured, Please try again.";
        }
        header('LOCATION: '.ADMIN_BASE_URL.'parent-category.php');
    }

    if(isset($_GET['cat_id']) && $_GET['cat_id'] > 0){
        $cat_id = $_GET['cat_id'];
        $data = mysqli_query($conn,"SELECT * FROM post_categories WHERE id='".$cat_id."' and status='1'");
        if(mysqli_num_rows($data) > 0){
            $cat_data = mysqli_fetch_assoc($data);       
        }
    }

?> 
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Post category</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Parent category</li>
                </ol>
            </nav>
        </div>
        <div class="main-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-title">
                                    <p class="page-desc">Category list.</p>
                                </div>
                                <?php if(isset($_SESSION['success']) && $_SESSION['success'] != '') { ?>
                                <div class="alert alert-success" role="alert">
                                    <?= $_SESSION['success'] ?>
                                </div>
                            <?php } else if(isset($_SESSION['error']) && $_SESSION['error'] != ''){ ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $_SESSION['error'] ?>
                                </div>
                            <?php } ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= isset($cat_data)?'Update':'Add'?> Category</h5>
                                        
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Category name</label>
                                                <input value="<?= isset($cat_data)?$cat_data['name']:'' ?>" required type="text" class="form-control" name="cat_name">
                                            </div>
                                            <?php
                                            if(isset($cat_data)){ 
                                                ?>
                                                <input type="hidden" name="cat_id" value="<?= $cat_data['id'] ?>">
                                                <button type="submit" name="update_cat" class="btn btn-primary">Update</button>
                                                <a href="<?= ADMIN_BASE_URL ?>parent-category.php"><button type="button" name="update_cat" class="btn btn-warning">Add new</button>
                                                <?php
                                            } else { ?>

                                            <button type="submit" name="add_cat" class="btn btn-primary">Create</button>
                                        <?php } ?>
                                        </form>
                                    </div>
                                </div>
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
                                                    <th scope="col">Slug</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $query = mysqli_query($conn,"SELECT * FROM post_categories WHERE status = '1'");
                                                    if(mysqli_num_rows($query) > 0){
                                                        $count = 1;
                                                        while($cat = mysqli_fetch_array($query)){
                                                            ?>
                                                                <tr>
                                                                    <th scope="row"><?= $count ?></th>
                                                                    <td><?= $cat['name'] ?></td>
                                                                    <td><?= $cat['url_slug'] ?></td>
                                                                    <td><a href="<?= ADMIN_BASE_URL ?>parent-category.php?cat_id=<?= $cat['id'] ?>"><button type="button" class="btn btn-xs btn-warning">Edit</button></a> <a href="<?= ADMIN_BASE_URL ?>parent-category.php?del_id=<?= $cat['id'] ?>"><button type="button" class="btn btn-xs btn-danger">Delete</button></a></td>
                                                                </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='4' class='text-center'>No result found!!</td></tr>";
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
$_SESSION['success'] = '';
$_SESSION['error'] = '';
session_unset($_SESSION['success']);
session_unset($_SESSION['error']);
?>                