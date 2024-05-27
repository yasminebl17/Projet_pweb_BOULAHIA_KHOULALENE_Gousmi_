<?php
require '../../db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $leader_id = $_POST['leader_id'];
    $members = isset($_POST['members']) ? $_POST['members'] : array();

    // Insérer le nouveau groupe
    $stmt = $pdo->prepare('INSERT INTO groups (name, leader_id) VALUES (?, ?)');
    $stmt->execute([$name, $leader_id]);
    $group_id = $pdo->lastInsertId();

    // Affecter le rôle "leader" au chef de groupe
    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
    $stmt->execute(['leader', $leader_id]);

    // Ajouter les membres au groupe et affecter le rôle "member"
    $stmt = $pdo->prepare('INSERT INTO group_members (group_id, user_id) VALUES (?, ?)');
    foreach ($members as $member_id) {
        $stmt->execute([$group_id, $member_id]);
        $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        $stmt->execute(['member', $member_id]);
    }

    header('Location: ajout_group.php');
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTodoList</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../assets/css/ajout_group3.css">
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
                        <span class="title">Ajouter un groupe</span>
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
                        <h2 style="margin-left:22%;">Ajouter un Groupe</h2> 
                        <form action="" method="POST">
                               
                                
                                <br>
                               
                                <div class="input-container" style="margin-right:70%;margin-top:20px;">
                                <label for="name">Nom du groupe:</label>
                                <input type="text"  name="name" id="pretty-input" placeholder="Nom du groupe" required>
                              </div>
                                <br>  <div class="form-group">
                                <label for="leader_id">Chef de groupe:</label>
                                 <div class="input-container">
                            <select id="pretty-select" name="leader_id" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                            <?php endforeach; ?>
                            </select>
                            <br>
                            <label for="members">Membres:</label>
                            <br> <br>
                            <div class="user-selection">
        <?php foreach ($users as $user): ?>
            <input type="checkbox" id="member_<?php echo $user['id']; ?>"  name="members[]" value="<?php echo $user['id']; ?>">
            <label for="member_<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></label><br>
        <?php endforeach ; ?>
        </div>
        <br>
                        </div>
                    </div>
                               
       
           
        </select>
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