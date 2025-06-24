# Hotel Management System

A modern and comprehensive hotel management solution built with CodeIgniter 4, featuring an intuitive dashboard for managing rooms, guests, and service requests.

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

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd hotel_manager
```

2. Install dependencies:
```bash
composer install
```

3. Configure your environment:
- Copy `env` to `.env`
- Update database credentials in `.env`
- Set your app's base URL in `.env`

4. Set up the database:
```bash
php spark migrate
php spark db:seed AdminUserSeeder
```

5. Start the development server:
```bash
php spark serve
```

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

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


## Support

For support and queries, please open an issue in the repository.


