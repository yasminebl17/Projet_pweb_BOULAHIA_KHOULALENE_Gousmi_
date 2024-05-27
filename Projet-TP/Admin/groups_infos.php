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

// Récupérer tous les groupes avec leurs leaders
$stmt = $pdo->query('
    SELECT g.id as group_id, g.name as group_name, u.username as leader_username
    FROM groups g
    JOIN users u ON g.leader_id = u.id;
');
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/groups_infos1.css">
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
                    <a href="index.php">
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
                    <a href="#">
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

                      <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>All Groups</h2>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Group Name</td>
                                <td>Leader</td>
                                <td>Modify</td>
                            </tr>
                        </thead>

                        <tbody>
                        
                        <?php foreach ($groups as $group): ?>
                        
                            <tr>
                                <td> <?php echo htmlspecialchars($group['group_name']); ?> </td>
                                <td> <?php echo htmlspecialchars($group['leader_username']);   ?></td>     
                                <td><a href="forms/modifier_group.php?group_id=<?php echo $group['group_id']; ?>" class="btn">Modify</a></td>
                            </tr>
                        <?php endforeach; ?>
                     
                        </tbody>
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