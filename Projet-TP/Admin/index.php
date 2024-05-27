<?php


require '../db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();

// Récupérer toutes les tâches terminées
$stmt = $pdo->query('SELECT tasks.*, users.username AS assigned_to FROM tasks JOIN users ON tasks.assigned_to = users.id WHERE tasks.completed = 1');
$completed_tasks = $stmt->fetchAll();

// Récupérer tous les groupes
$stmt = $pdo->query('SELECT * FROM groups');
$groups = $stmt->fetchAll();

// Récupérer le nombre d'utilisateurs
$stmt = $pdo->query('SELECT COUNT(*) AS user_count FROM users');
$user_nbr = $stmt->fetch(PDO::FETCH_ASSOC);

$user_count = $user_nbr['user_count'];

// Récupérer le nombre d'utilisateurs Leader 
$stmt = $pdo->query("SELECT COUNT(*) AS leader_count FROM users WHERE role= 'leader'");
$leader_nbr = $stmt->fetch(PDO::FETCH_ASSOC);

$leader_count = $leader_nbr['leader_count']; 

// Récupérer le nombre de Groupes
$stmt = $pdo->query('SELECT COUNT(*) AS grp_count FROM groups');
$group_nbr = $stmt->fetch(PDO::FETCH_ASSOC);

$group_count = $group_nbr['grp_count']; 

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
                        <span class="title">Admin</span>
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
                    <a href="forms/ajout_groupe.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Ajouter un groupe</span>
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
                        <div class="numbers"><?php echo $user_count ?></div>
                        <div class="cardName">Users</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="person-outline"></ion-icon>
                       
                    </div>
                </div></a>

                <a href="leaders_infos.php" style="text-decoration: none;"><div class="card">
                    <div>
                        <div class="numbers"><?php echo $leader_count ?></div>
                        <div class="cardName">Leaders</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div></a>

                <a href="groups_infos.php" style="text-decoration: none;"><div class="card">
                    <div>
                        <div class="numbers"> <?php echo $group_count ?></div>
                        <div class="cardName">Groups</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div></a> 

             
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
                        <?php foreach ($completed_tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td><?php echo htmlspecialchars($task['assigned_to']); ?> </td>
                                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                                <td><span class="status delivered">Completed</span></td>
                            </tr>
                            <?php endforeach; ?>
                         
                           
                          
                        
                        </tbody>
                    </table>
                </div>

                <!-- ================= New Customers ================ -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Recent Users</h2>
                        <a href="users_infos.php" class="btn">View All</a>
                    </div>

                    <table>
                      <?php foreach ($users as $user): ?>
                        <tr>

                            <td width="60px">
                                <div class="imgBx"><img src="assets/imgs/customer02.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4><?php echo htmlspecialchars($user['username']); ?> <br> <span>(<?php echo htmlspecialchars($user['role']); ?>)</span></h4>
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