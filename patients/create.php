<?php

include("../config/db.php");

$error = "";
$success = "";

if(isset($_POST['submit'])){

$name = trim($_POST['patient_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$age = trim($_POST['age'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$diagnosis = trim($_POST['diagnosis'] ?? '');
$doctor_id = trim($_POST['doctor_id'] ?? '');

    if(empty($name) || empty($email) || empty($phone)
    || empty($age) || empty($gender) || empty($diagnosis)){

        $error = "All fields are required";

    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $error = "Invalid Email";

    }

    elseif(!preg_match('/^[0-9]{10}$/', $phone)){

        $error = "Phone must contain 10 digits";

    }

    else{


        $check = $conn->prepare(
        "SELECT id FROM patients WHERE email=?");

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();

        if($result->num_rows > 0){

            $error = "Email already exists";

        }

        else{

            // INSERT USING BIND PARAMS

            $stmt = $conn->prepare(
            "INSERT INTO patients
            (patient_name,email,phone,age,gender,diagnosis,doctor_id)

            VALUES

            (?,?,?,?,?,?,?)");

            $stmt->bind_param(
            "sssissi",
            $name,
            $email,
            $phone,
            $age,
            $gender,
            $diagnosis,
            $doctor_id
            );

            if($stmt->execute()){

                $success = "Patient Added Successfully";

            }

            else{

                $error = "Insert Failed";

            }
        }
    }
}

include("../includes/header.php");

?>

<div class="row justify-content-center">

<div class="col-md-8">

<div class="custom-card p-4">

<div class="text-center mb-4">

<h2 class="page-title">
    Add New Patient
</h2>

<p class="text-muted">
    Hospital Patient Registration Form
</p>

</div>

<?php if($error){ ?>

<div class="alert alert-danger">
    <?php echo $error; ?>
</div>

<?php } ?>

<?php if($success){ ?>

<div class="alert alert-success">
    <?php echo $success; ?>
</div>

<?php } ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="fw-semibold">Patient Name</label>

<input type="text"
name="patient_name"
class="form-control"
value="<?php echo htmlspecialchars($name ?? ''); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="fw-semibold">Email</label>

<input type="email"
name="email"
class="form-control"
value="<?php echo htmlspecialchars($email ?? ''); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="fw-semibold">Phone</label>

<input type="text"
name="phone"
class="form-control"
value="<?php echo htmlspecialchars($phone ?? ''); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="fw-semibold">Age</label>

<input type="number"
name="age"
class="form-control"
value="<?php echo htmlspecialchars($age ?? ''); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="fw-semibold">Gender</label>

<select name="gender" class="form-select">

<option value="">Select Gender</option>

<option value="Male"
<?php if(($gender ?? '') == "Male") echo "selected"; ?>>
Male
</option>

<option value="Female"
<?php if(($gender ?? '') == "Female") echo "selected"; ?>>
Female
</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="fw-semibold">Doctor</label>

<select name="doctor_id" class="form-select">

<option value="">Select Doctor</option>

<?php

$doctorQuery = mysqli_query($conn,
"SELECT * FROM doctors");

while($doctor = mysqli_fetch_assoc($doctorQuery)){

?>

<option value="<?php echo $doctor['id']; ?>"

<?php
if(($doctor_id ?? '') == $doctor['id']){
    echo "selected";
}
?>

>

<?php echo $doctor['doctor_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-12 mb-3">

<label class="fw-semibold">Diagnosis</label>

<textarea
name="diagnosis"
rows="4"
class="form-control"><?php echo htmlspecialchars($diagnosis ?? ''); ?></textarea>

</div>

<div class="text-center mt-3">

<button type="submit"
name="submit"
class="btn btn-primary">

<i class="fa-solid fa-floppy-disk"></i>
 Save Patient

</button>

<a href="list.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</form>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>