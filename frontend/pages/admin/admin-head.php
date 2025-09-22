<?php
$pageTitle = $pageTitle ?? "Barangay Poblacion Sur System";
$pageDescription = $pageDescription ?? "Poblacion Sur Admin Dashboard";
$faviconPath = $faviconPath ?? "../../assets/images/Logo.webp";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="Admin Dashboard Of Barangay Poblacion Sur">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Favicon -->
    <link rel="icon" href="<?= $faviconPath ?>" type="image/x-icon">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
