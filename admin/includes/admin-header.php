<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'DeeReel Admin'; ?> - Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<header class="navbar navbar-dark navbar-expand-md p-0 shadow fixed-top" style="background: linear-gradient(135deg, #000 0%, #333 100%); height: 56px;">
    <button class="btn btn-link text-white sidebar-toggle d-md-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <a class="navbar-brand px-3 d-flex align-items-center" href="index.php">
        <img src="../images/drf-logo.webp" alt="DeeReel Footies" height="30" class="me-2">
        <span style="color: #fff; font-weight: bold;">DeeReel Admin</span>
    </a>
    <div class="navbar-nav ms-auto">
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle px-3" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>
                <?php echo $_SESSION['admin_username'] ?? 'Admin'; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="change-password.php">
                    <i class="bi bi-key me-2"></i>Change Password
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                </a></li>
            </ul>
        </div>
    </div>
</header>
