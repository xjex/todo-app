# Todo App - Laravel 10 + Vue 3

A modern full-stack todo application built with Laravel 10 backend and Vue 3 frontend using Composition API, Pinia for state management, and Tailwind CSS for styling.

## Features

### Core Features
- ✅ Create, read, update, and delete (CRUD) todo items
- ✅ Mark todos as complete/incomplete
- ✅ Clean, responsive UI design
- ✅ Basic form validation
- ✅ RESTful API endpoints for all CRUD operations
- ✅ Database migrations and models
- ✅ Input validation and error handling
- ✅ Proper HTTP status codes

### Bonus Features
- ✅ Todo categories/tags
- ✅ Due dates with sorting
- ✅ Search/filter functionality
- ✅ Responsive design with Tailwind CSS

## Tech Stack

**Backend:**
- Laravel 10
- PHP 8.1+
- MySQL

**Frontend:**
- Vue 3 (Composition API)
- Pinia (State Management)
- Tailwind CSS
- Axios (HTTP Client)
- Vite (Build Tool)

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 18+ and npm
- MySQL 5.7+ or MariaDB 10.3+

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd todo-2025
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Update `.env` file with your database credentials**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_app
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Start the development servers**

   In one terminal, start Laravel:
   ```bash
   php artisan serve
   ```

   In another terminal, start Vite:
   ```bash
   npm run dev
   ```

8. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

## API Endpoints

### Todos

- `GET /api/todos` - Get all todos (supports query parameters: search, category, completed, sort_by, sort_order)
- `POST /api/todos` - Create a new todo
- `GET /api/todos/{id}` - Get a specific todo
- `PUT /api/todos/{id}` - Update a todo
- `PATCH /api/todos/{id}` - Partially update a todo
- `DELETE /api/todos/{id}` - Delete a todo
- `PATCH /api/todos/{id}/toggle` - Toggle completion status

### Request/Response Examples

**Create Todo:**
```json
POST /api/todos
{
  "title": "Complete project",
  "description": "Finish the todo app",
  "category": "Work",
  "due_date": "2025-01-15"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Todo created successfully",
  "data": {
    "id": 1,
    "title": "Complete project",
    "description": "Finish the todo app",
    "category": "Work",
    "due_date": "2025-01-15",
    "completed": false,
    "created_at": "2025-01-01T12:00:00.000000Z",
    "updated_at": "2025-01-01T12:00:00.000000Z"
  }
}
```

## Project Structure

```
todo-2025/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── API/
│   │   │       └── TodoController.php
│   │   └── Middleware/
│   ├── Models/
│   │   └── Todo.php
│   └── Providers/
├── database/
│   └── migrations/
│       └── 2024_01_01_000001_create_todos_table.php
├── public/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── components/
│   │   │   ├── TodoForm.vue
│   │   │   ├── TodoList.vue
│   │   │   ├── TodoItem.vue
│   │   │   └── TodoFilters.vue
│   │   ├── stores/
│   │   │   └── todo.js
│   │   ├── App.vue
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── api.php
│   └── web.php
└── vite.config.js
```

## Development

### Building for Production

```bash
npm run build
```

### Running Tests

```bash
php artisan test
```

## License

MIT License
