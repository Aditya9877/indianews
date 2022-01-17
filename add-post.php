<?php
    include_once './header.php';
    if(!isset($_SESSION['user_id'])){
        header('location: '.BASE_URL.'log-in.php');
    }
    
    if(isset($_POST['submit'])){
        $title = addslashes(htmlspecialchars(trim($_POST['title'])));
        $pcat = htmlspecialchars(trim($_POST['pcat']));
        $ccat = htmlspecialchars(trim($_POST['ccat']));
        $content = addslashes(htmlspecialchars(trim($_POST['content'])));
        if($title == '' || $pcat == '' || $ccat == '' || $content == ''){
            $_SESSION['error'] = 'All fields are required';
        } else {
            $image = $_FILES['featured-image']['tmp_name'];
            $path = "upload/".$_FILES['featured-image']['name'];
            move_uploaded_file($image, $path);
            $query = mysqli_query($conn, "INSERT INTO posts SET title='".$title."', pcat='".$pcat."', ccat='".$ccat."', content='".$content."', image='".$path."', added_by='".$_SESSION['user_id']."'");
            if($query){
                $_SESSION['success'] = 'Post added successfully';
            } else {
                $_SESSION['error'] = 'Error occured, please try again.';
            }
        }
    }

    if(isset($_POST['update'])){
        $id = $_POST['post_id'];
        $title = addslashes(htmlspecialchars(trim($_POST['title'])));
        $pcat = htmlspecialchars(trim($_POST['pcat']));
        $ccat = htmlspecialchars(trim($_POST['ccat']));
        $content = addslashes(htmlspecialchars(trim($_POST['content'])));
        if($title == '' || $pcat == '' || $ccat == '' || $content == ''){
            $_SESSION['error'] = 'All fields are required';
        } else {
            if($_FILES['featured-image']['name'] != ''){
                $image = $_FILES['featured-image']['tmp_name'];
                $path = "upload/".$_FILES['featured-image']['name'];
                move_uploaded_file($image, $path);
                $query_append = " image='".$path."' ,";
            } else {
                $query_append = '';
            }

            $query = mysqli_query($conn, "UPDATE posts SET title='".$title."', pcat='".$pcat."', ccat='".$ccat."', content='".$content."', $query_append added_by='".$_SESSION['user_id']."' WHERE id='".$id."'");
            if($query){
                $_SESSION['success'] = 'Post updated successfully';
            } else {
                $_SESSION['error'] = 'Error occured, please try again.';
            }
        }
    }

    if(isset($_GET['id']) && $_GET['id'] > 0){
        $id = $_GET['id'];
        $query = mysqli_query($conn,"SELECT * FROM posts WHERE id='".$id."' AND status='1'");
        if($query){
            $data = mysqli_fetch_array($query);
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
                        <?php
                            if(isset($data)){
                                echo "<h3>Edit post</h3>";
                            } else {
                                echo "<h3>Add new post</h3>";
                            }
                        ?>                        
                    </div>
                    <div class="col-12">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" value="<?= isset($data)?$data['title']:'' ?>" required class="form-control" name="title" placeholder="Enter title" id="title">
                            </div>
                            <div class="form-group">
                                <label for="pcat">Parent category</label>
                                <select name="pcat" class="form-control" id="pcat" onchange="get_chid_cat(this.value)">
                                    <option>--Select--</option>
                                    <?php 
                                        $query = mysqli_query($conn,"SELECT * FROM post_categories WHERE status = '1'");
                                        if(mysqli_num_rows($query) > 0){
                                            while($cat = mysqli_fetch_array($query)){ ?>
                                                <option <?= isset($data)?$data['pcat']==$cat['id']?'selected':'':'' ?> value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                            <?php }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ccat">Child category</label>
                                <select name="ccat" class="form-control" id="child_cat">
                                    <option>--Select--</option> 
                                    <?php
                                        if(isset($data)){
                                            $query = mysqli_query($conn,"SELECT * FROM post_sub_category WHERE status = '1' and parent_category_id='".$data['pcat']."'");
                                            if(mysqli_num_rows($query) > 0){
                                                while($cat = mysqli_fetch_array($query)){ ?>
                                                    <option <?= isset($data)?$data['ccat']==$cat['id']?'selected':'':'' ?> value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                                <?php }
                                            }
                                        }
                                    ?>                                   
                                </select>
                            </div>
                                <div class="">
                                <label for="content">Content</label>
                                <textarea class="form-control" name="content" rows="5" style="resize: none;"><?= isset($data)?$data['content']:'' ?></textarea>
                            </div>
                            
                            <div class="form-group form-check">
                                <label>Featured image</label>
                                <input type="file" name="featured-image">
                                <?php
                                    if(isset($data)){
                                        echo "<br />";
                                        echo '<img src="'.BASE_URL.$data['image'].'" width="150" height="150">';
                                    }
                                ?>
                                <br />
                            </div>
                            <?php
                                if(isset($data)){
                            ?>
                                <input type="hidden" name="post_id" value="<?= $data['id'] ?>">
                                <button type="submit" name="update" class="btn btn-primary">Update post</button>
                            <?php } else { ?>
                                <button type="submit" name="submit" class="btn btn-primary">Add post</button>
                            <?php } ?>
                        </form>
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
<script type="text/javascript">
        function get_chid_cat(cat_id){
            $.ajax({
                url: 'controllers/get_sub_cat.php',
                type: 'post',
                data: {cat_id : cat_id },
                success: function(response){
                    $('#child_cat').html(response);
                }
            });
        }
</script>