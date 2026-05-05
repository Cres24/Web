-- Create the database
CREATE DATABASE IF NOT EXISTS exploreworld_db;
USE exploreworld_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Destinations table
CREATE TABLE IF NOT EXISTS destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    country VARCHAR(100),
    image_url VARCHAR(255),
    rating DECIMAL(3,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tours table
CREATE TABLE IF NOT EXISTS tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    destination_id INT,
    duration INT,
    difficulty ENUM('Easy', 'Moderate', 'Difficult'),
    price DECIMAL(10,2),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    booking_date DATE NOT NULL,
    number_of_people INT NOT NULL,
    total_price DECIMAL(10,2),
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- Reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    rating TINYINT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- Newsletter subscribers
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(40) DEFAULT NULL,
    subject VARCHAR(160) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

-- Gallery items
CREATE TABLE IF NOT EXISTS gallery_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    subtitle VARCHAR(180) DEFAULT NULL,
    country VARCHAR(80) DEFAULT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog posts
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    category VARCHAR(80) DEFAULT NULL,
    author VARCHAR(120) DEFAULT NULL,
    excerpt TEXT,
    content LONGTEXT,
    image_url VARCHAR(255) DEFAULT NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Packages (curated bundles)
CREATE TABLE IF NOT EXISTS travel_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category ENUM('Luxury','Adventure','Family','Budget') DEFAULT 'Adventure',
    duration_days INT NOT NULL,
    duration_nights INT NOT NULL,
    price_per_person DECIMAL(10,2) NOT NULL,
    highlights TEXT,
    includes_flights BOOLEAN DEFAULT TRUE,
    includes_hotel BOOLEAN DEFAULT TRUE,
    includes_meals BOOLEAN DEFAULT FALSE,
    includes_activities BOOLEAN DEFAULT TRUE,
    image_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Helpful indexes
CREATE INDEX IF NOT EXISTS idx_destinations_country ON destinations(country);
CREATE INDEX IF NOT EXISTS idx_tours_destination_id ON tours(destination_id);
CREATE INDEX IF NOT EXISTS idx_bookings_user_id ON bookings(user_id);
CREATE INDEX IF NOT EXISTS idx_bookings_tour_id ON bookings(tour_id);
CREATE INDEX IF NOT EXISTS idx_reviews_tour_id ON reviews(tour_id);
CREATE INDEX IF NOT EXISTS idx_contact_messages_email ON contact_messages(email);
CREATE INDEX IF NOT EXISTS idx_gallery_featured ON gallery_items(is_featured);
CREATE INDEX IF NOT EXISTS idx_blog_published_at ON blog_posts(published_at);
CREATE INDEX IF NOT EXISTS idx_packages_category ON travel_packages(category);

-- Insert sample data
INSERT INTO destinations (name, description, location, country, image_url, rating) VALUES
('Nepal', 'Land of Himalayas and rich culture', 'Kathmandu', 'Nepal', 'assets/images/nepal.svg', 4.8),
('India', 'Incredible India with diverse culture', 'Delhi', 'India', 'assets/images/india.svg', 4.7),
('Thailand', 'Land of smiles and beautiful beaches', 'Bangkok', 'Thailand', 'assets/images/thailand.svg', 4.9);

INSERT INTO tours (name, description, destination_id, duration, difficulty, price, image_url) VALUES
('Everest Base Camp Trek', 'Experience the majestic Himalayas', 1, 14, 'Moderate', 1200.00, 'assets/images/everest.svg'),
('Golden Triangle Tour', 'Explore India''s rich heritage', 2, 7, 'Easy', 800.00, 'assets/images/tajmahal.svg'),
('Thai Beach Paradise', 'Relax on beautiful beaches', 3, 10, 'Easy', 950.00, 'assets/images/beach.svg');

-- Gallery sample items
INSERT INTO gallery_items (title, subtitle, country, image_url, is_featured) VALUES
('Santorini', 'Magical sunsets and white-washed buildings', 'Greece', 'assets/images/santorini.svg', TRUE),
('Kyoto', 'Ancient temples and traditions', 'Japan', 'assets/images/kyoto.svg', TRUE),
('Machu Picchu', 'Mysteries of the Inca Empire', 'Peru', 'assets/images/machu-picchu.svg', TRUE),
('Paris', 'The city of love and lights', 'France', 'assets/images/paris.svg', FALSE),
('Venice', 'A city built on water', 'Italy', 'assets/images/venice.svg', FALSE),
('Bali', 'Tropical paradise beaches', 'Indonesia', 'assets/images/bali.svg', FALSE),
('Bangkok', 'Street food & vibrant city life', 'Thailand', 'assets/images/bangkok.svg', FALSE);

-- Blog sample posts
INSERT INTO blog_posts (title, slug, category, author, excerpt, content, image_url, published_at) VALUES
('Ultimate Guide to Santorini: Everything You Need to Know', 'ultimate-guide-santorini', 'Travel Guide', 'Sarah Johnson',
 'Discover the magic of Santorini with our guide to the best time to visit, where to stay, what to eat, and hidden gems.',
 'Santorini is famous for its dramatic cliffs, blue domes, and breathtaking sunsets. Start in Oia for sunset views, explore Fira, and take a boat tour to the caldera.\n\nTips:\n- Visit in April–June or September–October.\n- Try local cuisine like tomato keftedes.\n- Book viewpoints early during peak season.',
 'assets/images/santorini.svg', NOW()),
('10 Essential Tips for Your First Trip to Japan', 'first-trip-japan-tips', 'Tips', 'Mike Chen',
 'Navigate Japan like a pro with tips on transportation, etiquette, and must-visit places.',
 'Japan is easy to travel if you plan smart. Use an IC card, learn basic etiquette, and don’t miss regional foods.\n\nQuick tips:\n- Get a Suica/Pasmo card.\n- Keep cash for small shops.\n- Use luggage forwarding when moving cities.',
 'assets/images/kyoto.svg', NOW()),
('How to Experience Bali on a Budget', 'bali-on-a-budget', 'Budget Travel', 'Emma Wilson',
 'Explore Bali without breaking the bank—stay, eat, and travel smart.',
 'Budget Bali is possible with homestays, scooter rentals, and local warungs.\n\nIdeas:\n- Stay in Ubud for culture.\n- Use shared shuttles.\n- Choose free beaches and hikes.',
 'assets/images/bali.svg', NOW());

-- Package sample data
INSERT INTO travel_packages (title, category, duration_days, duration_nights, price_per_person, highlights, includes_flights, includes_hotel, includes_meals, includes_activities, image_url) VALUES
('Maldives Paradise', 'Luxury', 7, 6, 2999.00, 'Overwater villa stay, lagoon cruise, snorkeling', TRUE, TRUE, TRUE, TRUE, 'assets/images/maldives.svg'),
('Dubai Extravaganza', 'Luxury', 6, 5, 3499.00, 'Desert safari, city tour, luxury stay', TRUE, TRUE, FALSE, TRUE, 'assets/images/dubai.svg'),
('Peru Expedition', 'Adventure', 8, 7, 1999.00, 'Inca Trail trek, Machu Picchu entry, guided hikes', TRUE, TRUE, FALSE, TRUE, 'assets/images/peru.svg'),
('Japan Explorer', 'Adventure', 10, 9, 2499.00, 'Temple tours, rail pass, food walk', TRUE, TRUE, FALSE, TRUE, 'assets/images/japan.svg');