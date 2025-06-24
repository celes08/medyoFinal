<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_terms'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Agreement - CVSU Department Bulletin Board System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(rgba(4, 9, 30, 0.85), rgba(4, 9, 30, 0.85)), url('img/Silang-Campus-scaled.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .terms-card {
            background: rgba(255,255,255,0.97);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            max-width: 650px;
            width: 95vw;
            padding: 36px 32px 28px 32px;
            margin: 32px 0;
            color: #1b4332;
            animation: fadeIn 1.2s cubic-bezier(.4,0,.2,1);
        }
        .terms-card h2 {
            color: #14532d;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 18px;
            text-align: center;
        }
        .terms-card h3 {
            color: #006400;
            font-size: 1.1rem;
            margin-top: 18px;
            margin-bottom: 6px;
        }
        .terms-card p {
            color: #222;
            font-size: 1rem;
            margin-bottom: 8px;
        }
        .terms-card .accept-btn {
            display: block;
            margin: 32px auto 0 auto;
            background: #1b4332;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 12px 36px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .terms-card .accept-btn:hover {
            background: #14532d;
            transform: translateY(-2px);
        }
        @media (max-width: 700px) {
            .terms-card {
                padding: 18px 6vw 18px 6vw;
                max-width: 99vw;
            }
        }
    </style>
</head>
<body>
    <div class="terms-card">
        <h2>Terms and Agreement</h2>
        <form method="post">
            <div style="line-height: 1.6; max-height: 55vh; overflow-y: auto;">
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using the Cavite State University - Silang Campus EBA Inventory System, you accept and agree to be bound by the terms and provision of this agreement.</p>
                <h3>2. Use License</h3>
                <p>Permission is granted to temporarily access the materials on the EBA Inventory System for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title.</p>
                <h3>3. User Responsibilities</h3>
                <p>You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account or password.</p>
                <h3>4. Data Privacy</h3>
                <p>Your personal information will be handled in accordance with the Data Privacy Act of 2012. We are committed to protecting your privacy and ensuring the security of your data.</p>
                <h3>5. Prohibited Uses</h3>
                <p>You may not use the system for any unlawful purpose or to solicit others to perform unlawful acts. You may not violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances.</p>
                <h3>6. Disclaimer</h3>
                <p>The materials on the EBA Inventory System are provided on an 'as is' basis. Cavite State University makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                <h3>7. Limitations</h3>
                <p>In no event shall Cavite State University or its suppliers be liable for any damages arising out of the use or inability to use the materials on the EBA Inventory System.</p>
                <h3>8. Revisions and Errata</h3>
                <p>The materials appearing on the EBA Inventory System could include technical, typographical, or photographic errors. Cavite State University does not warrant that any of the materials on the system are accurate, complete, or current.</p>
                <h3>9. Links</h3>
                <p>Cavite State University has not reviewed all of the sites linked to the EBA Inventory System and is not responsible for the contents of any such linked site.</p>
                <h3>10. Modifications</h3>
                <p>Cavite State University may revise these terms of service at any time without notice. By using the EBA Inventory System, you are agreeing to be bound by the current version of these Terms of Service.</p>
            </div>
            <button type="submit" name="accept_terms" class="accept-btn">I Understand</button>
        </form>
    </div>
</body>
</html> 