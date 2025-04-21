<?php
require_once 'php/config.php';
require_once 'email.php'; // Include the email functionality

$success = '';
$error = '';
$display_message = '';

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $subject = sanitize_input($_POST['subject']);
    $message = sanitize_input($_POST['message']);

    // Simple validation
    if(empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } else {
        // Send the contact message to admin email using user's email as From
        $admin_email = 'shreyatpathi2005@gmail.com';
        $mail_subject = "Contact Form Submission: $subject";
        $mail_message = "<html><body>"
            . "<h2>New Contact Form Submission</h2>"
            . "<p><strong>Name:</strong> $name</p>"
            . "<p><strong>Email:</strong> $email</p>"
            . "<p><strong>Subject:</strong> $subject</p>"
            . "<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>"
            . "</body></html>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $name <$email>" . "\r\n";
        if(mail($admin_email, $mail_subject, $mail_message, $headers)) {
            $success = "Thank you for your message! We'll get back to you soon.";
            $display_message = $mail_message;
        } else {
            $error = "Failed to send your message. Please try again later.";
        }
    }
}

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-blue-600 text-white py-6 rounded-lg mb-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold">Contact Us</h1>
        <p class="text-lg mt-2">Get in touch with the BidHub team</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <!-- Contact Form -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6">Send Us a Message</h2>

            <?php if(!empty($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $success; ?></span>
            </div>
            <?php endif; ?>

            <?php if(!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
            <?php endif; ?>

            <form action="contact.php" method="POST" id="contactForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        <p id="name-error" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Your Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        <p id="email-error" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required>
                    <p id="subject-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-gray-700 font-medium mb-2">Message</label>
                    <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    <p id="message-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-xl font-bold mb-4">Contact Information</h2>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-4">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Address</p>
                        <p class="text-gray-600">Lovely Professional University</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-4">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Phone</p>
                        <p class="text-gray-600">+91 9002990526</p>
                        <p class="text-gray-600">+91 6006835102</p>
                        <p class="text-gray-600">+91 9334921382</p>
                        <p class="text-gray-600">+91 9076828488</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-4">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Email</p>
                        <p class="text-gray-600">shreyatpathi2005@gmail.com</p>
                        <p class="text-gray-600">soumyosishpal.108@gmail.com</p>
                        <p class="text-gray-600">mraj773929@gmail.com</p>
                        <p class="text-gray-600">tushar01mishra01@gmail.com</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-4">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Business Hours</p>
                        <p class="text-gray-600">Mon - Fri: 9:00 AM - 5:00 PM</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-xl font-bold mb-4">Connect With Us</h2>
            <div class="flex space-x-4">
                <a href="#" class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="bg-blue-400 text-white p-2 rounded-full hover:bg-blue-500 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.linkedin.com/in/soumyosishpal/" class="bg-blue-800 text-white p-2 rounded-full hover:bg-blue-900 transition">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </div>
</div>


<!-- FAQ Section -->
<div class="bg-white rounded-lg shadow-md p-8 mb-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Frequently Asked Questions</h2>

    <div class="space-y-4">
        <div class="border border-gray-200 rounded-md">
            <button class="w-full text-left px-6 py-4 focus:outline-none font-semibold text-gray-800">
                How do I register for an account?
            </button>
            <div class="px-6 py-4 bg-gray-50">
                <p class="text-gray-700">
                    Registration is simple. Click on the "Register" button in the top right corner of the page, fill out the registration form with your information, and submit. Once registered, you can start browsing and bidding on auctions.
                </p>
            </div>
        </div>

        <div class="border border-gray-200 rounded-md">
            <button class="w-full text-left px-6 py-4 focus:outline-none font-semibold text-gray-800">
                How do I place a bid?
            </button>
            <div class="px-6 py-4 bg-gray-50">
                <p class="text-gray-700">
                    To place a bid, navigate to the auction page of the item you're interested in. Enter your bid amount (which must be higher than the current price) and click "Place Bid". You'll receive a confirmation if your bid is successful.
                </p>
            </div>
        </div>

        <div class="border border-gray-200 rounded-md">
            <button class="w-full text-left px-6 py-4 focus:outline-none font-semibold text-gray-800">
                How do I create my own auction?
            </button>
            <div class="px-6 py-4 bg-gray-50">
                <p class="text-gray-700">
                    After logging in, click on "Create Auction" in the navigation menu. Fill out the auction form with details about your item, including title, description, category, starting price, and auction duration. Add an image URL and submit the form to create your auction.
                </p>
            </div>
        </div>

        <div class="border border-gray-200 rounded-md">
            <button class="w-full text-left px-6 py-4 focus:outline-none font-semibold text-gray-800">
                What happens when I win an auction?
            </button>
            <div class="px-6 py-4 bg-gray-50">
                <p class="text-gray-700">
                    When you win an auction, you'll receive a notification. You can view your won auctions in the "My Bids" section under "Won Auctions". In a real implementation, you would proceed with payment and shipping arrangements with the seller.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');

    contactForm.addEventListener('submit', function(e) {
        const validationRules = {
            name: {
                required: true,
                label: 'Name'
            },
            email: {
                required: true,
                pattern: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                patternMessage: 'Please enter a valid email address',
                label: 'Email'
            },
            subject: {
                required: true,
                label: 'Subject'
            },
            message: {
                required: true,
                label: 'Message'
            }
        };

        if (!validateForm('contactForm', validationRules)) {
            e.preventDefault();
        }
    });

    // Toggle FAQ answers
    const faqButtons = document.querySelectorAll('.border button');

    faqButtons.forEach(button => {
        button.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            answer.classList.toggle('hidden');
        });
    });
});
</script>

<?php include 'php/footer.php'; ?>
