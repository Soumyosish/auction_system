# Online Auction System

A comprehensive online auction platform built with PHP, MySQL, and Tailwind CSS that allows users to create auctions, place bids, and track their bidding history.

## Features

- User authentication (Register, Login, Profile management)
- Create and manage auction listings
- Browse auctions with category filters
- Detailed auction pages with bidding functionality
- Real-time countdown timers for auction end dates
- Bidding history tracking
- Responsive design using Tailwind CSS
- Admin dashboard with user and auction management

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

Follow these steps to set up the auction system:

### 1. Database Setup

1. Create a MySQL database named `auction_system` or use your preferred name.
2. Import the database schema from `auction_system.sql` using phpMyAdmin or MySQL command line:

```
```

### 2. Configure Database Connection

Open `php/config.php` and update the database connection parameters:

```php
define('DB_SERVER', 'localhost');      // Database server
define('DB_USERNAME', 'your_username'); // Database username
define('DB_PASSWORD', 'your_password'); // Database password
define('DB_NAME', 'auction_system');    // Database name
```

### 3. Server Configuration

1. Copy all files to your web server directory (e.g., `/var/www/html/auction_system` or `C:\xampp\htdocs\auction_system`).
2. Make sure the web server has read and write permissions to the project directory.

### 4. Base URL Configuration

Update the `BASE_URL` constant in `php/config.php` to match your server setup:

```php
define('BASE_URL', 'http://your-domain.com/auction_system');
```

Or for local development:

```php
define('BASE_URL', 'http://localhost/auction_system');
```

## Usage

### Admin Account

The system comes with a pre-configured admin account:
- Username: `admin`
- Password: `admin123`

### Regular User

1. Register a new account from the registration page.
2. Browse available auctions or create your own.
3. Place bids on active auctions.
4. Track your bidding history and auction wins.

## Folder Structure

```
auction_system/
├── assets/           # Static assets
├── css/              # CSS files
├── js/               # JavaScript files
├── php/              # PHP functions and includes
│   ├── config.php           # Database and site configuration
│   ├── auth_functions.php   # Authentication functions
│   ├── auction_functions.php # Auction-related functions
│   ├── header.php           # Common header
│   └── footer.php           # Common footer
├── sql/              # SQL schema and data
├── images/           # Uploaded images (if local storage is used)
├── index.php         # Homepage
├── browse.php        # Browse auctions
├── auction.php       # Auction details page
├── create_auction.php # Create new auction
├── login.php         # Login page
├── register.php      # Registration page
├── profile.php       # User profile
├── my_bids.php       # User's bidding history
├── my_auctions.php   # User's auctions
└── admin_dashboard.php # Admin dashboard
```

## Customization

### Adding Categories

To add more categories, insert them into the `categories` table:

```sql
INSERT INTO categories (name, description) VALUES ('New Category', 'Description of the category');
```

### Styling

The project uses Tailwind CSS via CDN. You can customize the styling by modifying the classes or extending Tailwind in a local setup.

## Notes

- In a production environment, make sure to:
  - Use HTTPS
  - Implement additional security measures
  - Set up proper error logging
  - Consider using a dedicated mail server for notifications

## License

This project is for educational purposes only. Feel free to use and modify it for personal or commercial use.

## Credits

- Built with [PHP](https://www.php.net/)
- Styled with [Tailwind CSS](https://tailwindcss.com/)
- Icons from [Font Awesome](https://fontawesome.com/)
