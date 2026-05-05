# ExploreWorld - Travel Booking Platform

A full-stack PHP + MySQL web application demonstrating a complete travel booking system with user authentication, dynamic content management, and booking functionality.

## Features

### User Management
- User registration with strong password validation
- Secure login with session management
- Profile viewing
- Logout functionality
- Dashboard with recent bookings

### Content Management
- Dynamic destinations with ratings and filtering
- Tours with difficulty levels and pricing
- Travel packages with category filtering
- Blog posts with categories and publishing control
- Image gallery with featured items
- Contact form with email storage
- Newsletter subscription system

### Booking System
- Browse available tours
- Create bookings with date and party size
- View all bookings with status
- Cancel bookings
- Automatic price calculation

### Admin-Ready Structure
- Prepared for admin panel addition
- User role system (user/admin)
- Modular code organization
- Scalable architecture

## Tech Stack

- **Backend**: PHP 7.4+ (mysqli)
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Icons**: Font Awesome 6.5
- **Fonts**: Poppins (Google Fonts)

## Project Structure

```
final_project/
├── config/
│   └── database.php          # Database connection & helpers
├── includes/
│   ├── header.php            # Navigation & layout header
│   ├── footer.php            # Footer & closing layout
│   └── init.php              # Bootstrap & utilities
├── assets/
│   ├── css/
│   │   └── style.css         # Global styling
│   ├── js/
│   │   └── main.js           # Client-side interactivity
│   └── images/
│       ├── profiles/         # User profile pictures
│       └── [other images]
├── Login/
│   ├── login.php             # User login page
│   └── signup.php            # User registration page
├── Public pages
│   ├── index.php             # Homepage
│   ├── destinations.php      # Destinations listing
│   ├── tours.php             # Tours listing
│   ├── tour.php              # Tour details & booking
│   ├── packages.php          # Travel packages
│   ├── gallery.php           # Image gallery
│   ├── blog.php              # Blog posts
│   ├── post.php              # Blog post details
│   ├── about.php             # About page
│   ├── contact.php           # Contact form
│   ├── subscribe.php         # Newsletter endpoint
│   ├── booking.php           # Booking form
│   ├── dashboard.php         # User dashboard
│   ├── my-bookings.php       # User bookings
│   └── logout.php            # Logout handler
├── database.sql              # Database schema & tables
├── DEPLOYMENT.md             # Deployment instructions
├── PRODUCTION_CHECKLIST.md   # Pre-launch checklist
├── logs/                     # Error log directory
└── .htaccess                 # Security & performance config
```

## Installation

### Local Development

1. **Create database**
   ```sql
   CREATE DATABASE exploreworld_db;
   ```

2. **Import schema**
   ```bash
   mysql -u root -p exploreworld_db < database.sql
   ```

3. **Configure database** (edit `config/database.php`)
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'exploreworld_db');
   ```

4. **Set file permissions**
   ```bash
   chmod 755 assets/images/profiles/
   chmod 755 logs/
   ```

5. **Access application**
   ```
   http://localhost/final_project/
   ```

## Production Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for complete deployment instructions for Infinity Free and other shared hosting providers.

### Quick Summary
1. Update `config/database.php` with production credentials
2. Set `APP_ENV=production` environment variable
3. Create required directories with proper permissions
4. Import database schema
5. Upload files via FTP
6. Test all functionality

## Usage Guide

### For Users
1. Visit homepage and explore destinations
2. Sign up for an account
3. Login with credentials
4. Browse and filter tours
5. Create a booking
6. View and manage bookings from dashboard
7. Contact support via contact form

### For Administrators (Future)
- Manage destinations and tours
- View user bookings and activity
- Manage blog posts
- View contact messages
- Manage newsletter subscribers

## Security Features

- ✓ Password hashing with bcrypt
- ✓ Prepared statements (SQL injection prevention)
- ✓ Input validation and sanitization
- ✓ Output escaping (XSS prevention)
- ✓ Session security (session regeneration)
- ✓ File upload validation
- ✓ HTTPS support (recommended)
- ✓ Error logging (not displayed to users)
- ✓ CORS and security headers in `.htaccess`

## API Endpoints

### Public
- `GET /index.php` - Homepage
- `GET /tours.php?q=search&country=filter` - Tours listing
- `GET /tour.php?id=1` - Tour details
- `GET /destinations.php` - Destinations listing
- `GET /blog.php` - Blog posts
- `GET /post.php?slug=post-slug` - Blog post details
- `GET /gallery.php` - Image gallery
- `GET /about.php` - About page
- `GET /contact.php` - Contact page

### Forms
- `POST /subscribe.php` - Newsletter subscription
- `POST /contact.php` - Contact form submission
- `POST /Login/login.php` - User login
- `POST /Login/signup.php` - User registration

### Authenticated
- `GET /dashboard.php` - User dashboard
- `GET /my-bookings.php` - User bookings
- `GET /booking.php?tour_id=1` - Booking form
- `POST /booking.php` - Submit booking
- `GET /logout.php` - User logout

## Database Schema

### Tables
- `users` - User accounts and authentication
- `destinations` - Travel destinations
- `tours` - Tour packages and details
- `bookings` - User tour bookings
- `blog_posts` - Blog articles
- `gallery_items` - Gallery images
- `travel_packages` - Travel packages
- `newsletter_subscribers` - Newsletter signups
- `contact_messages` - Contact form submissions

## Error Handling

All errors are logged to `/logs/php_errors.log`. 

In development mode (`APP_ENV=development`), errors are displayed on screen.
In production mode (`APP_ENV=production`), generic messages are shown to users.

## Performance

- Responsive design (works on all devices)
- Optimized CSS/JS (cached by browser)
- Database query optimization
- Prepared statements for security & performance
- Image optimization recommended for production

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Known Limitations

1. No payment gateway integration
2. No email sending (contact form stores to DB only)
3. Single language (English)
4. No admin panel
5. No password reset functionality
6. No two-factor authentication

## Future Enhancements

- [ ] Payment integration (Stripe/PayPal)
- [ ] Email notifications
- [ ] Admin dashboard
- [ ] User profile editing
- [ ] Review & rating system
- [ ] Advanced search
- [ ] Multi-language support
- [ ] API (REST)
- [ ] Mobile app

## Troubleshooting

### 404 Not Found
- Check `.htaccess` file exists
- Verify URL paths in code match hosting structure
- Check `BASE_URL` calculation in `includes/init.php`

### Database Connection Error
- Verify credentials in `config/database.php`
- Check database exists and user has permissions
- Test via phpMyAdmin first

### File Upload Fails
- Verify `assets/images/profiles/` permissions (755)
- Check PHP file upload limits
- Verify MIME types allowed

### Blank Page
- Check `/logs/php_errors.log`
- Enable `display_errors` temporarily for debugging
- Verify all required files exist

## Support & Issues

1. Check [DEPLOYMENT.md](DEPLOYMENT.md) for hosting-specific issues
2. Check [PRODUCTION_CHECKLIST.md](PRODUCTION_CHECKLIST.md) for launch readiness
3. Review error logs in `/logs/`
4. Verify database connectivity

## License

This is a demo/educational project. Modify and use freely for learning purposes.

## Version

**Version**: 1.0  
**Last Updated**: May 5, 2026  
**Status**: Production Ready

---

**Built with**: PHP + MySQL + HTML5 + CSS3 + JavaScript  
**Deployment Target**: Infinity Free, Shared Hosting, VPS, Cloud
