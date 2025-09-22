<!DOCTYPE html>
<html lang="en">
<?php include '../../components/Head.php'; ?>

<body class="bg-gray-50">
    <?php include '../../components/Navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Hero Section -->
        <?php include('./landing-page-section/hero_section.php');?>

        <!-- History Section -->
        <?php include('./landing-page-section/history_section.php');?>

        <!-- Geography & Demographics -->
        <?php include('./landing-page-section/geography_section.php');?>

        <!-- Economy & Services -->
        <?php include('./landing-page-section/economy_section.php');?>

        <!-- Mission & Vision -->
        <?php include('./landing-page-section/mission_section.php');?>

        <!-- Leaders Section -->
        <?php include('./landing-page-section/leaders_section.php');?>

        <!-- CTA Section -->
        <?php include('./landing-page-section/cta_section.php');?>
    </main>
<?php include '../../components/Footer.php'; ?>
</body>
</html>
