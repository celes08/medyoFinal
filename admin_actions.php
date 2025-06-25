<?php
// admin_actions.php
include("connections.php"); // Include your database connection file

header('Content-Type: application/json'); // Set header to indicate JSON response

$response = ['success' => false, 'message' => ''];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get action (approve/reject) and user ID from POST data
    $action = $_POST['action'] ?? '';
    $userId = $_POST['userId'] ?? '';

    // Validate input parameters
    if (empty($userId) || empty($action)) {
        $response['message'] = "Missing parameters.";
    } else {
        $newStatus = '';
        $successMessage = '';
        $errorMessage = '';

        // Determine the new status and messages based on the action
        if ($action === 'approve') {
            $newStatus = 'accepted';
            $successMessage = 'User approved successfully.';
            $errorMessage = 'Failed to approve user.';
        } elseif ($action === 'reject') {
            $newStatus = 'rejected';
            $successMessage = 'User rejected successfully.';
            $errorMessage = 'Failed to reject user.';
        } else {
            $response['message'] = "Invalid action."; // Action is not 'approve' or 'reject'
            echo json_encode($response);
            exit(); // Exit to prevent further execution
        }

        // Prepare the SQL statement to update the user's status
        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("UPDATE signuptbl SET status = ? WHERE user_id = ?");
        if ($stmt) {
            // Bind parameters: 's' for string (status), 'i' for integer (user_id)
            $stmt->bind_param("si", $newStatus, $userId);
            
            // Execute the statement
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = $successMessage;
            } else {
                $response['message'] = $errorMessage . " " . $stmt->error; // Include detailed error
            }
            $stmt->close(); // Close the prepared statement
        } else {
            $response['message'] = "Database prepare error: " . $con->error; // Error in preparing the statement
        }
    }
} else {
    $response['message'] = "Invalid request method."; // Only POST requests are allowed
}

// Encode the response array as JSON and output it
echo json_encode($response);
?>
