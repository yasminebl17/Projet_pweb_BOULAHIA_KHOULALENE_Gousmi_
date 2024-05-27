<?php
require '../../db.php';
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

        header('Location: add_task.php');
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
    <link rel="stylesheet" href="../assets/css/ajout_group1.css">
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
                    <a href="../index.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Add Task</span>
                    </a>
                </li>


                <li>
                    <a href="../../login.php">
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
                    <img src="../assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <!-- ======================= Cards ================== -->
          
            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2 style="margin-left:35%;">Add a Task</h2> 
        <form action="" method="POST">
            <div class="input-container" style="margin-top:10px;">
                <div class="form-group">
                    <label for="leader_id">Your groups:</label>
                        <div class="input-container">
                            <select id="pretty-select" name="group_id" required>
                            <?php foreach ($groups as $group): ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                
                            <?php endforeach; ?>
                            </select>
                                <br>
                        </div>  
                               
                    <div class="input-container" style="margin-right:70%;margin-top:20px;">
                        <label for="Task Description">Task Description: </label>
                        <input type="text"  name="task_description" id="pretty-input" placeholder="Task Description" required>
                              </div>
                                <br>  
                           
                           
                            <label for="members">Assigned to:</label>
                            <br> <br>
            <select id="pretty-select" name="assigned_to" required>
                <?php
                    // Récupérer les membres du groupe
                    foreach ($groups as $group) {
                    $stmt = $pdo->prepare('SELECT users.id, users.username FROM users JOIN group_members ON users.id = group_members.user_id WHERE group_members.group_id = ?');
                    $stmt->execute([$group['id']]);
                    $members = $stmt->fetchAll();
                    foreach ($members as $member) {
                        echo "<option value=\"{$member['id']}\">{$member['username']} (Groupe: {$group['name']})</option>";
                    }
                }
                ?>
            </select>
        <br>
        <label for="deadline">Date limite:</label>
        <input type="date" id="deadline" name="deadline" required>
        <br>
    </div>
    </div>
                               
       
           
      
        <br>
        <button name="ajout_group" class="btn">Ajouter</button>
    </form>
                        </div>
                </div>

            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="../assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>