# Hospital Management System

A modern, full-featured PHP-based hospital management system with role-based access control, appointment scheduling, patient records management, and billing functionality.

## 🏥 Features

### Core Functionality
- **User Authentication & Authorization** - Secure login system with role-based access control
- **Doctor Management** - Create, read, update, and delete doctor profiles with specialties
- **Patient Management** - Comprehensive patient records including demographics and medical history
- **Staff Management** - Manage hospital staff with shift scheduling capabilities
- **Appointment System** - Schedule and manage doctor-patient appointments
- **Medicine Inventory** - Track medicines with dosage and duration information
- **Billing System** - Create, manage, and track patient bills with itemized charges

### User Roles
- **Admin** - Full system access, manage all resources
- **Doctor** - View patients, manage appointments
- **Patient** - Book appointments, view medical records, check bills
- **Staff** - Hospital operations management

### User Interface
- Modern, responsive design with CSS Grid and Flexbox
- Animated components with smooth transitions
- 3D card effects for enhanced visual appeal
- Particle background animation
- Mobile-friendly interface
- Form validation with real-time feedback
- Scroll reveal animations

## 🛠️ Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+ / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Database Connection:** PDO (PHP Data Objects)
- **Authentication:** Session-based with bcrypt password hashing

## 📋 Database Schema

### Tables
- `doctors` - Doctor information and specialties
- `patients` - Patient demographics and medical details
- `staff` - Hospital staff records
- `medicine` - Medication inventory management
- `users` - User accounts with role-based access
- `appointments` - Scheduled doctor-patient appointments
- `bills` - Patient billing records
- `bill_items` - Individual charges within bills

### Relationships
- Users link to patients, doctors, or staff via foreign keys
- Appointments link doctors and patients
- Bills are associated with patients
- Bill items reference bills for itemized charges

## 📁 Project Structure

```
Hospital Management System/
├── auth/                    # Authentication pages
│   ├── login.php           # User login
│   ├── logout.php          # User logout
│   └── signup.php          # User registration
├── crud/                    # CRUD operations for each module
│   ├── doctors/            # Doctor management
│   ├── patients/           # Patient management
│   ├── staff/              # Staff management
│   ├── medicine/           # Medicine management
│   ├── appointments/       # Appointment management
│   ├── bills/              # Bill management
│   └── bill_items/         # Bill items management
├── user/                    # User dashboard pages
│   ├── appointments.php    # View appointments
│   ├── bills.php           # View bills
│   └── book_appointment.php # Book new appointment
├── assets/                  # Frontend assets
│   ├── css/                # Stylesheets
│   │   ├── main.css        # Main styles
│   │   ├── components.css  # Component styles
│   │   ├── animations.css  # Animation effects
│   │   └── 3d-cards.css    # 3D card effects
│   └── js/                 # JavaScript files
│       ├── app.js          # Main app utilities
│       └── background.js   # Background animation
├── config.php              # Database configuration
├── auth_helper.php         # Authentication helpers
├── init_db.php             # Database initialization script
├── index.php               # Main entry point
├── admin.php               # Admin dashboard
├── dashboard.php           # User dashboard
└── home.php                # Landing page
```

## 🚀 Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB
- Apache server (or similar)
- Composer (optional, for dependency management)

### Step 1: Clone the Repository
```bash
git clone https://github.com/fastudyrevision-oss/Basic_hospital_management_system.git
cd Hospital\ management
```

### Step 2: Configure Database
1. Open `config.php` and update database credentials:
   ```php
   $host = 'localhost';
   $db   = 'hospital_db';
   $user = 'root';
   $pass = 'your_password'; // Update with your MySQL password
   ```

2. Create the database:
   ```bash
   mysql -u root -p
   > CREATE DATABASE hospital_db;
   > EXIT;
   ```

### Step 3: Initialize Database
Run the database initialization script:
```bash
php init_db.php
```

This will create all necessary tables with proper relationships and constraints.

### Step 4: Run the Application
1. Place the project folder in your Apache `htdocs` directory
2. Access the application at: `http://localhost/Hospital%20management/`
3. You'll be redirected to the home page or login based on your session status

## 🔐 Security Features

- **Password Security** - Passwords are hashed using bcrypt
- **SQL Injection Prevention** - Uses prepared statements with PDO
- **XSS Protection** - HTML entity encoding for user inputs
- **Session Management** - Secure session-based authentication
- **Input Sanitization** - All user inputs are sanitized using `htmlspecialchars()`
- **Role-Based Access Control** - Middleware functions enforce role-based permissions

### Default Admin Access
After running `init_db.php`, create your first admin account through the signup page or modify the `init_db.php` script to seed default credentials.

## 📖 Core PHP Helpers

### Authentication Helpers (`auth_helper.php`)
- `is_logged_in()` - Check if user is authenticated
- `has_role($role)` - Verify user has specific role
- `require_login()` - Enforce login requirement
- `require_role($role)` - Enforce role requirement
- `get_logged_in_user()` - Retrieve current user data

### CRUD Helpers (`config.php`)
- `get_all($table, $pdo)` - Fetch all records from table
- `get_by_id($table, $id, $pdo)` - Fetch single record by ID
- `delete_record($table, $id, $pdo)` - Delete record by ID
- `sanitize_input($data)` - Sanitize user input
- `redirect($page)` - Redirect to specified page

## 🎨 Frontend Features

### CSS Modules
- **main.css** - Core styling and layout
- **components.css** - Reusable component styles
- **animations.css** - Smooth animations and transitions
- **3d-cards.css** - 3D card effects and transforms

### JavaScript Features
- Scroll reveal animations
- Smooth scrolling
- Form validation
- Modal dialogs
- Dynamic content loading
- Background particle animation

## 🔄 Common Workflows

### Book an Appointment (Patient)
1. Login as patient
2. Navigate to "Book Appointment"
3. Select doctor and date/time
4. Submit appointment request

### Manage Doctors (Admin)
1. Login as admin
2. Click on "Doctors" in admin panel
3. View list, create new, edit, or delete doctors

### View Medical Bills (Patient)
1. Login as patient
2. Navigate to "Bills" in user dashboard
3. View itemized charges and payment status

### Manage Appointments (Doctor)
1. Login as doctor
2. Access your appointments
3. Update notes or appointment status

## 🐛 Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check credentials in `config.php`
- Ensure database `hospital_db` exists
- Verify PDO MySQL extension is installed

### Session Issues
- Clear browser cookies
- Ensure `session_start()` is called at top of page
- Check PHP session settings in `php.ini`

### Display Issues
- Clear browser cache
- Check CSS file paths
- Verify all assets are in correct directories
- Check browser console for JavaScript errors

## 📝 Development Notes

### Adding New Modules
1. Create table in `init_db.php`
2. Create CRUD files in `crud/new_module/` directory
3. Add navigation links to admin/user dashboards
4. Implement helper functions as needed

### Modifying Database Schema
1. Update `init_db.php` with new schema
2. Run `init_db.php` to recreate tables (data will be lost)
3. Or manually execute ALTER TABLE queries in MySQL

### Customizing Styling
- Edit CSS files in `assets/css/`
- Modify color scheme in CSS variables
- Adjust animations as needed

## 📄 License

This project is open source and available under the MIT License.

## 🤝 Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Submit a pull request

## 👥 Support

For issues, questions, or suggestions, please:
- Create an issue on GitHub
- Contact the development team
- Check documentation in code comments

## 🎯 Future Enhancements

- [ ] Email notifications for appointments
- [ ] SMS reminders
- [ ] Advanced reporting and analytics
- [ ] Prescription management
- [ ] Lab reports integration
- [ ] Multi-language support
- [ ] PWA functionality
- [ ] API for mobile apps
- [ ] Payment gateway integration
- [ ] Appointment availability calendar

---

**Version:** 1.0.0  
**Last Updated:** February 2026  
**Repository:** https://github.com/fastudyrevision-oss/Basic_hospital_management_system.git
