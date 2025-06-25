<?php
include_once "theme-manager.php";
include("connections.php"); // Include your database connection

session_start(); // Ensure session is started for theme settings

// Fetch pending registrations from the database
$pendingRegistrations = [];
// Select all relevant columns for display and actions, filtering by 'pending' status
$stmt = $con->prepare("SELECT user_id, first_name, middle_name, last_name, username, email, date_of_birth, student_number, department, registration_date FROM signuptbl WHERE status = 'pending' ORDER BY registration_date DESC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pendingRegistrations[] = $row;
    }
    $stmt->close();
} else {
    // Log or display error if statement preparation fails
    error_log("Failed to prepare statement for fetching pending registrations: " . $con->error);
}
?>
<!DOCTYPE html>
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

    /* Styles for the loading spinner and notification */
    .loading-spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-left: 10px;
        display: none; /* Hidden by default */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .notification {
        display: none; /* Hidden by default */
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        min-width: 300px;
        text-align: center;
    }
    .notification.success {
        background-color: #4CAF50; /* Green */
    }
    .notification.error {
        background-color: #f44336; /* Red */
    }
    .notification-close {
        position: absolute;
        top: 5px;
        right: 10px;
        color: white;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
    }
    </style>
</head>
<body class="<?php echo getThemeClasses(); ?>">
    <!-- Notification div -->
    <div id="notification" class="notification">
        <button class="notification-close" onclick="document.getElementById('notification').style.display='none';">&times;</button>
        <span id="notificationMessage"></span>
    </div>

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
                        <?php if (empty($pendingRegistrations)): ?>
                            <tr><td colspan="5" style="text-align: center;">No pending registrations.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pendingRegistrations as $registration): ?>
                                <!-- Data attribute to store all user details as a JSON string -->
                                <tr data-user-id="<?php echo htmlspecialchars($registration['user_id']); ?>">
                                    <td><?php echo htmlspecialchars($registration['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['email']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['registration_date']); ?></td>
                                    <td>
                                        <button class="btn-primary view-details-btn" data-details='<?php echo json_encode($registration); ?>'>View Details</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                    <label>Middle Name:</label>
                    <span id="regMiddleName"></span>
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
                    <label>Username:</label>
                    <span id="regUsername"></span>
                </div>
                <div class="detail-group">
                    <label>Registration Date:</label>
                    <span id="regRegistrationDate"></span>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-success" id="acceptBtn">
                    <i class="fas fa-check"></i> Accept <span class="loading-spinner"></span>
                </button>
                <button class="btn-danger" id="rejectBtn">
                    <i class="fas fa-times"></i> Reject <span class="loading-spinner"></span>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registrationModal = document.getElementById('registrationModal');
            const registrationModalClose = document.getElementById('registrationModalClose');
            const pendingTableBody = document.getElementById('pendingTableBody');
            const acceptBtn = document.getElementById('acceptBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notificationMessage');

            let currentUserId = null; // To store the user_id of the currently viewed registration

            /**
             * Displays a notification message to the user.
             * @param {string} message - The message to display.
             * @param {string} type - The type of notification ('success' or 'error').
             */
            function showNotification(message, type) {
                notificationMessage.textContent = message;
                notification.className = `notification ${type}`; // Apply CSS classes
                notification.style.display = 'block'; // Make notification visible
                setTimeout(() => {
                    notification.style.display = 'none'; // Hide after 5 seconds
                }, 5000);
            }

            /**
             * Populates the registration details modal with the provided user data.
             * @param {object} details - An object containing the user's registration details.
             */
            function showRegistrationDetails(details) {
                document.getElementById('regFirstName').textContent = details.first_name;
                document.getElementById('regMiddleName').textContent = details.middle_name || 'N/A'; // Handle optional middle name
                document.getElementById('regLastName').textContent = details.last_name;
                document.getElementById('regEmail').textContent = details.email;
                document.getElementById('regStudentNumber').textContent = details.student_number;
                document.getElementById('regDepartment').textContent = details.department;
                document.getElementById('regDateOfBirth').textContent = details.date_of_birth;
                document.getElementById('regUsername').textContent = details.username;
                document.getElementById('regRegistrationDate').textContent = details.registration_date;
                currentUserId = details.user_id; // Store the user ID for actions
                registrationModal.style.display = 'block'; // Display the modal
            }

            // Event listener for "View Details" buttons within the table body
            // Using event delegation for efficiency
            pendingTableBody.addEventListener('click', function(event) {
                if (event.target.classList.contains('view-details-btn')) {
                    // Parse the JSON string from the data-details attribute
                    const details = JSON.parse(event.target.dataset.details);
                    showRegistrationDetails(details);
                }
            });

            // Close modal when the 'x' button is clicked
            registrationModalClose.addEventListener('click', function() {
                registrationModal.style.display = 'none';
                currentUserId = null; // Clear the stored user ID
            });

            // Close modal when clicking outside of the modal content
            window.addEventListener('click', function(event) {
                if (event.target == registrationModal) {
                    registrationModal.style.display = 'none';
                    currentUserId = null; // Clear the stored user ID
                }
            });

            /**
             * Handles the 'approve' or 'reject' action for a user registration.
             * @param {string} actionType - The type of action ('approve' or 'reject').
             */
            async function handleAction(actionType) {
                if (!currentUserId) {
                    showNotification('No user selected for action.', 'error');
                    return;
                }

                const button = actionType === 'approve' ? acceptBtn : rejectBtn;
                const spinner = button.querySelector('.loading-spinner');
                const originalButtonHtml = button.innerHTML; // Store original HTML to restore it

                button.disabled = true; // Disable the button to prevent multiple clicks
                spinner.style.display = 'inline-block'; // Show the loading spinner

                // Create FormData object to send data via POST
                const formData = new FormData();
                formData.append('action', actionType);
                formData.append('userId', currentUserId);

                try {
                    // Send AJAX request to admin_actions.php
                    const response = await fetch('admin_actions.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json(); // Parse the JSON response

                    if (result.success) {
                        showNotification(result.message, 'success');
                        // Find and remove the row corresponding to the processed user
                        const rowToRemove = document.querySelector(`tr[data-user-id="${currentUserId}"]`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }
                        // If no more rows, display "No pending registrations" message
                        if (pendingTableBody.children.length === 0) {
                            pendingTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">No pending registrations.</td></tr>';
                        }
                        registrationModal.style.display = 'none'; // Close the modal
                        currentUserId = null; // Clear the stored user ID
                    } else {
                        showNotification(result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('An error occurred during the operation.', 'error');
                } finally {
                    button.disabled = false; // Re-enable the button
                    spinner.style.display = 'none'; // Hide the spinner
                    button.innerHTML = originalButtonHtml; // Restore original button content
                }
            }

            // Event listeners for Accept and Reject buttons in the modal
            acceptBtn.addEventListener('click', () => handleAction('approve'));
            rejectBtn.addEventListener('click', () => handleAction('reject'));
        });
    </script>
</body>
</html>
