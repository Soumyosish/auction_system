</main>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white mt-10">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">BidPulse</h3>
                    <p class="text-blue-200 mb-4">The premier online auction platform where you can buy and sell items through a transparent bidding process.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-instagram"></i></a>
                        <a href="nkedin.com/in/soumyosishpal/" class="text-white hover:text-blue-200"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-blue-200 hover:text-white">Home</a></li>
                        <li><a href="browse.php" class="text-blue-200 hover:text-white">Browse Auctions</a></li>
                        <li><a href="create_auction.php" class="text-blue-200 hover:text-white">Create Auction</a></li>
                        <li><a href="about.php" class="text-blue-200 hover:text-white">About Us</a></li>
                        <li><a href="contact.php" class="text-blue-200 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <?php
                        require_once 'php/auction_functions.php';
                        $categories = get_all_categories();
                        foreach(array_slice($categories, 0, 5) as $category) {
                            echo '<li><a href="browse.php?category=' . $category['category_id'] . '" class="text-blue-200 hover:text-white">' . $category['name'] . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-blue-200">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span>Lovely Professional University,Phagwara</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3"></i>
                            <span>+91 9002990526</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3"></i>
                            <span>+91 6006835102</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3"></i>
                            <span>+91 9334921382</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3"></i>
                            <span>+91 9076828488</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3"></i>
                            <span>soumyosishpal.108@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-blue-700 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p>© 2025 BidPulse | All Rights Reserved</p>
                <div class="mt-4 md:mt-0">
                    <a href="#" class="text-blue-200 hover:text-white mx-2">Privacy Policy</a>
                    <a href="#" class="text-blue-200 hover:text-white mx-2">Terms of Service</a>
                    <a href="#" class="text-blue-200 hover:text-white mx-2">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for countdown and other functionalities -->
    <script>
        // Function to update all countdowns
        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown');

            countdownElements.forEach(element => {
                const endTime = new Date(element.getAttribute('data-end')).getTime();
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance <= 0) {
                    element.innerHTML = "Auction ended";
                    element.classList.add('text-red-500');
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let countdown = '';
                if (days > 0) countdown += days + "d ";
                countdown += hours + "h " + minutes + "m " + seconds + "s";

                element.innerHTML = countdown;
            });
        }

        // Update countdowns every second
        setInterval(updateCountdowns, 1000);
        updateCountdowns(); // Initial update

        // Form validation functions
        function validateForm(formId, rules) {
            const form = document.getElementById(formId);
            if (!form) return true;

            let isValid = true;

            for (const fieldName in rules) {
                const field = form.querySelector(`[name="${fieldName}"]`);
                const errorElement = form.querySelector(`#${fieldName}-error`);

                if (field && errorElement) {
                    const value = field.value.trim();
                    let fieldValid = true;
                    let errorMessage = '';

                    // Check required
                    if (rules[fieldName].required && value === '') {
                        fieldValid = false;
                        errorMessage = `${rules[fieldName].label} is required`;
                    }
                    // Check min length
                    else if (rules[fieldName].minLength && value.length < rules[fieldName].minLength) {
                        fieldValid = false;
                        errorMessage = `${rules[fieldName].label} must be at least ${rules[fieldName].minLength} characters`;
                    }
                    // Check pattern
                    else if (rules[fieldName].pattern && !new RegExp(rules[fieldName].pattern).test(value)) {
                        fieldValid = false;
                        errorMessage = rules[fieldName].patternMessage || `Invalid ${rules[fieldName].label}`;
                    }

                    if (!fieldValid) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        errorElement.textContent = errorMessage;
                        errorElement.classList.remove('hidden');
                    } else {
                        field.classList.remove('border-red-500');
                        errorElement.classList.add('hidden');
                    }
                }
            }

            return isValid;
        }
    </script>
</body>
</html>
