<!DOCTYPE html>
<html lang="en">
<?php include '../../components/Head.php'; ?>

<body class="bg-gray-50">
    <?php include '../../components/Navbar.php'; ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Hero -->
    <section class="text-center mb-12">
        <h1 class="text-4xl font-bold text-blue-600">Contact Us</h1>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
            We’re here to assist you. Reach out for inquiries, concerns, or suggestions.
        </p>
    </section>

    <!-- Contact Info -->
    <section class="max-w-6xl mx-auto py-2 px-6 grid md:grid-cols-3 gap-8">
    <!-- Address Card -->
    <a href="https://maps.app.goo.gl/Ev1fAZ4sop4kFgNdA" target="_blank" class="block bg-white shadow-md p-8 rounded-xl text-center hover:shadow-lg transition no-underline">
        <h3 class="text-xl font-semibold text-blue-600">📍 Address</h3>
        <p class="mt-2 text-gray-600">Barangay Poblacion Sur, Talavera, Nueva Ecija</p>
    </a>

    <!-- Phone Card -->
    <a href="tel:+639056036602" class="block bg-white shadow-md p-8 rounded-xl text-center hover:shadow-lg transition no-underline">
        <h3 class="text-xl font-semibold text-blue-600">📞 Phone</h3>
        <p class="mt-2 text-gray-600">+63 905 603 6602</p>
    </a>

    <!-- Email Card -->
    <a href="mailto:poblacionsur648@gmail.com" class="block bg-white shadow-md p-8 rounded-xl text-center hover:shadow-lg transition no-underline">
        <h3 class="text-xl font-semibold text-blue-600">✉️ Email</h3>
        <p class="mt-2 text-gray-600">poblacionsur648@gmail.com</p>
    </a>
</section>


    <!-- Contact Form & Map Grid -->
    <section class="max-w-6xl mx-auto py-2 px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white shadow-lg rounded-xl p-8">

            <!-- Contact Form -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center md:text-left">Send us a Message</h2>
                <form id="contactForm" action="../../../backend/actions/send_message.php" method="POST" class="space-y-4">
                    <input type="text" name="name" placeholder="Your Name" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none hover:border-blue-400 transition">

                    <input type="email" name="email" placeholder="Your Email" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none hover:border-blue-400 transition">

                    <textarea name="message" rows="5" placeholder="Your Message" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none hover:border-blue-400 transition"></textarea>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition">
                        Send
                    </button>
                </form>
            </div>

            <!-- Google Map -->
            <div>
                <iframe 
                title="Barangay Poblacion Sur Location"
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d301.19277376342507!2d120.91701922389477!3d15.57789444199908!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33972a8ae97e24ab%3A0x2ecb12f935596c5f!2sPoblacion%20Sur%20Barangay%20Compound%2C%20Talavera%2C%20Nueva%20Ecija!5e1!3m2!1sen!2sph!4v1757000854690!5m2!1sen!2sph" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"  
                class="w-full h-64 md:h-[400px] rounded-xl border-0 shadow-md">
            </iframe>
            </div>

        </div>
    </section>
</main>

<?php include '../../components/Footer.php'; ?>
</body>
</html>
