<?php
require '../../db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_id = $_POST['group_id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $leader_id = isset($_POST['leader_id']) ? $_POST['leader_id'] : '';
    $members = isset($_POST['members']) ? $_POST['members'] : array();

    // Vérifiez que les valeurs ne sont pas vides avant d'exécuter le traitement
    if (!empty($name) && !empty($leader_id)) {
        // Mettre à jour les détails du groupe dans la base de données
        $stmt = $pdo->prepare('UPDATE groups SET name = ?, leader_id = ? WHERE id = ?');
        if ($stmt->execute([$name, $leader_id, $group_id])) {
            // Affecter le rôle "leader" au chef de groupe
            $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
            $stmt->execute(['leader', $leader_id]);

            // Supprimer les membres existants du groupe
            $stmt = $pdo->prepare('DELETE FROM group_members WHERE group_id = ?');
            $stmt->execute([$group_id]);

            // Réinsérer les membres mis à jour dans le groupe et affecter le rôle "member"
            $stmt = $pdo->prepare('INSERT INTO group_members (group_id, user_id) VALUES (?, ?)');
            foreach ($members as $member_id) {
                $stmt->execute([$group_id, $member_id]);
                $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
                $stmt->execute(['member', $member_id]);
            }
        } 
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['group_id'])) {
        $group_id = $_GET['group_id'];
    
        // Récupérer les détails du groupe
        $stmt = $pdo->prepare('SELECT * FROM groups WHERE id = ?');
        $stmt->execute([$group_id]);
        $group = $stmt->fetch();

        // Récupérer tous les utilisateurs
        $stmt = $pdo->query('SELECT * FROM users');
        $users = $stmt->fetchAll();

        // Récupérer les membres actuels du groupe
        $stmt = $pdo->prepare('SELECT user_id FROM group_members WHERE group_id = ?');
        $stmt->execute([$group_id]);
        $group_members = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        header('Location: ../index.php');
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
    <link rel="stylesheet" href="../assets/css/ajout_group3.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <a href="ajout_groupe.php">
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
                        <h2 style="margin-left:35%;">Edit Group</h2> 
        <?php if (isset($group)): ?>
        <form action="" method="POST">
            <div class="input-container" style="margin-top:10px;">
                <div class="form-group">
                <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
                    <label for="leader_id">Group Name:</label>
                        <div class="input-container">
                        <input type="text" id="pretty-input" name="name" value="<?php echo htmlspecialchars($group['name']); ?>" required>
                        <br>
                        <label for="leader_id">Chef de groupe:</label>
                        <select id="pretty-select" name="leader_id" required>
                        <?php foreach ($users as $user): ?>
                             <option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == $group['leader_id']) echo 'selected'; ?>><?php echo htmlspecialchars($user['username']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <br>
                        <label for="members">Membres:</label>
                         <br> <br>
                         <div class="user-selection">
                        <?php foreach ($users as $user): ?>
                        <input type="checkbox" id="member_<?php echo $user['id']; ?>" name="members[]" value="<?php echo $user['id']; ?>" <?php if (in_array($user['id'], $group_members)) echo 'checked'; ?>>
                        <label for="member_<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></label><br>
                      <?php endforeach; ?>
                        </div>
                    <br>
                        </div>  
                               
                  
                </div>

        <?php endif; ?>
    </div>
    
                    
        <button name="ajout_group" class="btn">Modifier</button>
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