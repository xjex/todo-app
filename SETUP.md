# Setup Guide

## Quick Start

Follow these steps to get the Todo App running:

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup

Edit `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_app
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```sql
CREATE DATABASE todo_app;
```

Run migrations:

```bash
php artisan migrate
```

### 4. Start Development Servers

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```

### 5. Access the Application

Open your browser and navigate to:
```
http://localhost:8000
```

## Testing

Run the test suite:

```bash
php artisan test
```

## Building for Production

```bash
npm run build
```

## Troubleshooting

### Issue: "Class 'App\Http\Controllers\Controller' not found"
**Solution:** Run `composer dump-autoload`

### Issue: "Vite manifest not found"
**Solution:** Make sure you're running `npm run dev` in a separate terminal

### Issue: Database connection error
**Solution:** 
1. Check your `.env` database credentials
2. Ensure MySQL is running
3. Verify the database exists

### Issue: CORS errors
**Solution:** The API endpoints are configured to allow CORS. If issues persist, check `config/cors.php`

## Project Structure

- `app/Http/Controllers/API/` - API Controllers
- `app/Models/` - Eloquent Models
- `database/migrations/` - Database Migrations
- `resources/js/` - Vue.js frontend
- `resources/js/components/` - Vue Components
- `resources/js/stores/` - Pinia Stores
- `routes/api.php` - API Routes
- `routes/web.php` - Web Routes

## Features Implemented

✅ Full CRUD operations
✅ Mark todos as complete/incomplete
✅ Categories/Tags
✅ Due dates with sorting
✅ Search functionality
✅ Filter by category and status
✅ Responsive UI with Tailwind CSS
✅ Form validation
✅ Error handling
✅ RESTful API
✅ Automated tests

