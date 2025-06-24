<?php
include_once "theme-manager.php";
session_start(); // Ensure session is started for theme settings
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reported Posts - Admin Portal</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    /* Dark mode styles for reported-posts.php */
    body.dark-theme {
        background-color: #1a1a1a !important;
        color: #fff !important;
    }
    body.dark-theme .admin-header,
    body.dark-theme .reported-posts-container,
    body.dark-theme .modal-content {
        background: #23272f !important;
        color: #fff !important;
    }
    body.dark-theme .admin-header h1,
    body.dark-theme .admin-header label,
    body.dark-theme .admin-header .logo {
        color: #fff !important;
    }
    body.dark-theme .admin-table {
        background: #23272f !important;
        color: #fff !important;
    }
    body.dark-theme .admin-table th {
        background: #1b4332 !important;
        color: #fff !important;
    }
    body.dark-theme .admin-table td {
        background: #23272f !important;
        color: #fff !important;
        border-bottom: 1px solid #333 !important;
    }
    body.dark-theme .admin-table tr:hover {
        background-color: #2d333b !important;
    }
    body.dark-theme .btn-primary,
    body.dark-theme .btn-success {
        background: #388e3c !important;
        color: #fff !important;
    }
    body.dark-theme .btn-primary:hover,
    body.dark-theme .btn-success:hover {
        background: #256029 !important;
    }
    body.dark-theme .btn-secondary {
        background: #444 !important;
        color: #fff !important;
    }
    body.dark-theme .btn-secondary:hover {
        background: #222 !important;
    }
    body.dark-theme .btn-danger {
        background: #dc3545 !important;
        color: #fff !important;
    }
    body.dark-theme .btn-danger:hover {
        background: #a71d2a !important;
    }
    body.dark-theme .form-group label {
        color: #fff !important;
    }
    body.dark-theme .form-group input,
    body.dark-theme .form-group select,
    body.dark-theme .form-group textarea {
        background: #23272f !important;
        color: #fff !important;
        border: 1px solid #444 !important;
    }
    body.dark-theme .form-group input:focus,
    body.dark-theme .form-group select:focus,
    body.dark-theme .form-group textarea:focus {
        border-color: #1b4332 !important;
    }
    body.dark-theme .modal-header h3 {
        color: #fff !important;
    }
    body.dark-theme .btn-back {
        color: #fff !important;
    }
    body.dark-theme .btn-back:hover {
        color: #b2dfdb !important;
    }
    body.dark-theme .modal {
        background: rgba(30, 30, 30, 0.8) !important;
    }
    body.dark-theme .modal-content {
        box-shadow: 0 0 20px #111 !important;
    }
    /* Ensure all text in reported posts is white in dark mode */
    body.dark-theme, body.dark-theme * {
        color: #fff !important;
        border-color: #444 !important;
    }
    </style>
</head>
<body class="<?php echo getThemeClasses(); ?>">
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <img src="img/logo.png" alt="CVSU Logo" class="logo">
                <h1>Reported Posts</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="reported-posts-container" id="reportedPostsContainer">
                <!-- Reported posts will be populated here -->
            </div>

            <div class="back-button">
                <a href="admin-dashboard.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to admin page
                </a>
            </div>
        </main>
    </div>

    <!-- Suspension Modal -->
    <div class="modal" id="suspensionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Suspend User</h3>
                <span class="modal-close" id="suspensionModalClose">&times;</span>
            </div>
            <div class="suspension-details">
                <p>Select suspension duration for user: <strong id="suspensionUserName"></strong></p>
                <div class="suspension-options">
                    <label class="radio-option">
                        <input type="radio" name="suspensionDuration" value="3days">
                        <span>3 Days</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="suspensionDuration" value="1week">
                        <span>1 Week</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="suspensionDuration" value="permanent">
                        <span>Permanent Ban</span>
                    </label>
                </div>
                <div class="form-group">
                    <label for="suspensionReason">Reason for Suspension</label>
                    <textarea id="suspensionReason" rows="3" placeholder="Enter reason for suspension..."></textarea>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeSuspensionModal()">Cancel</button>
                <button class="btn-danger" onclick="confirmSuspension()">
                    <i class="fas fa-ban"></i> Suspend User
                </button>
            </div>
        </div>
    </div>

    <script src="reported-posts.js"></script>
</body>
</html>
