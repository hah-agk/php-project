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
<style>
body{
    background:#0f172a;
    color:#e5e7eb;
    font-family:Arial;
    padding:40px;
}
table{
    width:100%;
    border-collapse:collapse;
    background:#111827;
    border-radius:10px;
    overflow:hidden;
}
th, td{
    padding:14px;
    text-align:center;
}
th{
    background:#1f2933;
}
tr:nth-child(even){
    background:#0b1220;
}
.status-pending{ color:#facc15; }
.status-approved{ color:#22c55e; }
.status-rejected{ color:#ef4444; }

.btn{
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-weight:bold;
    margin:0 3px;
}
.approve{ background:#22c55e; color:#000; }
.reject{ background:#ef4444; color:#fff; }
.disabled{
    opacity:0.4;
    pointer-events:none;
}
</style>
</head>
<body>

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

</body>
</html>
