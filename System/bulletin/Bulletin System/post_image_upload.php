<?php
include("user_session.php");
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['post_image'])) {
    $file = $_FILES['post_image'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif', 'heic', 'heif'];

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // 5MB max
                $fileNameNew = uniqid('postimg_', true) . "." . $fileActualExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    echo json_encode(['success' => true, 'url' => $fileDestination]);
                    exit();
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
                    exit();
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'File is too large (max 5MB).']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Upload error.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid file type.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No file uploaded.']);
    exit();
} 