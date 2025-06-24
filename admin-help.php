<?php
session_start();
include_once "appearance-theme.php";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Tickets - Admin Portal</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }
    body.admin-body {
        background-color: var(--color-bg, #f5f5f5);
        color: var(--color-text, #333);
    }
    body.dark-theme {
        --color-bg: #181a1b;
        --color-text: #f1f1f1;
        --color-header-bg: linear-gradient(135deg, #23272f, #1b4332);
        --color-header-text: #fff;
        --color-card-bg: #23272f;
        --color-card-border: #333;
        --color-card-hover: #263238;
        --color-btn-primary: #2d5a3d;
        --color-btn-primary-hover: #1b4332;
        --color-btn-success: #28a745;
        --color-btn-success-hover: #218838;
        --color-btn-danger: #ff6b6b;
        --color-btn-danger-hover: #c82333;
        --color-btn-back: #fff;
        --color-btn-back-hover: #ccc;
        --color-muted: #b0b0b0;
        --color-modal-bg: #23272f;
    }
    body.light-theme {
        --color-bg: #f5f5f5;
        --color-text: #333;
        --color-header-bg: linear-gradient(135deg, #1b4332, #2d5a3d);
        --color-header-text: #fff;
        --color-card-bg: #fff;
        --color-card-border: #e1e5e9;
        --color-card-hover: #f8f9fa;
        --color-btn-primary: #1b4332;
        --color-btn-primary-hover: #2d5a3d;
        --color-btn-success: #28a745;
        --color-btn-success-hover: #218838;
        --color-btn-danger: #dc3545;
        --color-btn-danger-hover: #c82333;
        --color-btn-back: #1b4332;
        --color-btn-back-hover: #2d5a3d;
        --color-muted: #6c757d;
        --color-modal-bg: #fff;
    }
    body.compact-mode .admin-header {
        padding: 0.5rem 1rem;
    }
    body.high-contrast {
        --color-bg: #000;
        --color-text: #fff;
        --color-header-bg: #000;
        --color-header-text: #fff;
        --color-card-bg: #000;
        --color-card-border: #fff;
        --color-card-hover: #222;
        --color-btn-primary: #fff;
        --color-btn-primary-hover: #ccc;
        --color-btn-success: #fff;
        --color-btn-success-hover: #ccc;
        --color-btn-danger: #ff0;
        --color-btn-danger-hover: #fff;
        --color-btn-back: #fff;
        --color-btn-back-hover: #ccc;
        --color-muted: #fff;
        --color-modal-bg: #000;
    }
    .admin-header {
        background: var(--color-header-bg, linear-gradient(135deg, #1b4332, #2d5a3d));
        color: var(--color-header-text, #fff);
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .logo {
        width: 50px;
        height: 50px;
        border-radius: 8px;
    }
    .admin-main {
        flex: 1;
        padding: 2rem;
    }
    .table-container {
        background: var(--color-card-bg, #fff);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        border: 1px solid var(--color-card-border, #e1e5e9);
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--color-card-bg, #fff);
        color: var(--color-text, #333);
    }
    .admin-table th {
        background: var(--color-header-bg, #1b4332);
        color: var(--color-header-text, #fff);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }
    .admin-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--color-card-border, #e1e5e9);
    }
    .admin-table tr:hover {
        background-color: var(--color-card-hover, #f8f9fa);
    }
    </style>
</head>
<body class="<?php echo getThemeClasses(); ?>">
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <img src="img/logo.png" alt="CVSU Logo" class="logo">
                <h1>Help Tickets</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1001</td>
                            <td>Login Issue</td>
                            <td>Open</td>
                        </tr>
                        <tr>
                            <td>1002</td>
                            <td>Account Locked</td>
                            <td>Resolved</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
