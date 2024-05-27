<?php
require '../db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est un chef de groupe
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les groupes dont l'utilisateur est le chef
$stmt = $pdo->prepare('SELECT * FROM groups WHERE leader_id = ?');
$stmt->execute([$user_id]);
$groups = $stmt->fetchAll();

// Récupérer les tâches assignées par le chef de groupe
$stmt = $pdo->prepare('SELECT tasks.*, users.username AS assigned_to FROM tasks JOIN users ON tasks.assigned_to = users.id WHERE tasks.assigned_by = ?');
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();

// Récupérer le nombre d'utilisateurs
$stmt = $pdo->query('SELECT COUNT(*) AS user_count FROM users');
$user_nbr = $stmt->fetch(PDO::FETCH_ASSOC);

$user_count = $user_nbr['user_count'];

// Récupérer le nombre de Tasks
$stmt = $pdo->query('SELECT COUNT(*) AS task_count FROM tasks');
$task_nbr = $stmt->fetch(PDO::FETCH_ASSOC);

$task_count = $task_nbr['task_count'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_id = $_POST['group_id'];
    $task_description = $_POST['task_description'];
    $assigned_to = $_POST['assigned_to'];
    $deadline = $_POST['deadline'];

    // Vérifier que la date limite n'est pas dans le passé
    $current_date = date('Y-m-d');
    if ($deadline < $current_date) {
        echo "La date limite ne peut pas être dans le passé.";
    } else {
        $stmt = $pdo->prepare('INSERT INTO tasks (group_id, description, assigned_by, assigned_to, deadline) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$group_id, $task_description, $user_id, $assigned_to, $deadline]);

        header('Location: leader_dashboard.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style1.css">
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="title">Leader</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="forms/add_task.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Add Task</span>
                    </a>
                </li>

    
            
                <li>
                    <a href="../login.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>

                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <a href="users_infos.php" style="text-decoration: none;">
                    <div class="card">
                    <div>
                        <div class="numbers"><?php echo $user_count?></div>
                        <div class="cardName">Users</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="person-outline"></ion-icon>
                       
                    </div>
                </div></a>

              

            
                <div class="card" style="height: 131px;">
                    <div>
                        <div class="numbers"><?php echo $task_count?></div>
                        <div class="cardName">Tasks</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="hourglass-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Completed Tasks</h2>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Description</td>
                                <td>Assigned To</td>
                                <td>Deadline</td>
                                <td>status</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td><?php echo htmlspecialchars($task['assigned_to']); ?></td>
                                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                                <td><span class="status delivered">Delivered</span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- ================= New Customers ================ -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Your Groups</h2>
                    </div>

                    <table>
                    <?php foreach ($groups as $group): ?>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="assets/imgs/customer02.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4><?php echo htmlspecialchars($group['name']); ?></h4>
                            </td>
                        
                        </tr>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>