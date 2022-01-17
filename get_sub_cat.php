<?php
	include '../includes/db.php';
	include '../includes/constants.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");
	if(isset($_POST['cat_id'])){
		$cat_id = $_POST['cat_id'];
		$query = mysqli_query($conn,"SELECT * FROM post_sub_category WHERE status = '1' and parent_category_id='".$cat_id."'");
        if(mysqli_num_rows($query) > 0){
        	$options = '';
            while($cat = mysqli_fetch_array($query)){
            	$options .= '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
            }
            echo $options;
        } else {
        	echo "<option>--Select--</option>";
        }
	}


    if(isset($_POST['cat_id_api'])){
        $response  = array();
        $cat_id = $_POST['cat_id_api'];
        $query = mysqli_query($conn,"SELECT * FROM post_sub_category WHERE status = '1' and parent_category_id='".$cat_id."'");
        if(mysqli_num_rows($query) > 0){
            $options = '';
            $response['status'] = 1;
            $data = array();
            while($cat = mysqli_fetch_array($query)){
                $res['cat_id'] = $cat['id'];
                $res['cat_name'] = $cat['name'];
                array_push($data, $res);
            }
            $response['message'] = 'Records found';
            $response['categories'] = $data;
        } else {
            $response['status'] = 0;
            $response['message'] = 'No record found';
     
        }
        echo json_encode($response);
    }
?>