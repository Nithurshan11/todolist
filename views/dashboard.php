<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

require_once "../config/database.php";
require_once "../models/Task.php";

$db=(new Database())->connect();
$taskModel=new Task($db);
$tasks=$taskModel->getAll($_SESSION['user_id']);

$today=date("Y-m-d");
$total=0; $completed=0; $low=0; $medium=0; $high=0;

while($row=$tasks->fetch_assoc()){
    $total++;
    if($row['status']=="Completed") $completed++;
    if($row['priority']=="Low") $low++;
    if($row['priority']=="Medium") $medium++;
    if($row['priority']=="High") $high++;
}
$tasks->data_seek(0); // reset pointer
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SmartTask Dashboard</title>
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/script.js"></script>
</head>
<body>
<div class="topbar">
    <h2>Welcome, <?=$_SESSION['username']?> 👋</h2>
    <p>Let’s tackle today’s tasks 🚀</p>
    <div style="margin-top:10px;">
        <button onclick="toggleDark()">🌙 Dark Mode</button>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="dashboard-grid">
    <!-- ADD TASK FORM -->
    <div class="card">
        <h3>Add Task</h3>
        <form action="../controllers/TaskController.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="task" placeholder="Task Name" required>
            <input type="date" name="date" required>
            <select name="priority">
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
            </select>
            <button>Add Task</button>
        </form>
    </div>

    <!-- TASK LIST -->
    <div class="card">
        <h3>Your Tasks</h3>
        <?php while($row=$tasks->fetch_assoc()): ?>
            <?php
            $class="";
            if($row['due_date']<$today) $class="overdue";
            if($row['due_date']==$today) $class="today";
            ?>
            <div class="task <?=$class?>">
                <div>
                    <b><?=$row['task_name']?></b> 
                    <span class="badge <?=$row['priority']?>"><?=$row['priority']?></span>
                    <?php if($row['priority']=="High" && $row['due_date']==$today) echo "🔥"; ?>
                    <small>(<?=$row['due_date']?>)</small>
                </div>
                <div>
                    <?php if($row['status']=="Pending"): ?>
                        <a href="../controllers/TaskController.php?complete=<?=$row['id']?>" title="Mark Completed">✔</a>
                    <?php endif; ?>
                    <a href="../controllers/TaskController.php?delete=<?=$row['id']?>" title="Delete">❌</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- SMART ANALYTICS -->
    <div class="card">
        <h3>Productivity Chart</h3>
        <canvas id="statusChart"></canvas>
        <canvas id="priorityChart" style="margin-top:20px;"></canvas>
    </div>
</div>

<script>
const ctxStatus = document.getElementById("statusChart").getContext('2d');
new Chart(ctxStatus,{
    type:"doughnut",
    data:{
        labels:["Completed","Pending"],
        datasets:[{
            data:[<?=$completed?>, <?=$total-$completed?>],
            backgroundColor:["#28a745","#dc3545"]
        }]
    },
    options:{responsive:true,plugins:{legend:{position:'bottom'}}}
});

const ctxPriority = document.getElementById("priorityChart").getContext('2d');
new Chart(ctxPriority,{
    type:"bar",
    data:{
        labels:["Low","Medium","High"],
        datasets:[{
            label:"Task Priority Distribution",
            data:[<?=$low?>,<?=$medium?>,<?=$high?>],
            backgroundColor:["#6c757d","#ffc107","#fd7e14"]
        }]
    },
    options:{responsive:true,plugins:{legend:{display:false}}}
});
</script>

</body>
</html>