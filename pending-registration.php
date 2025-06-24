<?php
include_once "theme-manager.php";
session_start(); // Ensure session is started for theme settings
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Registration - Admin Portal</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    /* Dark mode styles for pending-registration.php */
    body.dark-theme {
        background-color: #1a1a1a;
        color: #fff;
    }
    body.dark-theme .admin-header,
    body.dark-theme .table-container,
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
    body.dark-theme .form-group label,
    body.dark-theme .detail-group label {
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
    </style>
</head>
<body class="<?php echo getThemeClasses(); ?>">
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <img src="img/logo.png" alt="CVSU Logo" class="logo">
                <h1>Pending Registration</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingTableBody">
                        <!-- Pending registrations will be populated here -->
                    </tbody>
                </table>
            </div>

            <div class="back-button">
                <a href="admin-dashboard.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to admin page
                </a>
            </div>
        </main>
    </div>

    <!-- Registration Details Modal -->
    <div class="modal" id="registrationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Registration Details</h3>
                <span class="modal-close" id="registrationModalClose">&times;</span>
            </div>
            <div class="registration-details">
                <div class="detail-group">
                    <label>First Name:</label>
                    <span id="regFirstName"></span>
                </div>
                <div class="detail-group">
                    <label>Last Name:</label>
                    <span id="regLastName"></span>
                </div>
                <div class="detail-group">
                    <label>Email:</label>
                    <span id="regEmail"></span>
                </div>
                <div class="detail-group">
                    <label>Student Number:</label>
                    <span id="regStudentNumber"></span>
                </div>
                <div class="detail-group">
                    <label>Department:</label>
                    <span id="regDepartment"></span>
                </div>
                <div class="detail-group">
                    <label>Date of Birth:</label>
                    <span id="regDateOfBirth"></span>
                </div>
                <div class="detail-group">
                    <label>Registration Date:</label>
                    <span id="regRegistrationDate"></span>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-success" onclick="approveRegistration()">
                    <i class="fas fa-check"></i> Accept
                </button>
                <button class="btn-danger" onclick="rejectRegistration()">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
        </div>
    </div>

    <script src="pending-registration.js"></script>
</body>
</html>
