<?php
    include('connect.php');
    

    $name = null;
    $department = null;
    $salary = null;
    $supervisor = null;
    $gender = null;
    $image = null;

    $btcore = "add";
    // print_r($_FILES);
    // INSERT
    if(isset($_POST["submit"])) {
        $name = $_POST['name'];
        $department = $_POST['department'];
        $salary = $_POST['salary'];
        $supervisor = $_POST['supervisor'];
        $gender = $_POST['gender'];
        

        // Image INSERT
        $image_name = rand(0,255) . rand(0,255) . $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $location = "./upload/" . $image_name;
        move_uploaded_file($image_tmp, $location);

        $insert_query = "INSERT INTO `employees` VALUES (NULL,'$name', $salary, '$gender', '$department', '$supervisor', '$image_name')";
        $insert = mysqli_query($connection, $insert_query);
        header("location: index.php");
    }

    // Read
    $idx = 0;
    $read_query = "SELECT * FROM `employees`";
    $read = mysqli_query($connection, $read_query);

    // Delete
    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        // Delete image
        $select_emp_image_query = "SELECT * FROM `employees` WHERE id = $id";
        $select_emp_image = mysqli_query($connection, $select_emp_image_query);
        $row_delete = mysqli_fetch_assoc($select_emp_image);
        $img_delete = $row_delete['image'];
        // echo $img_delete;
        unlink("./upload/$img_delete");

        $delete_query = "DELETE FROM `employees` where id = $id";
        $delete = mysqli_query($connection, $delete_query);
        
        header("location: index.php");
    }

    // Edit

    if(isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $get_query = "SELECT * FROM `employees` where id = $id";
        $get = mysqli_query($connection, $get_query);
        $emp = mysqli_fetch_assoc($get);
        $name = $emp['name'];
        $department = $emp['department'];
        $salary = $emp['salary'];
        $supervisor = $emp['supervisor'];
        $gender = $emp['gender'];
        $image = $emp['image'];
        $btcore = "edit";

        if(isset($_POST['update'])) {
            $name = $_POST['name'];
            $department = $_POST['department'];
            $salary = $_POST['salary'];
            $supervisor = $_POST['supervisor'];
            $gender = $_POST['gender'];
            if($_FILES['image']['name'] == null) {
                $image_name = $image;
            }
            else {
                $image_name = rand(0,255) . rand(0,255) . $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $location = "./upload/" . $image_name;
                move_uploaded_file($image_tmp, $location);
                unlink("./upload/$image");
            }
             
            $update_query = "UPDATE `employees` SET `name` = '$name', `salary` = $salary, `gender` = '$gender', `department` = '$department', `supervisor` = '$supervisor', `image` = '$image_name' where id = $id";
            $update = mysqli_query($connection, $update_query);
            $btcore = "add";
            header("location: index.php");
        }
    }
    if(isset($_GET['color'])) {
        $color = $_GET['color'];
        $updatecolor = "UPDATE theme SET color = '$color' WHERE id = 1";
        $update = mysqli_query($connection, $updatecolor);
    }
    $select_theme = "SELECT * FROM `theme` where id = 1";
    $theme = mysqli_query($connection, $select_theme);
    $rowtheme = mysqli_fetch_assoc($theme);
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <?php if($rowtheme['color'] != "dark") : ?>
    <link rel="stylesheet" href="./main.css">
    <?php endif; ?>
</head>
<body>
    <?php if ($rowtheme['color'] == 'dark') : ?>
    <a href="?color=light" class="btn btn-dark">Light Mode</a>
    <?php else : ?>
    <a href="?color=dark" class="btn btn-light">Dark Mode</a>
    <?php endif; ?>
    <div class="container col-6 py-5">
        <div class="row justify-content-center mt-5">
            <div class="col-12">
                <div class = "card bg-dark text-light">
                    <h2 class = "text-center">Add Employee</h2>
                    <div class="card-body">
                        <form method = "POST" enctype="multipart/form-data">
                            <div class = "form-group mb-3">
                                <label for="name" class="form">Name</label>
                                <input type="text" value = "<?=$name?>" class="form-control" name = "name" id = "name">
                            </div>
                            <div class = "form-group mb-3">
                                <label for="department" class="form">Department</label>
                                <input type="text" value = "<?=$department?>" class="form-control" name = "department" id = "department">
                            </div>
                            <div class = "form-group mb-3">
                                <label for="salary" class="form">Salary</label>
                                <input type="text" value = "<?=$salary?>" class="form-control" name = "salary" id = "salary">
                            </div>
                            <div class = "form-group mb-3">
                                <label for="supervisor" class="form">supervisor</label>
                                <input type="text" value = "<?=$supervisor?>" class="form-control" name = "supervisor" id = "supervisor">
                            </div>
                            <div class = "form-group mb-3">
                                <label for="gender" class="form">Gender</label>
                                <select name="gender" id="gender" class = "form-select">
                                    <?php if($gender == "male"):?>
                                        <option selected value="male">Male</option>
                                    <option value="female">female</option>
                                    <?php else : ?>
                                        <option value="male">Male</option>
                                    <option selected value="female">female</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for = "">Employee Image : <?php if($image != null) : ?><img width = 50 src="./upload/<?=$image?>" alt=""> <?php endif; ?></label>
                                <input type="file" accept = "image/*" name = "image" class = "form-control">
                            </div>
                            <div class="text-center form-group mt-5">
                                <?php if($btcore == "add") : ?>
                                    <button name = "submit" class = "btn btn-primary">Add Employee</button>
                                <?php else : ?>
                                    <button name = "update" class = "btn btn-warning">Edit Employee</button>
                                <?php endif ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <hr class = "mt-3">
            <div class="col-12">
                <table class = "table table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>Gender</th>
                        <th>Department</th>
                        <th>Supervisor</th>
                        <th>Emp Image</th>
                        <th colspan="2">Action</th>
                    </tr>
                    <?php foreach($read as $item) : ?>
                        <tr>
                            <td><?= ++$idx?></td>
                            <td><?= $item['name']?></td>
                            <td><?= $item['salary']?></td>
                            <td><?= $item['gender']?></td>
                            <td><?= $item['department']?></td>
                            <td><?= $item['supervisor']?></td>
                            <td><img width = 70 src="./upload/<?=$item['image']?>" alt=""></td>
                            <td><a href = "?edit=<?=$item['id']?>" class = "btn btn-warning">Edit</a></td>
                            <td><a href = "?delete=<?=$item['id']?>" class = "btn btn-danger">Delete</a></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>