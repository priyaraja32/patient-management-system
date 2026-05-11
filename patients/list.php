<?php

include("../config/db.php");
include("../includes/header.php");

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

$page = $_GET['page'] ?? 1;

$limit = 5;
$offset = ($page - 1) * $limit;

$order = "ORDER BY patients.id DESC";

if($sort == "age_asc"){
    $order = "ORDER BY age ASC";
}

elseif($sort == "age_desc"){
    $order = "ORDER BY age DESC";
}

elseif($sort == "name_asc"){
    $order = "ORDER BY patient_name ASC";
}

elseif($sort == "name_desc"){
    $order = "ORDER BY patient_name DESC";
}

$where = "";

if($search != ""){

    $where = "WHERE patient_name LIKE '%$search%'
    OR diagnosis LIKE '%$search%'";
}

$totalQuery = mysqli_query($conn,
"SELECT COUNT(*) as total FROM patients $where");

$totalRow = mysqli_fetch_assoc($totalQuery);

$totalRecords = $totalRow['total'];

$totalPages = ceil($totalRecords / $limit);

$query = "SELECT patients.*,
doctors.doctor_name

FROM patients

LEFT JOIN doctors
ON patients.doctor_id = doctors.id

$where

$order

LIMIT $offset,$limit";

$result = mysqli_query($conn,$query);

?>

<div class="custom-card p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="page-title">
    Patients Dashboard
</h2>

<p class="text-muted">
    Manage patient records efficiently
</p>

</div>

<a href="create.php" class="btn btn-primary">

<i class="fa-solid fa-plus"></i>
 Add Patient

</a>

</div>

<form method="GET">

<div class="row mb-4">

<div class="col-md-5 mb-2">

<input type="text"
name="search"
class="form-control"
placeholder="Search by patient name or diagnosis"
value="<?php echo $search; ?>">

</div>

<div class="col-md-4 mb-2">

<select name="sort" class="form-select">

<option value="">Sort By</option>

<option value="age_asc">Age ASC</option>
<option value="age_desc">Age DESC</option>

<option value="name_asc">Name A-Z</option>
<option value="name_desc">Name Z-A</option>

</select>

</div>

<div class="col-md-3 mb-2">

<button class="btn btn-primary w-100">

<i class="fa-solid fa-magnifying-glass"></i>
 Search

</button>

</div>

</div>

</form>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>
<th>Patient</th>
<th>Contact</th>
<th>Age</th>
<th>Gender</th>
<th>Diagnosis</th>
<th>Doctor</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td>
    <?php echo $row['id']; ?>
</td>

<td>

<div class="fw-bold">
    <?php echo $row['patient_name']; ?>
</div>

<div class="text-muted small">
    <?php echo $row['email']; ?>
</div>

</td>

<td>
    <?php echo $row['phone']; ?>
</td>

<td>
    <?php echo $row['age']; ?>
</td>

<td>

<?php if($row['gender']=="Male"){ ?>

<span class="badge bg-primary badge-gender">
    Male
</span>

<?php } else { ?>

<span class="badge bg-danger badge-gender">
    Female
</span>

<?php } ?>

</td>

<td>
    <?php echo $row['diagnosis']; ?>
</td>

<td>
    <?php echo $row['doctor_name']; ?>
</td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">

<i class="fa-solid fa-pen"></i>

</a>

<a href="delete.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete Patient?')">

<i class="fa-solid fa-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php if($page > 1){ ?>

<li class="page-item">

<a class="page-link"
href="?page=<?php echo $page-1; ?>">

Previous

</a>

</li>

<?php } ?>

<?php if($page < $totalPages){ ?>

<li class="page-item">

<a class="page-link"
href="?page=<?php echo $page+1; ?>">

Next

</a>

</li>

<?php } ?>

</ul>

</nav>

</div>

<?php include("../includes/footer.php"); ?>