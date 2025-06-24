# Hotel Management System

A modern and comprehensive hotel management solution built with CodeIgniter 4, featuring an intuitive dashboard for managing rooms, guests, and service requests.

## Local Development Setup

### Prerequisites
- XAMPP (or equivalent with PHP 7.4+ and MySQL 5.7+)
- Composer
- Git
- Web browser (Chrome/Firefox recommended)

### Step 1: XAMPP Setup
1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services from XAMPP Control Panel
3. Verify XAMPP is running by visiting [http://localhost](http://localhost)

### Step 2: Project Setup
1. Navigate to XAMPP's htdocs directory:
```bash
cd C:/xampp/htdocs
```

2. Clone the repository:
```bash
git clone https://github.com/[your-username]/hotel_manager.git
cd hotel_manager
```

3. Install dependencies:
```bash
composer install
```

### Step 3: Environment Configuration
1. Copy the environment file:
```bash
cp env .env
```

2. Update `.env` file with your database settings:
```env
database.default.hostname = localhost
database.default.database = hotel_manager
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

3. Other important .env configurations:
```env
app.baseURL = 'http://localhost/hotel_manager/public'
app.indexPage = ''
```

### Step 4: Database Setup
1. Create database through phpMyAdmin:
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Click "New" to create a new database
   - Name it "hotel_manager"
   - Set collation to "utf8mb4_unicode_ci"

2. Run migrations:
```bash
php spark migrate
```

3. Seed initial data:
```bash
php spark db:seed AdminUserSeeder
```

### Step 5: File Permissions (if using Linux/Mac)
```bash
chmod -R 777 writable/
```

### Step 6: Start the Application
1. Access the application:
   - Open your browser
   - Visit: [http://localhost/hotel_manager/public](http://localhost/hotel_manager/public)

2. Default login credentials:
   - Username: admin
   - Password: admin123

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify MySQL is running in XAMPP
   - Check database credentials in `.env`
   - Ensure database exists

2. **500 Server Error**
   - Check file permissions in writable/ directory
   - Verify PHP version compatibility
   - Check error logs in `writable/logs/`

3. **404 Not Found**
   - Verify .htaccess file exists in public/
   - Check base URL in .env
   - Enable mod_rewrite in Apache

4. **Composer Issues**
   - Clear composer cache:
     ```bash
     composer clear-cache
     ```
   - Update dependencies:
     ```bash
     composer update
     ```

### Development Tools

1. **Enable Error Reporting**
   In `.env`:
   ```env
   CI_ENVIRONMENT = development
   ```

2. **Database Debug**
   ```php
   // Enable query logging in .env
   database.default.DBDebug = true
   ```

3. **Clear Cache**
   ```bash
   php spark cache:clear
   ```

## Additional Setup

### Email Configuration (Optional)
Update `.env` for email functionality:
```env
email.fromEmail = 'your@email.com'
email.fromName = 'Hotel Manager'
email.SMTPHost = 'smtp.gmail.com'
email.SMTPUser = 'your@email.com'
email.SMTPPass = 'your-password'
```

### SSL Setup (Optional)
1. Enable SSL in Apache
2. Update base URL in `.env`:
```env
app.baseURL = 'https://localhost/hotel_manager/public'
```

## Development Workflow

1. **Code Style**
   - Follow PSR-4 autoloading standards
   - Use meaningful variable and function names
   - Comment complex logic

2. **Version Control**
   ```bash
   git checkout -b feature/your-feature
   git add .
   git commit -m "feat: your feature description"
   git push origin feature/your-feature
   ```

3. **Testing**
   ```bash
   # Run PHPUnit tests
   vendor/bin/phpunit
   ```

## Support

For issues and support:
1. Check the [troubleshooting](#troubleshooting) section
2. Review error logs in `writable/logs/`
3. Open an issue in the repository

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## Features

### Authentication System
- Secure login system
- Role-based access control
- Protected routes and endpoints

### Room Management
- Complete CRUD operations for rooms
- Real-time room status tracking (Available, Occupied, Maintenance)
- Room properties management (Type, AC/Non-AC, Bed Type)
- Visual status indicators

### Guest Management
- Guest registration and profile management
- Guest history tracking
- Quick guest information access
- Custom status badges for guest states

### Service Request System
- Track and manage service requests
- Status management (Pending, In Progress, Completed)
- Integration with room management
- Service request history

### Modern UI/UX
- Responsive Bootstrap 5.3.0 design
- Fixed sidebar navigation
- Modern color scheme with CSS variables
- Enhanced card layouts and shadows
- Custom status indicators
- Mobile-friendly interface

## Technical Stack

- **Framework**: CodeIgniter 4
- **Database**: MySQL
- **Frontend**: Bootstrap 5.3.0
- **Additional Libraries**: 
  - PHP 7.4+ required
  - Composer for dependency management

## Database Schema

The system uses the following main tables:
- `users` - System users and authentication
- `guests` - Guest information and tracking
- `rooms` - Room details and status
- `service_requests` - Service request tracking

## Development

### Directory Structure

```
hotel_manager/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/         # Database models
│   ├── Views/          # View templates
│   └── Config/         # Configuration files
├── public/            # Public assets
└── writable/         # Logs and cache
```

### Key Files
- `app/Config/Routes.php` - Application routes
- `app/Controllers/Dashboard.php` - Main dashboard controller
- `app/Views/dashboard/layout.php` - Main layout template

## Security Features

- CSRF Protection
- SQL Injection Prevention
- XSS Filtering
- Session Management
- Input Validation


