<?php
session_start();

// Require login
if (!isset($_SESSION['user'])) {
		header('Location: Html/index.php');
		exit;
}

$current_user = $_SESSION['user'] ?? '';
$user_role = $_SESSION['role'] ?? '';

// --- Mock analytics data (replace with DB queries) ---
$days = [];
$counts = [];
for ($i = 6; $i >= 0; $i--) {
		$days[] = date('Y-m-d', strtotime("-$i days"));
		$counts[] = rand(20, 200);
}

$recent_signups = [
		['id'=>301, 'name'=>'Laila Ahmed', 'email'=>'laila@example.com', 'created_at'=>'2025-11-22 14:05:00'],
		['id'=>302, 'name'=>'Marco Polo', 'email'=>'marco@example.com', 'created_at'=>'2025-11-22 13:42:00'],
		['id'=>303, 'name'=>'Sara Ali', 'email'=>'sara@example.com', 'created_at'=>'2025-11-21 18:20:00'],
];

// CSV export for recent signups
if (isset($_GET['export']) && $_GET['export'] === 'recent_users') {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=recent_users.csv');
		$out = fopen('php://output', 'w');
		fputcsv($out, ['id','name','email','created_at']);
		foreach ($recent_signups as $r) {
				fputcsv($out, [$r['id'], $r['name'], $r['email'], $r['created_at']]);
		}
		fclose($out);
		exit;
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Analytics - Admin</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/analytics.css">
</head>
<body>
	<div class="sidebar" style="width:250px;">
		<div class="p-3">
			<h4 class="text-center mb-4"><i class="fas fa-chart-bar me-2"></i>Analytics</h4>
		</div>
		<ul class="nav flex-column">
			<li class="nav-item"><a class="nav-link" href="manager.php"><i class="fas fa-home me-2"></i>Dashboard</a></li>
			<li class="nav-item"><a class="nav-link active" href="analyticspage.php"><i class="fas fa-chart-bar me-2"></i>Analytics</a></li>
			
		</ul>
	</div>

	<div class="main-content">
		<nav class="navbar navbar-expand-lg navbar-light mb-4">
			<div class="container-fluid">
				<span class="navbar-brand">Analytics</span>
				<div class="d-flex">
					<span class="navbar-text me-3">Welcome, <strong><?= htmlspecialchars($current_user) ?></strong>
						<span class="badge bg-<?= $user_role === 'admin' ? 'danger' : 'primary' ?> ms-1"><?= ucfirst($user_role) ?></span>
					</span>
					<a href="Html/mapage.php?logout=1" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
				</div>
			</div>
		</nav>

		<div class="row mb-4">
			<div class="col-lg-8 mb-3">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h6 class="card-title mb-0">Active Users (Last 7 Days)</h6>
						<small class="text-muted">Updated now</small>
					</div>
					<div class="card-body"><canvas id="usersChart" style="max-height:320px;"></canvas></div>
				</div>
			</div>

			<div class="col-lg-4 mb-3">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h6 class="card-title mb-0">Recent Signups</h6>
						<a href="?export=recent_users" class="btn btn-sm btn-outline-secondary">Export CSV</a>
					</div>
					<div class="card-body p-2">
						<div class="table-responsive">
							<table class="table table-sm table-striped mb-0">
								<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Joined</th></tr></thead>
								<tbody>
								<?php foreach ($recent_signups as $r): ?>
									<tr>
										<td><?= (int)$r['id'] ?></td>
										<td><?= htmlspecialchars($r['name']) ?></td>
										<td><?= htmlspecialchars($r['email']) ?></td>
										<td><?= htmlspecialchars($r['created_at']) ?></td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-3 mb-3">
				<div class="card text-white bg-primary">
					<div class="card-body">
						<h5 class="card-title">Total Users</h5>
						<p class="card-text display-6">1,542</p>
					</div>
				</div>
			</div>
			<div class="col-md-3 mb-3">
				<div class="card text-white bg-success">
					<div class="card-body">
						<h5 class="card-title">Active Now</h5>
						<p class="card-text display-6">1,247</p>
					</div>
				</div>
			</div>
			<div class="col-md-3 mb-3">
				<div class="card text-white bg-warning">
					<div class="card-body">
						<h5 class="card-title">Signups Today</h5>
						<p class="card-text display-6">23</p>
					</div>
				</div>
			</div>
			<div class="col-md-3 mb-3">
				<div class="card text-white bg-info">
					<div class="card-body">
						<h5 class="card-title">Revenue</h5>
						<p class="card-text display-6">$12,847</p>
					</div>
				</div>
			</div>
		</div>

		<hr>
		<p class="text-muted small">Notes: This page uses mock data. To use real data, connect to your database (see comment in the file).</p>

	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		const ctx = document.getElementById('usersChart')?.getContext('2d');
		if (ctx) {
			new Chart(ctx, {
				type: 'line',
				data: {
					labels: <?= json_encode($days) ?>,
					datasets: [{
						label: 'Active Users',
						data: <?= json_encode($counts) ?>,
						backgroundColor: 'rgba(54,162,235,0.15)',
						borderColor: 'rgba(54,162,235,1)',
						tension: 0.35,
						fill: true,
						pointRadius: 3
					}]
				},
				options: { responsive: true, scales: { y: { beginAtZero: true } } }
			});
		}
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

