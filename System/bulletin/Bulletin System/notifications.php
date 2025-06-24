<?php
// Handle POST requests and redirects FIRST
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_all_notifications'])) {
    include("connections.php");
    $user_id = $_SESSION['user_id'];
    $delete_sql = "DELETE FROM notifications WHERE user_id = ?";
    $delete_stmt = $con->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    header("Location: notifications.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read_id'])) {
    include("connections.php");
    $user_id = $_SESSION['user_id'];
    $notification_id_to_mark = intval($_POST['mark_as_read_id']);
    $stmt = $con->prepare("UPDATE notifications SET is_read = TRUE, read_at = CURRENT_TIMESTAMP WHERE notification_id=? AND user_id=?");
    $stmt->bind_param("ii", $notification_id_to_mark, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: notifications.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_notification_id'])) {
    include("connections.php");
    $user_id = $_SESSION['user_id'];
    $notification_id_to_delete = intval($_POST['delete_notification_id']);
    $stmt = $con->prepare("DELETE FROM notifications WHERE notification_id=? AND user_id=?");
    $stmt->bind_param("ii", $notification_id_to_delete, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: notifications.php");
    exit();
}

include("user_session.php");
requireLogin();
include("connections.php");
include("post-modal-shared.php");

// Fetch notifications for the current user
$user_id = $_SESSION['user_id'];
$notifications = [];

$sql = "SELECT n.*, p.title AS post_title,
               su.email AS sender_email,
               CONCAT_WS(' ', su.first_name, su.middle_name, su.last_name) AS sender_full_name,
               su.profile_picture AS sender_profile_picture
        FROM notifications n
        LEFT JOIN posts p ON n.related_post_id = p.post_id
        LEFT JOIN signuptbl su ON n.related_user_id = su.user_id
        WHERE n.user_id = ?
        ORDER BY n.is_read ASC, n.created_at DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - CVSU Department Bulletin Board System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="notifications.css">
    <link rel="stylesheet" href="post-modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* notifications.css */

        .notifications-content {
            padding: 20px;
            background-color: #f0f2f5; /* Light background */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .notifications-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }

        .notifications-header h1 {
            font-size: 2em;
            color: #333;
            margin: 0;
        }

        .clear-all-notifications {
            background: none;
            border: none;
            color: #dc3545; /* Red for delete action */
            cursor: pointer;
            font-size: 0.9em;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.2s ease, color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .clear-all-notifications:hover {
            background-color: #f8d7da;
            color: #c82333;
        }

        .empty-notifications {
            padding: 50px 20px;
            text-align: center;
            color: #777;
        }

        .empty-notifications .empty-icon {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-notifications h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .empty-notifications p {
            font-size: 1em;
        }

        .notifications-list {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden; /* For rounded corners on first/last item */
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
            position: relative; /* For positioning action buttons */
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
        }

        .notification-item.unread {
            background-color: #e6f7ff; /* Light blue for unread */
            font-weight: bold;
        }

        .notification-item.unread:hover {
            background-color: #d1edff;
        }

        /* Removed .notification-icon if you're using .notification-type-icon inline */
        .notification-avatar {
            width: 40px; /* Adjust size as needed */
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0; /* Prevent it from shrinking */
        }

        .notification-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-message {
            font-size: 1em;
            color: #333;
            line-height: 1.4;
            margin-bottom: 5px;
            display: flex; 
            align-items: flex-start; 
            gap: 8px; 
        }

        .notification-message a {
            color: green; 
            text-decoration: none;
            font-weight: normal; 
        }

        .notification-message a:hover {
            text-decoration: underline;
        }

        .notification-timestamp {
            font-size: 0.8em;
            color: #888;
        }

        .notification-item.unread .notification-timestamp {
            color: #555; 
        }

        .read-status {
            font-style: italic;
            margin-left: 5px;
            color: #aaa;
        }

        .notification-type-icon {
            font-size: 1.1em; /* Adjust icon size */
            color: #6c757d; /* A subtle grey for generic icons */
            margin-top: 2px; /* Fine-tune vertical alignment */
        }

        /* Specific icon colors if desired for .notification-type-icon */
        /* .notification-item .fa-comment { color: #28a745; } 
        .notification-item .fa-heart { color: #dc3545; } 
        .notification-item .fa-bullhorn { color: #ffc107; } 
        .notification-item .fa-at { color: #6f42c1; } 
        .notification-item .fa-user-check { color: #17a2b8; } 
        .notification-item .fa-user-times { color: #dc3545; } 
        .notification-item .fa-reply { color: #6c757d; }  */


        .notification-item.unread .notification-type-icon {
            color: #007bff; 
        }


        .notification-message strong {
            color: #333;
            font-weight: 600; 
        }


        .notification-actions {
            display: flex;
            gap: 10px;
            margin-left: 20px; 
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            color: #007bff; /* Default action button color */
            transition: color 0.2s ease;
            padding: 0; /* Remove default button padding */
        }

        .action-btn:hover {
            color: #0056b3;
        }

        .action-btn.delete-notification {
            color: #dc3545;
        }

        .action-btn.delete-notification:hover {
            color: #c82333;
        }

        /* Optional: Notifications tabs if you enable them */
        .notifications-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .notification-tab {
            background: none;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 1em;
            color: #555;
            transition: color 0.2s ease, border-bottom 0.2s ease;
            border-bottom: 2px solid transparent;
        }

        .notification-tab.active {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
        }

        .notification-tab:hover:not(.active) {
            color: #007bff;
        }

        /* Inherit general dashboard styles from styles.css */
        /* Ensure you have basic dashboard-body, dashboard-container, sidebar, main-content styles in styles.css */
    </style>
</head>
<body class="dashboard-body<?php
if (isset($_SESSION['theme'])) {
    echo ' ' . htmlspecialchars($_SESSION['theme']) . '-theme';
} else {
    echo ' system-theme';
}
?>">
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="img/logo.png" alt="CVSU Logo" class="sidebar-logo">
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li>
                        <a href="organizational-chart.php"><i class="fas fa-sitemap"></i> Organizational Chart</a>
                    </li>
                    <li class="active">
                        <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                    </li>
                    <li>
                        <a href="bookmarks.php"><i class="fas fa-bookmark"></i> Bookmarks</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                    </li>
                </ul>
            </nav>
            <div class="post-button-container">
                <form method="post" style="margin:0;">
                    <button class="post-button" name="showPostModal" type="submit">
                        <i class="fas fa-plus"></i> Post
                    </button>
                </form>
            </div>
            <div class="sidebar-footer">
                <ul>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="help.php"><i class="fas fa-question-circle"></i> Help</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
                </ul>
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php
                        $profilePic = $currentUser['profile_picture'] ?? '';
                        if ($profilePic) {
                            $profilePic = preg_replace('#^uploads/#', '', $profilePic);
                            $imgSrc = 'uploads/' . htmlspecialchars($profilePic);
                        } else {
                            $initials = htmlspecialchars(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1));
                            $imgSrc = 'https://placehold.co/36x36/cccccc/000000?text=' . $initials;
                        }
                        ?>
                        <img src="<?php echo $imgSrc; ?>" alt="User Avatar">
                    </div>
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']); ?></h4>
                        <p><?php echo htmlspecialchars($currentUser['username']); ?></p>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </aside>
        <main class="main-content notifications-content">
            <div class="notifications-container">
                <div class="notifications-header">
                    <h1>Notifications</h1>
                    <form method="post" onsubmit="return confirm('Are you sure you want to clear all notifications? This action cannot be undone.');">
                        <button type="submit" name="clear_all_notifications" class="clear-all-notifications" id="clearAllNotifications">
                            <i class="fas fa-trash"></i> Clear all notifications
                    </button>
                    </form>
                </div>
                <div class="notifications-list" id="notificationsList">
                    <?php if (empty($notifications)): ?>
                        <div class="empty-notifications" id="emptyNotifications">
                            <i class="fas fa-bell empty-icon"></i>
                            <h2>No Notifications Yet</h2>
                            <p>You have no notifications at this time.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                    <?php
                            $icon_class = 'fas fa-info-circle';
                            switch ($notification['notification_type']) {
                                case 'reply_to_post': $icon_class = 'fas fa-comment'; break;
                                case 'post_liked': $icon_class = 'fas fa-heart'; break;
                                case 'new_post_mention': $icon_class = 'fas fa-at'; break;
                                case 'important_announcement': $icon_class = 'fas fa-bullhorn'; break;
                                case 'account_approved': $icon_class = 'fas fa-user-check'; break;
                                case 'account_rejected': $icon_class = 'fas fa-user-times'; break;
                                case 'new_ticket_reply': $icon_class = 'fas fa-reply'; break;
                                default: $icon_class = 'fas fa-info-circle'; break;
                            }
                            $notification_class = 'notification-item';
                            if ($notification['is_read']) {
                                $notification_class .= ' read';
                            } else {
                                $notification_class .= ' unread';
                            }
                            // Profile picture logic
                            $raw_profile_pic = $notification['sender_profile_picture'];
                            if (!empty($raw_profile_pic)) {
                                // Only prepend 'uploads/' if not already present
                                if (strpos($raw_profile_pic, 'uploads/') === 0) {
                                    $sender_avatar_url = htmlspecialchars($raw_profile_pic);
                                } else {
                                    $sender_avatar_url = 'uploads/' . htmlspecialchars($raw_profile_pic);
                                }
                            } else {
                                $sender_avatar_url = 'img/avatar-placeholder.png';
                            }
                            ?>
                            <!-- Avatar raw: <?php echo $raw_profile_pic; ?> | Avatar final: <?php echo $sender_avatar_url; ?> -->
                            <div class="<?php echo $notification_class; ?>">
                                <div class="notification-avatar">
                                    <img src="<?php echo $sender_avatar_url; ?>" alt="Sender Avatar">
                                </div>
                                <div class="notification-content">
                                    <div class="notification-message">
                                        <span class="notification-type-icon"><i class="<?php echo $icon_class; ?>"></i></span>
                                        <?php
                                        if (!empty($notification['sender_full_name'])) {
                                            echo '<strong>' . htmlspecialchars($notification['sender_full_name']) . '</strong> ';
                                        } elseif (!empty($notification['sender_email'])) {
                                            echo '<strong>' . htmlspecialchars($notification['sender_email']) . '</strong> ';
                                        }
                                        echo htmlspecialchars($notification['message']);
                                        if (!empty($notification['related_post_id']) && !empty($notification['post_title'])) {
                                            echo ' <a href="view_post.php?id=' . htmlspecialchars($notification['related_post_id']) . '">';
                                            echo ' on "' . htmlspecialchars($notification['post_title']) . '"';
                                            echo '</a>';
                            }
                                        ?>
                                    </div>
                                    <div class="notification-timestamp">
                                        <?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?>
                                        <?php if ($notification['is_read'] && !empty($notification['read_at'])): ?>
                                            <span class="read-status">(Read <?php echo date('g:i A', strtotime($notification['read_at'])); ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <?php if (!$notification['is_read']): ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="mark_as_read_id" value="<?php echo $notification['notification_id']; ?>">
                                            <button class="action-btn mark-as-read" type="submit" title="Mark as Read">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="delete_notification_id" value="<?php echo $notification['notification_id']; ?>">
                                        <button class="action-btn delete-notification" type="submit" title="Delete Notification" onclick="return confirm('Are you sure you want to delete this notification?');">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="script.js"></script>
    <script src="notifications.js"></script>
</body>
</html>