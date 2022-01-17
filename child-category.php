<?php 
    include_once './header.php'; 
    if(!isset($_SESSION['admin_id'])){
        header('LOCATION: '.ADMIN_BASE_URL.'log-in.php'); 
    } 

    if(isset($_POST['add_sub_cat'])){
        $sub_cat_name = trim(htmlspecialchars($_POST['sub_cat_name']));
        $parent_cat = trim(htmlspecialchars($_POST['parent_cat']));
        $check = mysqli_query($conn, "SELECT * FROM post_sub_category WHERE name='".$sub_cat_name."' and status='1'");
        if(mysqli_num_rows($check) > 0){
            $_SESSION['error'] = 'Category already exist.';
        } else {
            $query = mysqli_query($conn, "INSERT INTO post_sub_category SET name='".$sub_cat_name."', parent_category_id='".$parent_cat."', status='1'");
            if($query){
                $_SESSION['success'] = 'Category created successfully';
            } else {
                $_SESSION['error'] = 'Error occured';
            } 
        }
    }

    if(isset($_POST['update_sub_cat'])){
        $sub_cat_name = trim(htmlspecialchars($_POST['sub_cat_name']));
        $sub_cat_id = $_POST['sub_cat_id'];
        $parent_cat = trim(htmlspecialchars($_POST['parent_cat']));
        $check = mysqli_query($conn, "SELECT * FROM post_sub_category WHERE name='".$sub_cat_name."' and status='1'");
        if(mysqli_num_rows($check) > 0){
            $_SESSION['error'] = 'Category already exist.';
        } else {
            $query = mysqli_query($conn, "UPDATE post_sub_category SET name='".$sub_cat_name."', parent_category_id='".$parent_cat."', status='1' WHERE id = '".$sub_cat_id."'");
            if($query){
                $_SESSION['success'] = 'Category updated successfully';
            } else {
                $_SESSION['error'] = 'Error occured';
            } 
        }
    }

    if(isset($_GET['del_id']) && $_GET['del_id'] > 0){
        $cat_id = $_GET['del_id'];
        $query = mysqli_query($conn,"UPDATE post_sub_category SET status='0' WHERE id='".$cat_id."'");
        if($query){
            $_SESSION['success'] = 'Category deleted successfully';
        } else {
            $_SESSION['error'] = "Some error occured, Please try again.";
        }
        header('LOCATION: '.ADMIN_BASE_URL.'child-category.php');
    }

    if(isset($_GET['cat_id']) && $_GET['cat_id'] > 0){
        $cat_id = $_GET['cat_id'];
        $data = mysqli_query($conn,"SELECT * FROM post_sub_category WHERE id='".$cat_id."' and status='1'");
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
                    <li class="breadcrumb-item active" aria-current="page">Child category</li>
                </ol>
            </nav>
        </div>
        <div class="main-wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-title">
                                    <p class="page-desc">Child category list.</p>
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
                                                <input value="<?= isset($cat_data)?$cat_data['name']:'' ?>" required type="text" class="form-control" name="sub_cat_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Parent category</label>
                                                <select class="form-control" required name="parent_cat">
                                                    <option>--Select--</option>
                                                    <?php
                                                        $query = mysqli_query($conn,"SELECT * FROM post_categories WHERE status = '1'");
                                                    if(mysqli_num_rows($query) > 0){
                                                        $count = 1;
                                                        while($cat = mysqli_fetch_array($query)){
                                                            ?>
                                                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                                            <?php
                                                        }
                                                        }    
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            if(isset($cat_data)){ 
                                                ?>
                                                <input type="hidden" name="sub_cat_id" value="<?= $cat_data['id'] ?>">
                                                <button type="submit" name="update_sub_cat" class="btn btn-primary">Update</button>
                                                <a href="<?= ADMIN_BASE_URL ?>child-category.php"><button type="button" name="update_sub_cat" class="btn btn-warning">Add new</button>
                                                <?php
                                            } else { ?>

                                            <button type="submit" name="add_sub_cat" class="btn btn-primary">Create</button>
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
                                                    <th scope="col">Parent category</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $query = mysqli_query($conn,"SELECT * FROM post_sub_category WHERE status = '1'");
                                                    if(mysqli_num_rows($query) > 0){
                                                        $count = 1;
                                                        while($cat = mysqli_fetch_array($query)){
                                                            ?>
                                                                <tr>
                                                                    <th scope="row"><?= $count ?></th>
                                                                    <td><?= $cat['name'] ?></td>
                                                                    <td><?= parent_cat_name($cat['parent_category_id'], $conn) ?></td>
                                                                    <td><a href="<?= ADMIN_BASE_URL ?>child-category.php?cat_id=<?= $cat['id'] ?>"><button type="button" class="btn btn-xs btn-warning">Edit</button></a> <a href="<?= ADMIN_BASE_URL ?>child-category.php?del_id=<?= $cat['id'] ?>"><button type="button" class="btn btn-xs btn-danger">Delete</button></a></td>
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