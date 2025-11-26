 <?php
 require  "component/opendb.php";
 $sql = "ALTER TABLE users AUTO_INCREMENT = 1";
 $pdo->exec($sql);

