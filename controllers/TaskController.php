<?php
// Only start session once
session_start();

// Secure check: redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/Task.php";

$db = (new Database())->connect();
$task = new Task($db);

/* -------------------------
   ADD TASK
------------------------- */
if (isset($_POST['action']) && $_POST['action'] == "add") {

    $taskName = htmlspecialchars($_POST['task']);
    $date = $_POST['date'];
    $priority = $_POST['priority'];

    $task->add($_SESSION['user_id'], $taskName, $date, $priority);

    header("Location: ../views/dashboard.php");
    exit();
}

/* -------------------------
   COMPLETE TASK
------------------------- */
if (isset($_GET['complete'])) {
    $id = intval($_GET['complete']);
    $task->complete($id);  // ✅ works now
    header("Location: ../views/dashboard.php");
    exit();
}

/* -------------------------
   DELETE TASK
------------------------- */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $task->delete($id);
    header("Location: ../views/dashboard.php");
    exit();
}
?>