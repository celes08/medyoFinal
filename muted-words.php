<?php
// PHP SCRIPT START
session_start();
// include_once "theme-manager.php"; // Assuming theme-manager.php content is incorporated or not strictly needed for this single file example

$successMsg = '';
$errorMsg = '';

// Initialize muted words from session or an empty array
// Store each muted word as an associative array to hold 'word' and 'reason'
$_SESSION['admin_muted_words'] = $_SESSION['admin_muted_words'] ?? [];

/**
 * Adds a muted word/phrase to the session list if not a duplicate.
 * @param string $word The word or phrase to mute.
 * @param string $reason The reason for muting.
 * @return array [success (bool), message (string)]
 */
function addMutedWord($word, $reason = '') {
    if (!isset($_SESSION['admin_muted_words']) || !is_array($_SESSION['admin_muted_words'])) {
        $_SESSION['admin_muted_words'] = [];
    }
    $word = trim($word);
    if ($word === '') {
        return [false, 'Word/phrase cannot be empty.'];
    }
    foreach ($_SESSION['admin_muted_words'] as $item) {
        if (strtolower($item['word']) === strtolower($word)) {
            return [false, 'This word/phrase already exists in the muted list.'];
        }
    }
    $_SESSION['admin_muted_words'][] = ['word' => $word, 'reason' => $reason];
    return [true, 'Word/phrase added successfully!'];
}

/**
 * Checks if a word/phrase is already muted.
 * @param string $word The word or phrase to check.
 * @return bool
 */
function isMutedWord($word) {
    if (!isset($_SESSION['admin_muted_words']) || !is_array($_SESSION['admin_muted_words'])) {
        return false;
    }
    foreach ($_SESSION['admin_muted_words'] as $item) {
        if (strtolower($item['word']) === strtolower(trim($word))) {
            return true;
        }
    }
    return false;
}

/**
 * Adds a word/phrase to the muted list (callable from anywhere in PHP)
 * @param string $word The word or phrase to mute
 * @param string $reason The reason for muting
 * @return array [success (bool), message (string)]
 */
function addWordToMute($word, $reason = '') {
    if (isMutedWord($word)) {
        return [false, 'This word/phrase already exists in the muted list.'];
    }
    $word = trim($word);
    if ($word === '') {
        return [false, 'Word/phrase cannot be empty.'];
    }
    $_SESSION['admin_muted_words'][] = ['word' => $word, 'reason' => $reason];
    return [true, 'Word/phrase added successfully!'];
}

// Simulate theme settings if theme-manager.php is not included
$adminTheme = $_SESSION['admin_theme'] ?? 'light'; // Default to 'light'
$adminCompactMode = $_SESSION['admin_compact_mode'] ?? false; // Default to false
$adminHighContrast = $_SESSION['admin_high_contrast'] ?? false; // Default to false

function getThemeClasses() {
    global $adminTheme, $adminCompactMode, $adminHighContrast;
    $classes = "admin-body";
    if ($adminTheme === 'dark') {
        $classes .= " dark-theme";
    } elseif ($adminTheme === 'system') {
        $classes .= " system-theme";
    } else { // 'light'
        $classes .= " light-theme";
    }
    if ($adminCompactMode) {
        $classes .= " compact-mode";
    }
    if ($adminHighContrast) {
        $classes .= " high-contrast";
    }
    return $classes;
}


// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add a new muted word/phrase
    if (isset($_POST['action']) && $_POST['action'] === 'addMutedWord') {
        $word = trim($_POST['mutedWord'] ?? '');
        $reason = trim($_POST['wordReason'] ?? '');
        list($success, $msg) = addMutedWord($word, $reason);
        header('Content-Type: application/json'); // Set header for JSON response
        echo json_encode(['success' => $success, 'message' => $msg]);
        exit; // Stop further execution to return only JSON
    }

    // Delete a muted word/phrase
    if (isset($_POST['action']) && $_POST['action'] === 'deleteMutedWord') {
        $index = $_POST['index'] ?? null;
        if ($index !== null && isset($_SESSION['admin_muted_words'][$index])) {
            $deletedWord = $_SESSION['admin_muted_words'][$index]['word'];
            array_splice($_SESSION['admin_muted_words'], $index, 1);
            $success = true;
            $msg = 'Word/phrase "' . htmlspecialchars($deletedWord) . '" deleted successfully.';
        } else {
            $success = false;
            $msg = 'Invalid word/phrase to delete.';
        }
        header('Content-Type: application/json'); // Set header for JSON response
        echo json_encode(['success' => $success, 'message' => $msg]);
        exit; // Stop further execution to return only JSON
    }
}

// Get the current list of muted words for display
$mutedWords = $_SESSION['admin_muted_words'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muted Words - Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reusing and adapting styles from admin-settings-page */
        /* BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            position: relative;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        /* THEME VARIABLES (Copied from admin-settings-page for consistency) */
        :root {
            --bg-color: #f5f5f5;
            --text-color: #333333;
            --content-bg: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #e1e5e9;
            --accent-color: #007bff; /* General accent, not specific to green */

            /* Admin-specific colors for light mode (default green header) */
            --admin-header-bg: #1b4332;
            --admin-header-text: white;
            --btn-primary-bg: #1b4332;
            --btn-primary-hover-bg: #0f2419;
            --btn-secondary-bg: #6c757d;
            --btn-secondary-hover-bg: #5a6268;
            --highlight-color: #d4edda; /* Light green for selected themes/active states */
            --input-focus-border: #1b4332;
        }

        .dark-theme {
            --bg-color: #1a1a1a;
            --text-color: #ffffff;
            --content-bg: #2d2d2d;
            --card-bg: #333333;
            --border-color: #444444;
            --accent-color: #4dabf7;

            --admin-header-bg: #1b4332; /* Remains green */
            --admin-header-text: #ffffff;
            --btn-primary-bg: #4dabf7; /* Brighter accent for dark mode primary */
            --btn-primary-hover-bg: #1e87f0;
            --btn-secondary-bg: #5a6268;
            --btn-secondary-hover-bg: #6c757d;
            --highlight-color: rgba(77, 171, 247, 0.1); /* Light blue tint for selected/active in dark mode */
            --input-focus-border: #4dabf7;
        }

        .high-contrast {
            --bg-color: #000000;
            --text-color: #ffffff;
            --content-bg: #000000;
            --card-bg: #000000;
            --border-color: #ffffff;
            --accent-color: #ffff00; /* Yellow for high contrast accents */

            --admin-header-bg: #000000;
            --admin-header-text: #ffffff;
            --btn-primary-bg: #ffffff;
            --btn-primary-hover-bg: #cccccc;
            --btn-primary-text: #000000; /* Primary button text for high contrast */
            --btn-secondary-bg: #333333;
            --btn-secondary-hover-bg: #666666;
            --highlight-color: #333333; /* Darker highlight for high contrast */
            --input-focus-border: #ffff00;
        }
        .high-contrast .btn-primary { color: var(--btn-primary-text, black); }

        /* Set a fallback for input-focus-border-rgb */
        :root { --input-focus-border-rgb: 27, 67, 50; } /* Default green */
        .dark-theme { --input-focus-border-rgb: 77, 171, 247; } /* Blue */
        .high-contrast { --input-focus-border-rgb: 255, 255, 0; } /* Yellow */


        /* ADMIN LAYOUT (Copied for consistency) */
        body.admin-body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--bg-color);
        }

        .admin-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            flex-grow: 1;
        }

        /* HEADER STYLES (Copied for consistency) */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: var(--admin-header-bg);
            color: var(--admin-header-text);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            border-bottom: 1px solid var(--border-color);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--admin-header-text);
        }

        .btn-primary, .btn-secondary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--btn-primary-bg);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background-color: var(--btn-primary-hover-bg);
            transform: translateY(-1px);
        }

        .btn-primary:disabled {
            background-color: #adb5bd;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background-color: var(--btn-secondary-bg);
            color: white;
        }

        .btn-secondary:hover {
            background-color: var(--btn-secondary-hover-bg);
            transform: translateY(-1px);
        }

        /* MAIN CONTENT STYLES */
        .admin-main {
            flex-grow: 1;
            padding: 30px;
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px; /* Space between content and back button */
        }

        .muted-words-container {
            max-width: 900px;
            width: 100%;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid var(--border-color);
            padding: 24px;
        }

        .words-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .word-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--content-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            transition: background-color 0.3s ease;
        }

        .word-item:hover {
            background-color: var(--highlight-color);
        }

        .word-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .word-info strong {
            font-size: 18px;
            color: var(--text-color);
        }

        .word-info span {
            font-size: 14px;
            color: #6c757d;
        }

        .word-actions {
            margin-left: 20px;
        }

        .delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 8px; /* Added padding for better click target */
        }

        .delete-btn:hover {
            color: #c82333;
        }

        .back-button {
            margin-top: 20px;
        }
        .btn-back {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            background-color: var(--btn-secondary-bg);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: var(--btn-secondary-hover-bg);
        }

        /* MODAL STYLES (Copied for consistency) */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9) translateY(20px);
            transition: transform 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 24px;
        }

        .modal.active .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background-color: var(--content-bg);
            color: var(--text-color);
        }

        /* FORM STYLES WITHIN MODAL */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 14px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 3px rgba(var(--input-focus-border-rgb), 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        /* NOTIFICATION TOAST (Copied for consistency) */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10000;
            transform: translateX(120%); /* Start off-screen */
            transition: transform 0.4s ease-out;
            min-width: 280px;
        }

        .notification-toast.show {
            transform: translateX(0);
        }

        .notification-toast.success {
            border-left: 4px solid #28a745;
        }

        .notification-toast.error {
            border-left: 4px solid #dc3545;
        }

        .toast-content {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .toast-icon {
            font-size: 16px;
        }

        .notification-toast.success .toast-icon {
            color: #28a745;
        }

        .notification-toast.error .toast-icon {
            color: #dc3545;
        }

        .toast-message {
            font-size: 14px;
            color: var(--text-color);
        }

        .toast-close {
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .toast-close:hover {
            background-color: var(--content-bg);
            color: var(--text-color);
        }

        /* COMPACT MODE (Copied from admin-settings-page) */
        .compact-mode .admin-header { padding: 15px 30px; }
        .compact-mode .admin-header h1 { font-size: 24px; }
        .compact-mode .admin-main { padding: 20px; }
        .compact-mode .muted-words-container { padding: 20px; }
        .compact-mode .words-list { gap: 10px; }
        .compact-mode .word-item { padding: 12px; }
        .compact-mode .word-info strong { font-size: 16px; }
        .compact-mode .word-info span { font-size: 12px; }
        .compact-mode .delete-btn { font-size: 18px; padding: 6px; }
        .compact-mode .back-button { margin-top: 15px; }
        .compact-mode .btn-back { padding: 8px 12px; font-size: 13px; }
        .compact-mode .modal-content { padding: 20px; }
        .compact-mode .modal-header { padding-bottom: 15px; margin-bottom: 20px; }
        .compact-mode .modal-header h3 { font-size: 18px; }
        .compact-mode .form-group { margin-bottom: 15px; }
        .compact-mode .form-group label { font-size: 13px; }
        .compact-mode .form-group input, .compact-mode .form-group textarea { padding: 10px 14px; font-size: 13px; }

        /* RESPONSIVE DESIGN (Copied from admin-settings-page) */
        @media (max-width: 992px) {
            .admin-header { padding: 15px 30px; }
            .admin-header h1 { font-size: 24px; }
            .admin-main { padding: 20px; }
            .muted-words-container { padding: 20px; }
        }

        @media (max-width: 768px) {
            .admin-header { flex-direction: column; text-align: center; gap: 15px; padding: 15px 20px; }
            .header-right { width: 100%; }
            .btn-primary { width: 100%; justify-content: center; } /* Make add button full width */
            .admin-main { padding: 15px; }
            .muted-words-container { margin: 0; padding: 15px; }
            .word-item { flex-direction: column; align-items: flex-start; gap: 8px; }
            .word-actions { margin-left: 0; width: 100%; text-align: right; }
            .modal-content { width: 90%; margin: 0 15px; }
        }

        @media (max-width: 480px) {
            .admin-header { padding: 10px 15px; }
            .admin-header h1 { font-size: 22px; }
            .admin-main { padding: 10px; }
            .muted-words-container { padding: 10px; }
            .word-item { padding: 10px; }
            .word-info strong { font-size: 16px; }
            .word-info span { font-size: 12px; }
            .delete-btn { font-size: 18px; }
            .modal-content { width: 98%; margin: 10px; }
        }
    </style>
</head>
<body class="<?php echo getThemeClasses(); ?>">
    <div class="admin-container">
        <header class="admin-header">
            <div class="header-left">
                <img src="img/logo.png" alt="CVSU Logo" class="logo">
                <h1>Muted Words</h1>
            </div>
            <div class="header-right">
                <button class="btn-primary" onclick="openAddWordModal()">
                    <i class="fas fa-plus"></i> Add Word/Phrase
                </button>
            </div>
        </header>

        <main class="admin-main">
            <div class="notification-toast <?php echo ($successMsg || $errorMsg) ? 'show ' . ($successMsg ? 'success' : 'error') : ''; ?>" id="notificationToast">
                <div class="toast-content">
                    <i class="toast-icon fas <?php echo $successMsg ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <span class="toast-message"><?php echo htmlspecialchars($successMsg ?: $errorMsg); ?></span>
                </div>
                <button class="toast-close"><i class="fas fa-times"></i></button>
            </div>

            <div class="muted-words-container">
                <div class="words-list" id="mutedWordsList">
                    <?php if (empty($mutedWords)): ?>
                        <div style="text-align: center; padding: 30px; color: #6c757d; font-style: italic;">
                            No muted words or phrases found. Add some using the "Add Word/Phrase" button.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="back-button">
                <a href="admin-dashboard.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Admin Dashboard
                </a>
            </div>
        </main>
    </div>

    <div class="modal" id="addWordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Muted Word/Phrase</h3>
                <button type="button" class="modal-close" id="addWordModalClose"><i class="fas fa-times"></i></button>
            </div>
            <form id="addWordForm">
                <div class="form-group">
                    <label for="mutedWord">Word/Phrase</label>
                    <input type="text" id="mutedWord" required placeholder="Enter word or phrase to mute">
                </div>
                <div class="form-group">
                    <label for="wordReason">Reason</label>
                    <textarea id="wordReason" rows="3" placeholder="Why is this word/phrase being muted?"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeAddWordModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Add to Muted List</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- PHP data passed to JavaScript ---
        const initialMutedWords = <?php echo json_encode($mutedWords); ?>;
        const initialAdminTheme = "<?php echo htmlspecialchars($adminTheme); ?>";
        const initialAdminCompactMode = <?php echo $adminCompactMode ? 'true' : 'false'; ?>;
        const initialAdminHighContrast = <?php echo $adminHighContrast ? 'true' : 'false'; ?>;

        // --- DOM Elements ---
        const mutedWordsList = document.getElementById('mutedWordsList');
        const addWordModal = document.getElementById('addWordModal');
        const addWordModalClose = document.getElementById('addWordModalClose');
        const addWordForm = document.getElementById('addWordForm');
        const mutedWordInput = document.getElementById('mutedWord');
        const wordReasonTextarea = document.getElementById('wordReason');
        const notificationToast = document.getElementById("notificationToast");
        const toastCloseButton = notificationToast.querySelector(".toast-close");


        // --- Helper Function for HTML escaping (since PHP's htmlspecialchars isn't available in JS directly) ---
        function htmlspecialchars(str) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        // --- Functions ---

        /**
         * Shows a notification toast message.
         * @param {string} message - The message to display.
         * @param {string} type - 'success' or 'error'.
         */
        function showNotification(message, type = "success") {
            if (!notificationToast) return;

            const icon = notificationToast.querySelector(".toast-icon");
            const messageElement = notificationToast.querySelector(".toast-message");

            // Update content and class
            messageElement.textContent = message;
            notificationToast.classList.remove("success", "error");
            notificationToast.classList.add(type);

            // Set icon based on type
            if (type === "success") {
                icon.className = "toast-icon fas fa-check-circle";
            } else {
                icon.className = "toast-icon fas fa-times-circle";
            }

            // Show the toast
            notificationToast.classList.add("show");

            // Hide after 5 seconds
            setTimeout(() => {
                notificationToast.classList.remove("show");
            }, 5000);

            // Add close button functionality if not already added
            if (toastCloseButton && !toastCloseButton.dataset.listenerAttached) {
                toastCloseButton.addEventListener("click", () => {
                    notificationToast.classList.remove("show");
                });
                toastCloseButton.dataset.listenerAttached = 'true';
            }
        }

        /**
         * Applies the selected theme classes to the body.
         * @param {string} theme - 'light', 'dark', or 'system'.
         * @param {boolean} isCompactMode - True if compact mode is active.
         * @param {boolean} isHighContrast - True if high contrast is active.
         */
        function applyTheme(theme, isCompactMode, isHighContrast) {
            const body = document.body;
            body.classList.remove("light-theme", "dark-theme", "system-theme", "compact-mode", "high-contrast");

            // Apply base theme
            if (theme === "dark") {
                body.classList.add("dark-theme");
            } else if (theme === "system") {
                body.classList.add("system-theme");
                if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    body.classList.add("dark-theme");
                } else {
                    body.classList.add("light-theme");
                }
            } else { // 'light' theme
                body.classList.add("light-theme");
            }

            // Apply display options
            if (isCompactMode) {
                body.classList.add("compact-mode");
            }
            if (isHighContrast) {
                body.classList.add("high-contrast");
            }
        }

        /**
         * Renders the list of muted words.
         * This function should ideally fetch the latest data or use a global updated array.
         * For this example, it uses `initialMutedWords` which would be updated on page reload.
         * In a more dynamic SPA, you'd update a JS array and re-render without a full reload.
         */
        function renderMutedWords(wordsToRender) {
            mutedWordsList.innerHTML = ''; // Clear existing list

            if (wordsToRender.length === 0) {
                mutedWordsList.innerHTML = `
                    <div style="text-align: center; padding: 30px; color: #6c757d; font-style: italic;">
                        No muted words or phrases found. Add some using the "Add Word/Phrase" button.
                    </div>
                `;
                return;
            }

            wordsToRender.forEach((item, index) => {
                const wordItem = document.createElement('div');
                wordItem.classList.add('word-item');
                wordItem.dataset.index = index; // Store index for deletion

                wordItem.innerHTML = `
                    <div class="word-info">
                        <strong>${htmlspecialchars(item.word)}</strong>
                        ${item.reason ? `<span>${htmlspecialchars(item.reason)}</span>` : ''}
                    </div>
                    <div class="word-actions">
                        <button type="button" class="delete-btn" data-index="${index}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
                mutedWordsList.appendChild(wordItem);
            });

            // Attach event listeners to new delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', handleDeleteWord);
            });
        }

        /**
         * Opens the "Add Word/Phrase" modal.
         */
        window.openAddWordModal = function() {
            addWordModal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling body
            mutedWordInput.focus(); // Focus on the first input
        }

        /**
         * Closes the "Add Word/Phrase" modal.
         */
        window.closeAddWordModal = function() {
            addWordModal.classList.remove('active');
            document.body.style.overflow = ''; // Restore body scrolling
            addWordForm.reset(); // Clear form fields
        }

        /**
         * Adds a muted word/phrase via AJAX.
         * @param {string} word - The word or phrase to mute
         * @param {string} reason - The reason for muting
         * @returns {Promise<void>}
         */
        async function addMutedWordJS(word, reason) {
            if (!word) {
                showNotification("Word/phrase cannot be empty.", "error");
                return;
            }

            try {
                const formData = new URLSearchParams();
                formData.append('action', 'addMutedWord');
                formData.append('mutedWord', word);
                formData.append('wordReason', reason);

                const response = await fetch('', { // Post to the same PHP file
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                });

                const data = await response.json(); // Parse response as JSON

                if (data.success) {
                    showNotification(data.message, 'success');
                    closeAddWordModal(); // Close modal on success
                    // For simplicity, we'll reload the page to see the updated list.
                    // In a more complex app, you'd update the `initialMutedWords` array
                    // and call `renderMutedWords` again without a full reload.
                    setTimeout(() => location.reload(), 1000); // Reload after a short delay to show toast
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error adding muted word:', error);
                showNotification('An error occurred while adding the word/phrase. Please try again.', 'error');
            }
        }

        /**
         * Handles the submission of the add word form.
         * @param {Event} e - The form submission event.
         */
        async function handleAddWord(e) {
            e.preventDefault(); // Prevent default form submission
            const word = mutedWordInput.value.trim();
            const reason = wordReasonTextarea.value.trim();
            await addMutedWordJS(word, reason);
        }

        /**
         * Handles the deletion of a muted word/phrase.
         * @param {Event} e - The click event from the delete button.
         */
        async function handleDeleteWord(e) {
            const index = e.currentTarget.dataset.index; // Get the index from the button's data-attribute

            if (!confirm('Are you sure you want to delete this word/phrase?')) {
                return;
            }

            try {
                const formData = new URLSearchParams();
                formData.append('action', 'deleteMutedWord');
                formData.append('index', index);

                const response = await fetch('', { // Post to the same PHP file
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData.toString()
                });

                const data = await response.json(); // Parse response as JSON

                if (data.success) {
                    showNotification(data.message, 'success');
                    // Reload the page to reflect the deletion.
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error deleting muted word:', error);
                showNotification('An error occurred while deleting the word/phrase. Please try again.', 'error');
            }
        }

        // --- Event Listeners ---
        addWordModalClose.addEventListener('click', closeAddWordModal);
        addWordModal.addEventListener('click', (e) => {
            if (e.target === addWordModal) { // Close if clicked outside content
                closeAddWordModal();
            }
        });
        addWordForm.addEventListener('submit', handleAddWord);


        // --- Initializations ---
        // Apply theme classes from PHP variables
        applyTheme(initialAdminTheme, initialAdminCompactMode, initialAdminHighContrast);

        // Render muted words list
        renderMutedWords(initialMutedWords);

        // Show initial success/error messages if present from PHP (e.g., after a full page load POST)
        <?php if ($successMsg): ?>
            showNotification("<?php echo htmlspecialchars($successMsg); ?>", "success");
        <?php elseif ($errorMsg): ?>
            showNotification("<?php echo htmlspecialchars($errorMsg); ?>", "error");
        <?php endif; ?>
    });
    </script>
</body>
</html>