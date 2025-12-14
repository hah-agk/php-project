<?php
session_start();
require 'component/opendb.php';



// if (!isset($_SESSION['UorMorA']) || $_SESSION['UorMorA'] !== 'admin' || !isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
//     header("Location: signup.php");
//     exit();
// }'
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare(
            "UPDATE manager_requests SET status = 'approved' WHERE id = ?"
        );
        $stmt->execute([$id]);
    }

    if ($action === 'reject') {
        $stmt = $pdo->prepare(
            "UPDATE manager_requests SET status = 'rejected' WHERE id = ?"
        );
        $stmt->execute([$id]);
    }

    header("Location: admin.php");
    exit();
}
$stmt = $pdo->query("
    SELECT *
    FROM manager_requests
    WHERE status = 'pending'
    ORDER BY request_date DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manager Requests</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <h4>
                <i class="fas fa-th-large"></i> Admin Panel
            </h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?= $base ?>/admin.php">
                    <i class="fas fa-home me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base ?>/settings.php">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base ?>/admin.php?logout=1">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
<div class="main-content">
<h2>Manager Requests</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Email</th>
    <th>Status</th>
    <th>Request Date</th>
    
   
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php foreach ($requests as $r): ?>
<tr>
    <td><?= htmlspecialchars($r['id'])?></td>
    <td><?= htmlspecialchars($r['name'])?></td>
    <td><?= htmlspecialchars($r['phone'])?></td>
    <td><?= htmlspecialchars($r['address']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td><?= htmlspecialchars($r['status']) ?></td>
    <td><?= htmlspecialchars($r['request_date']) ?></td>
  
    
       <td>
    <a class="btn approve"
       href="admin.php?action=approve&id=<?= $r['id'] ?>">
       ✅
    </a>

    <a class="btn reject"
       href="admin.php?action=reject&id=<?= $r['id'] ?>">
       ❌
    </a>


    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</body>
</html>
