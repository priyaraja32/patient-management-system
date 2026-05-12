<?php

include("../config/db.php");

$id = $_GET['id'] ?? '';

if(empty($id)){

    header("Location:list.php");
    exit;

}

$stmt = $conn->prepare(
"SELECT * FROM patients WHERE id=?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();



if(!$row){

    header("Location:list.php");
    exit;

}

$error = "";
$success = "";

if(isset($_POST['update'])){

    $name = trim($_POST['patient_name'] ?? '');

    $phone = trim($_POST['phone'] ?? '');

    $age = trim($_POST['age'] ?? '');

    $gender = trim($_POST['gender'] ?? '');

    $diagnosis = trim($_POST['diagnosis'] ?? '');



    if(empty($name) || empty($phone)
    || empty($age) || empty($gender)
    || empty($diagnosis)){

        $error = "All fields are required";

    }

    elseif(!preg_match('/^[0-9]{10}$/', $phone)){

        $error = "Phone number must contain 10 digits";

    }

    else{

       

        $update = $conn->prepare(

        "UPDATE patients SET

        patient_name=?,
        phone=?,
        age=?,
        gender=?,
        diagnosis=?

        WHERE id=?"

        );

        $update->bind_param(

        "ssissi",

        $name,
        $phone,
        $age,
        $gender,
        $diagnosis,
        $id

        );

   

        if($update->execute()){

            $success = "Patient Updated Successfully";

            

            $stmt = $conn->prepare(
            "SELECT * FROM patients WHERE id=?"
            );

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            $row = $result->fetch_assoc();

        }

        else{

            $error = "Update Failed";

        }
    }
}

include("../includes/header.php");

?>

<div class="row justify-content-center mt-4">

<div class="col-md-8">

<div class="custom-card p-4">

<h2 class="page-title text-center mb-4">

    Edit Patient

</h2>

// ERROR MESSAGE

<?php if($error != ""){ ?>

<div class="alert alert-danger">

    <?php echo $error; ?>

</div>

<?php } ?>
>

// SUCCESS MESSAGE
<?php if($success != ""){ ?>

<div class="alert alert-success">

    <?php echo $success; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="fw-bold">

    Patient Name

</label>

<input
type="text"
name="patient_name"
class="form-control"
value="<?php echo $row['patient_name']; ?>">

</div>

<div class="mb-3">

<label class="fw-bold">

    Phone

</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo $row['phone']; ?>">

</div>

<div class="mb-3">

<label class="fw-bold">

    Age

</label>

<input
type="number"
name="age"
class="form-control"
value="<?php echo $row['age']; ?>">

</div>

<div class="mb-3">

<label class="fw-bold">

    Gender

</label>

<select name="gender" class="form-select">

<option value="Male"

<?php
if($row['gender']=="Male"){
    echo "selected";
}
?>

>

Male

</option>

<option value="Female"

<?php
if($row['gender']=="Female"){
    echo "selected";
}
?>

>

Female

</option>

</select>

</div>

<div class="mb-3">

<label class="fw-bold">

    Diagnosis

</label>

<textarea
name="diagnosis"
class="form-control"
rows="4"><?php echo $row['diagnosis']; ?></textarea>

</div>

<div class="text-center mt-4">

<button
type="submit"
name="update"
class="btn btn-primary px-5">

    Update Patient

</button>

<a href="list.php"
class="btn btn-secondary">

    Back

</a>

</div>

</form>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>