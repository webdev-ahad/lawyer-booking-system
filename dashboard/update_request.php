<?php
include("config/db_connection.php");
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "admin") {
    header("Location: index.php");
    exit();
}
// getting id and status validation
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    exit("Invalid request");
}
// getting id and status

$request_id = $_GET['id'];
$status = $_GET['status'];

// if the action is already done maybe bug 
if ($status !== "approved" && $status !== "rejected") {
    $_SESSION['swal'] = ["title"=>"Invalid status","message"=>"Invalid status","type"=>"error"];
    header("Location: lawyer_requests.php");
    exit();
}

// check if request exist then fetch it
$check_query = "SELECT * FROM lawyer_requests WHERE request_id = $request_id";
$check = mysqli_query($connection, $check_query);

if (!$check || mysqli_num_rows($check) == 0) {
    $_SESSION['swal'] = ["title"=>"Request not found","message"=>"Request not found","type"=>"error"];
    header("Location: lawyer_requests.php");
    exit();
}

$row = mysqli_fetch_assoc($check);

// update query main (changing status from pending to 'action')
$update_query = "UPDATE lawyer_requests SET request_status = '$status' WHERE request_id = $request_id";
$update = mysqli_query($connection, $update_query);

if (!$update) {
    $_SESSION['swal'] = ["title"=>"Update failed","message"=>"Update failed","type"=>"error"];
    header("Location: lawyer_requests.php");
    exit();
}

// now approve and copy data to lawyer_profiles
if ($status === "approved") {

    $u_id = $row['user_id'];

    $copy_sql = "INSERT INTO lawyer_profiles (user_id, lawyer_bio, lawyer_city, lawyer_address, lawyer_experience_years, lawyer_bar_number, lawyer_consultation_fee, lawyer_profile_photo) SELECT user_id, request_bio, request_city, request_address, request_experience_years, request_bar_number, request_consultation_fee, request_profile_photo FROM lawyer_requests WHERE request_id = $request_id AND NOT EXISTS (SELECT 1 FROM lawyer_profiles WHERE user_id = $u_id)";
    $copy_result = mysqli_query($connection, $copy_sql);

    if (!$copy_result) {
        $_SESSION['swal'] = ["title"=>"Insert failed","message"=>"Insert failed","type"=>"error"];
        header("Location: lawyer_requests.php");
        exit();
    }

    $update_user_role = mysqli_query($connection,
        "UPDATE users SET user_role = 'lawyer' WHERE user_id = $u_id"
    );

    if (!$update_user_role) {
        $_SESSION['swal'] = ["title"=>"Update failed","message"=>"Update failed","type"=>"error"];
        header("Location: lawyer_requests.php");
        exit();
    }

}

header("Location: lawyer_requests.php");
exit();
?>