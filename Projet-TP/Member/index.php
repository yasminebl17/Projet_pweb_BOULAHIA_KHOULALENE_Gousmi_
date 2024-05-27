<?php
require '../db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est un membre
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les groupes dont l'utilisateur est membre
$stmt = $pdo->prepare('SELECT groups.* FROM groups JOIN group_members ON groups.id = group_members.group_id WHERE group_members.user_id = ?');
$stmt->execute([$user_id]);
$groups = $stmt->fetchAll();

// Récupérer les tâches assignées à l'utilisateur
$stmt = $pdo->prepare('SELECT tasks.*, users.username AS assigned_by FROM tasks JOIN users ON tasks.assigned_by = users.id WHERE tasks.assigned_to = ?');
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];

    // Marquer la tâche comme terminée
    $stmt = $pdo->prepare('UPDATE tasks SET completed = 1 WHERE id = ?');
    $stmt->execute([$task_id]);

    header('Location: index.php');
    exit();
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
                        <span class="title">Member</span>
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
                


                <a href="groups_infos.php" style="text-decoration: none;"><div class="card">
                    <div>
                        <div class="numbers"><?php 
                        if(isset($_SESSION['grp_cpt'])){
                            echo $_SESSION['grp_cpt'] ;
                        }else{
                            echo '0';
                        }
                            
                        ?>
                        </div>
                        <div class="cardName">Groups</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div></a> 

                <div class="card" style="height: 131px;">
                    <div>
                        <div class="numbers"><?php 
                        if(isset($_SESSION['task_cpt'])){
                            echo $_SESSION['task_cpt'] ;
                        }else{
                            echo '0';
                        }
                       ?></div>
                        <div class="cardName">Tasks Todo</div>
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
                        <h2>Tasks Todo</h2>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Description</td>
                                <td>Assigned By</td>
                                <td>Deadline</td>
                                
                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                        $task_cpt = 0 ;
                        foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td><?php echo htmlspecialchars($task['assigned_by']); ?> </td>
                                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                                <?php if (!$task['completed']): ?>
                                    <form method="POST" action="index.php" style="display:inline;">   <td>
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit">Marquer comme terminée</button>
                         </td></form>
                    <?php else: ?>
                        <strong>(Terminée)</strong>
                    <?php endif; ?>
                            </tr>
                            <?php 
                            $task_cpt = $task_cpt + 1;
                     

                        endforeach; 
                        
                        $_SESSION['task_cpt'] = $task_cpt++;
                        ?>
                        
                        </tbody>
                    </table>
                </div>

                <!-- ================= New Customers ================ -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Your Groups</h2>
                    </div>

                    <table>

                    <?php
                    $grp_cpt = 0 ;
                    foreach ($groups as $group): ?>
                        <tr>

                            <td width="60px">
                                <div class="imgBx"><img src="assets/imgs/customer02.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4><?php echo htmlspecialchars($group['name']); ?></h4>
                            </td>
                        </tr>
                        <?php 
                        $grp_cpt = $grp_cpt + 1;
                         endforeach; 
                         
                         $_SESSION['grp_cpt'] = $grp_cpt;?>
                      
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