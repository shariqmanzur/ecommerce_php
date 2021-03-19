<?php 
require('include/header.inc.php');

$categories_id = "";
$name = "";
$mrp = "";
$price = "";
$qty = "";
$image = "";
$short_desc = "";
$long_desc = "";
$meta_title = "";
$meta_desc = "";
$meta_keyword = "";
$status = "";

$msg = "";
$image_required = 'required';

if(isset($_GET['id']) && $_GET['id'] != ''){
    $image_required = '';
    $id = get_safe_value($con,$_GET['id']);
    $res = mysqli_query($con,"select * from product where id = '$id'");
    $check = mysqli_num_rows($res);
    if($check>0){

        $row = mysqli_fetch_assoc($res);
        $categories_id = $row['categories_id'];
        $name = $row['name'];
        $mrp = $row['mrp'];
        $price = $row['price'];
        $qty = $row['qty'];
        $image = $row['image'];
        $short_desc = $row['short_desc'];
        $long_desc = $row['long_desc'];
        $meta_title = $row['meta_title'];
        $meta_desc = $row['meta_desc'];
        $meta_keyword = $row['meta_keyword'];
    }
    else{
        header('location:product.php');
        die();
    }
}

if(isset($_POST['submit'])){
    $categories_id = get_safe_value($con, $_POST['categories_id']);
    $name = get_safe_value($con, $_POST['name']);
    $mrp = get_safe_value($con, $_POST['mrp']);
    $price = get_safe_value($con, $_POST['price']);
    $qty = get_safe_value($con, $_POST['qty']);
    $short_desc = get_safe_value($con, $_POST['short_desc']);
    $long_desc = get_safe_value($con, $_POST['long_desc']);
    $meta_title = get_safe_value($con, $_POST['meta_title']);
    $meta_desc = get_safe_value($con, $_POST['meta_desc']);
    $meta_keyword = get_safe_value($con, $_POST['meta_keyword']);

    $res = mysqli_query($con,"select * from product where name = '$name'");
    $check = mysqli_num_rows($res);
    if($check>0){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $getData = mysqli_fetch_assoc($res);
            if($id == $getData['id']){

            }
            else{
                $msg = "Product already exist";
            }
        }else{
            $msg = "Product already exist";
        }
    }

    if($_FILES['image']['type'] !='' && ($_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/jpg' && $_FILES['image']['type'] != 'image/jpeg')){
        
        $msg = "Please select only png, jpg and jpeg formate";
    }
    
    if($msg == ''){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            if($_FILES['image']['name'] != ''){
                $image = rand(1111111111,9999999999).'_'.$_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'],PRODUCT_IMAGE_SERVER_PATH.$image);
                $update_sql = "update product set categories_id = '$categories_id', name = '$name', mrp = '$mrp', price = '$price', qty = '$qty', short_desc = '$short_desc', long_desc = '$long_desc', meta_title = '$meta_title', meta_desc = '$meta_desc', meta_keyword = '$meta_keyword', image = '$image' where id = '$id'";
            }else{
                $update_sql = "update product set categories_id = '$categories_id', name = '$name', mrp = '$mrp', price = '$price', qty = '$qty', short_desc = '$short_desc', long_desc = '$long_desc', meta_title = '$meta_title', meta_desc = '$meta_desc', meta_keyword = '$meta_keyword' where id = '$id'";
            }
            mysqli_query($con,$update_sql);
        }
        else{
            $image = rand(1111111111,9999999999).'_'.$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],PRODUCT_IMAGE_SERVER_PATH.$image);
            mysqli_query($con,"insert into product(categories_id, name, mrp, price, qty, image, short_desc, long_desc, meta_title, meta_desc, meta_keyword, status) values('$categories_id', '$name', '$mrp', '$price', '$qty', '$image', '$short_desc', '$long_desc', '$meta_title', '$meta_desc', '$meta_keyword', '1')");
        }
        header('location:product.php');
        die();
    }
}

?>

<div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong>Product</strong><small> Form</small></div>
                        <div class="card-body card-block">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="categories" class=" form-control-label">Categories</label>
                                    <select class="form-control" name="categories_id">
                                    <?php
                                        $res = mysqli_query($con, "select id, categories from categories");
                                            while($row = mysqli_fetch_assoc($res)){
                                                if($row['id'] == $categories_id){
                                                    echo "<option selected value=".$row['id'].">".$row['categories']."</option>";
                                                }else{
                                                    echo "<option value=".$row['id'].">".$row['categories']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="name" class=" form-control-label">Prouct Name</label>
                                    <input type="text" name="name" value="<?php echo $name;?>" id="name" placeholder="Enter your product name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="mrp" class=" form-control-label">MRP</label>
                                    <input type="text" name="mrp" value="<?php echo $mrp;?>" id="mrp" placeholder="Enter product mrp" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="price" class=" form-control-label">Price</label>
                                    <input type="text" name="price" value="<?php echo $price;?>" id="price" placeholder="Enter product price" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="qty" class=" form-control-label">Quantity</label>
                                    <input type="text" name="qty" value="<?php echo $qty;?>" id="qty" placeholder="Enter product quantity" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="image" class=" form-control-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control"<?php echo $image_required ?>>
                                </div>

                                <div class="form-group">
                                    <label for="short_desc" class=" form-control-label">Short Description</label>
                                    <textarea name="short_desc" id="short_desc" placeholder="Enter product short description" class="form-control" required><?php echo $short_desc;?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="long_desc" class=" form-control-label">Description</label>
                                    <textarea name="long_desc" id="long_desc" placeholder="Enter product description" class="form-control" required><?php echo $long_desc;?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_title" class=" form-control-label">Meta Title</label>
                                    <textarea name="meta_title" id="meta_title" placeholder="Enter product meta title" class="form-control" required><?php echo $meta_title;?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_desc" class=" form-control-label">Meta Description</label>
                                    <textarea name="meta_desc" id="meta_desc" placeholder="Enter product meta description" class="form-control" required><?php echo $meta_desc;?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keyword" class=" form-control-label">Meta Keyword</label>
                                    <textarea name="meta_keyword" id="meta_keyword" placeholder="Enter product meta keyword" class="form-control" required><?php echo $meta_keyword;?></textarea>
                                </div>
                               

                                <button id="categories-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
                                    Submit
                                </button>
                                <div class="field_error"><?php echo $msg; ?></div>
                            </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

<?php 
require('include/footer.inc.php');
?>