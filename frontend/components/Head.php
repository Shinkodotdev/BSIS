<?php
$page = basename($_SERVER['PHP_SELF'], '.php');

switch ($page) {
    case 'index':
        $title = 'Home';
        break;
    case 'Profile':
        $title = 'Profile';
        break;
    case 'Announcements':
        $title = 'Announcements';
        break;
    case 'Events':
        $title = 'Events';
        break;
    case 'Services':
        $title = 'Services';
        break;
    case 'Directory':
        $title = 'Directory';
        break;
    case 'Transparency':
        $title = 'Transparency';
        break;
    case 'Login':
        $title = 'Login';
        break;
    case 'Signup':
        $title = 'Signup';
        break;        
    default:
        $title = 'Page';
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSIS - <?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../../assets/images/Logo.webp" type="image/x-icon">
    <link rel="icon" href="../assets/images/Logo.webp" type="image/x-icon">
    <link rel="icon" href="./frontend/assets/images/Logo.webp" type="image/x-icon">
<style>
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>