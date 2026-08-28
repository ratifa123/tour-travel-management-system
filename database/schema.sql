-- ============================================================================
-- ONLINE TOUR & TRAVEL MANAGEMENT SYSTEM - DATABASE SCHEMA
-- ============================================================================
-- Database: tour_travel_system
-- Created: 2026-08-28
-- Purpose: Production-ready schema for tour booking management
-- ============================================================================

-- Create Database
DROP DATABASE IF EXISTS `tour_travel_system`;
CREATE DATABASE `tour_travel_system` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `tour_travel_system`;

-- ============================================================================
-- TABLE: admin
-- ============================================================================
-- Purpose: Store admin user credentials with secure password hashing
-- ============================================================================
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'bcrypt hashed password',
  `full_name` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  
  INDEX `idx_username` (`username`),
  INDEX `idx_email` (`email`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: packages
-- ============================================================================
-- Purpose: Store tour package information with pricing and itineraries
-- ============================================================================
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `duration_days` INT NOT NULL DEFAULT 1,
  `duration_nights` INT NOT NULL DEFAULT 0,
  `price` DECIMAL(10, 2) NOT NULL,
  `discount_price` DECIMAL(10, 2) NULL,
  `max_guests` INT DEFAULT 50,
  `destination` VARCHAR(100),
  `image` VARCHAR(255) NOT NULL COMMENT 'Relative path to image file',
  `itinerary` LONGTEXT NOT NULL COMMENT 'Detailed day-by-day itinerary',
  `highlights` TEXT COMMENT 'Bullet points of package highlights',
  `included` TEXT COMMENT 'What is included in the package',
  `excluded` TEXT COMMENT 'What is NOT included',
  `difficulty_level` ENUM('Easy', 'Moderate', 'Challenging', 'Expert') DEFAULT 'Moderate',
  `best_season` VARCHAR(100),
  `is_featured` BOOLEAN DEFAULT FALSE,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX `idx_title` (`title`),
  INDEX `idx_destination` (`destination`),
  INDEX `idx_is_featured` (`is_featured`),
  INDEX `idx_is_active` (`is_active`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: bookings
-- ============================================================================
-- Purpose: Store customer booking requests with complete contact and package info
-- ============================================================================
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_address` TEXT,
  `travel_date` DATE NOT NULL,
  `total_guests` INT NOT NULL DEFAULT 1,
  `special_requests` TEXT,
  `status` ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') DEFAULT 'Pending',
  `total_amount` DECIMAL(10, 2),
  `payment_status` ENUM('Unpaid', 'Paid', 'Partial', 'Refunded') DEFAULT 'Unpaid',
  `notes` TEXT COMMENT 'Admin notes',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  
  INDEX `idx_package_id` (`package_id`),
  INDEX `idx_customer_email` (`customer_email`),
  INDEX `idx_travel_date` (`travel_date`),
  INDEX `idx_status` (`status`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: reviews (Optional - for future enhancement)
-- ============================================================================
-- Purpose: Store customer reviews and ratings for packages
-- ============================================================================
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT NOT NULL,
  `booking_id` INT NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `rating` INT CHECK (rating BETWEEN 1 AND 5),
  `review_text` TEXT,
  `is_approved` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
  
  INDEX `idx_package_id` (`package_id`),
  INDEX `idx_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SAMPLE DATA
-- ============================================================================

-- Insert sample admin user (password: admin123 - bcrypt hashed)
INSERT INTO `admin` (`username`, `email`, `password`, `full_name`, `is_active`) VALUES
('admin', 'admin@tourtravelco.com', '$2y$10$YourBcryptHashHereAdmin123', 'System Administrator', TRUE);

-- Insert sample tour packages
INSERT INTO `packages` (
  `title`, `description`, `duration_days`, `duration_nights`, `price`, 
  `discount_price`, `destination`, `image`, `itinerary`, `highlights`, 
  `included`, `excluded`, `difficulty_level`, `best_season`, `is_featured`, `is_active`
) VALUES
(
  'Paris City Escape',
  'Experience the magic of the City of Light with guided tours and cultural experiences.',
  5, 4, 1200.00, 999.00, 'Paris, France',
  'assets/images/packages/paris.jpg',
  'Day 1: Arrive in Paris, hotel check-in, evening Seine river cruise\nDay 2: Eiffel Tower & Louvre Museum\nDay 3: Versailles Palace day trip\nDay 4: Montmartre & Arc de Triomphe\nDay 5: Shopping & departure',
  '• Guided Eiffel Tower visit\n• Louvre Museum access\n• Seine River cruise\n• Versailles day tour\n• 3-star hotel accommodation',
  '• Flights\n• All meals\n• Travel insurance\n• Visa assistance',
  '• Travel insurance\n• Visa costs\n• Personal expenses',
  'Easy', 'Spring, Fall', TRUE, TRUE
),
(
  'Swiss Alps Adventure',
  'Mountain hiking and scenic village exploration in the heart of the Swiss Alps.',
  7, 6, 1800.00, 1500.00, 'Swiss Alps',
  'assets/images/packages/swiss-alps.jpg',
  'Day 1-2: Interlaken arrival and acclimatization\nDay 3-4: Jungfraujoch guided trek\nDay 5: Lauterbrunnen Valley exploration\nDay 6: Grindelwald hiking\nDay 7: Return journey',
  '• Guided mountain treks\n• Cable car rides\n• Alpine village tours\n• Professional guide\n• Mountain lodge stays',
  '• Flights\n• Most meals\n• Mountain equipment rental\n• Travel insurance',
  '• Airfare\n• Travel insurance\n• Personal gear (if preferred)',
  'Moderate', 'Summer, Early Fall', TRUE, TRUE
),
(
  'Tokyo Cultural Tour',
  'Immerse yourself in traditional and modern Japan with temples, gardens, and street food.',
  6, 5, 1500.00, 1200.00, 'Tokyo, Japan',
  'assets/images/packages/tokyo.jpg',
  'Day 1: Tokyo arrival, Shibuya exploration\nDay 2: Senso-ji Temple & Asakusa\nDay 3: Meiji Shrine & Harajuku\nDay 4: Day trip to Mt. Fuji\nDay 5: Tsukiji market & Ginza\nDay 6: Departure',
  '• Senso-ji Temple tour\n• Mt. Fuji visit\n• Authentic tea ceremony\n• Street food tasting\n• 4-star hotel stay',
  '• Flights\n• Most meals\n• JR Pass\n• Guided tours\n• Insurance',
  '• International flights\n• Visa (if required)\n• Personal shopping',
  'Easy', 'Spring, Fall', FALSE, TRUE
);

-- ============================================================================
-- END OF SCHEMA
-- ============================================================================
