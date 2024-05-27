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
                        <span class="icon">
                            <ion-icon name="logo-apple"></ion-icon>
                        </span>
                        <span class="title">Admin</span>
                    </a>
                </li>

                <li>
                    <a href="../index.html">
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
                                <option value="type1">Type 1</option>
                                <option value="type2">Type 2</option>
                                <option value="type3">Type 3</option>
                                <option value="type4">Type 4</option>
                            </select>
                            <br>
                            <label for="members">Membres:</label>
                            <br>
        <?php for ($i = 0;$i< 10 ;$i++): ?>
            <input type="checkbox" name="members[]" value="<?php echo $i; ?>">
            <label for="member"><?php echo 'adel'; ?></label><br>
        <?php endfor ; ?>
        <br>
                        </div>
                    </div>
                               
       
           
        </select>
        <br>
        <button name="ajout_group" class="btn">Ajouter</button>
                            </form>
                        </div>
                </div>

                <!-- ================= New Customers ================ -->
            
                <div class="recentCustomers">
              
                    <div class="cardHeader">
                    <h2 style="margin-bottom:20px;">Recent Groups</h2>
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <th>Groupe </th>
                            <th>Leader</th>
                        </tr>      
                </thead>
                <tbody>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/imgs/customer02.jpg" alt=""></div>
                            </td>
                            <td>
                                Italy
                            </td>
                        </tr>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/imgs/customer02.jpg" alt=""></div>
                            </td>
                            <td>
                                Italy
                            </td>
                        </tr>
                        <t/body> 
                    </table>
                   
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