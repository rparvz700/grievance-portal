# Grievance Portal - Complete Setup Guide

## Step-by-Step Installation

### 1. Create Laravel Project

```bash
composer create-project laravel/laravel grievance-portal
cd grievance-portal
```

### 2. Install Dependencies

```bash
composer require laravel/ui
php artisan ui bootstrap --auth
npm install
npm run build
```

### 3. Configure Environment

Edit `.env` file:

```env
APP_NAME="Grievance Portal"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grievance_portal
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@grievance.com"
MAIL_FROM_NAME="${APP_NAME}"

FILESYSTEM_DISK=local
```

### 4. Create Database

```bash
mysql -u root -p
CREATE DATABASE grievance_portal;
exit
```

### 5. Create Migrations

Run the following commands to create migration files:

```bash
php artisan make:migration add_role_to_users_table
php artisan make:migration create_categories_table
php artisan make:migration create_grievances_table
php artisan make:migration create_attachments_table
php artisan make:migration create_status_histories_table
```

Copy the migration code from the artifacts provided into respective files in `database/migrations/`.

### 6. Create Models

```bash
php artisan make:model Category
php artisan make:model Grievance
php artisan make:model Attachment
php artisan make:model StatusHistory
```

Copy the model code from the artifacts into respective files in `app/Models/`.

### 7. Create Middleware

```bash
php artisan make:middleware CheckAdmin
php artisan make:middleware CheckSuperAdmin
```

Copy the middleware code into `app/Http/Middleware/`.

### 8. Create Controllers

```bash
php artisan make:controller GrievanceController
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/GrievanceController
php artisan make:controller Admin/CategoryController
php artisan make:controller HomeController
```

Copy the controller code from the artifacts.

### 9. Create Notification

```bash
php artisan make:notification NewGrievanceNotification
```

Copy the notification code into `app/Notifications/`.

### 10. Update Routes

Replace content of `routes/web.php` with the routes artifact.

### 11. Update Bootstrap Configuration

Replace content of `bootstrap/app.php` with the bootstrap artifact.

### 12. Create Views Directory Structure

```bash
mkdir -p resources/views/grievances
mkdir -p resources/views/admin/grievances
mkdir -p resources/views/admin/categories
mkdir -p resources/views/layouts
```

Copy all view files from the artifacts into their respective directories.

### 13. Run Migrations

```bash
php artisan migrate
```

### 14. Run Seeders

```bash
php artisan db:seed
```

This will create:
- Super Admin: superadmin@grievance.com / password
- Regular Admin: admin@grievance.com / password
- Default Categories

### 15. Create Storage Link

```bash
php artisan storage:link
```

### 16. Configure Queue (Optional but Recommended)

For email notifications to work asynchronously:

Update `.env`:
```env
QUEUE_CONNECTION=database
```

Create queue table:
```bash
php artisan queue:table
php artisan migrate
```

Run queue worker:
```bash
php artisan queue:work
```

### 17. Start Development Server

```bash
php artisan serve
```

## Access Points

- **Public Portal**: http://localhost:8000/grievances/create
- **Admin Login**: http://localhost:8000/login
- **Admin Dashboard**: http://localhost:8000/admin/dashboard

## Default Credentials

**Super Admin:**
- Email: superadmin@grievance.com
- Password: password

**Regular Admin:**
- Email: admin@grievance.com
- Password: password

## Features Summary

### Public Features
- ✅ Anonymous grievance submission
- ✅ Multiple file uploads (Images, PDF, Video)
- ✅ File validation (type, size, count)
- ✅ Unique reference number generation
- ✅ Success confirmation page

### Admin Features
- ✅ Dashboard with statistics
- ✅ Analytics charts (Status, Category, Monthly trend)
- ✅ Grievance listing with filters
- ✅ Search functionality
- ✅ Detailed grievance view
- ✅ Category assignment
- ✅ Status management
- ✅ Investigation report
- ✅ Admin notes
- ✅ File download
- ✅ Status history tracking
- ✅ Email notifications

### Super Admin Features
- ✅ All admin features
- ✅ Category management (CRUD)
- ✅ Delete grievances
- ✅ Delete categories

## File Upload Configuration

Maximum file size: 10MB
Maximum files per submission: 5
Allowed types: JPG, JPEG, PNG, PDF, MP4

To change these, edit:
1. `app/Http/Controllers/GrievanceController.php` - validation rules
2. `php.ini` - upload_max_filesize and post_max_size

## Security Features

- ✅ CSRF protection
- ✅ File validation
- ✅ Role-based access control
- ✅ Secure file storage (outside public directory)
- ✅ Input sanitization
- ✅ SQL injection prevention (Eloquent ORM)

## Troubleshooting

### Issue: File upload errors
**Solution**: Check `php.ini` settings:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 5
```

### Issue: Email not sending
**Solution**: 
1. Check `.env` mail configuration
2. Use Mailtrap or similar for testing
3. Run queue worker: `php artisan queue:work`

### Issue: Permission denied on storage
**Solution**:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Issue: Images not displaying
**Solution**:
```bash
php artisan storage:link
```

## Production Deployment Checklist

- [ ] Update `.env` with production values
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper mail server
- [ ] Set up SSL certificate
- [ ] Configure queue workers as system service
- [ ] Set proper file permissions
- [ ] Enable Laravel scheduler (for future features)
- [ ] Set up database backups
- [ ] Configure AWS S3 or similar for file storage
- [ ] Optimize application: `php artisan optimize`

## Future Enhancements

Potential features to add:
- Public tracking by reference number
- SMS notifications
- Multi-language support
- Advanced analytics
- Export functionality (Excel, PDF)
- Bulk actions
- API endpoints
- Mobile app integration

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Bootstrap Documentation: https://getbootstrap.com/docs