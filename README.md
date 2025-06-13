## Todo API

A RESTful API for managing todo lists with features like date filtering, status tracking, and priority management.

## Features

- CRUD operations for todo items
- Date range filtering
- Status tracking (pending, open)
- Priority levels (low, medium, high)
- Time tracking
- Data export functionality

## API Endpoints

### Todo List Management

#### Create Todo
```http
POST /api/todos
```
Request Body:
```json
{
    "title": "string",
    "assignee": "string",
    "due_date": "YYYY-MM-DD",
    "status": "pending|open",
    "priority": "low|medium|high",
    "time_tracked": "integer"
}
```

#### Get All Todos
```http
GET /api/todos
```
Query Parameters:
- `start`: Start date for filtering
- `end`: End date for filtering
- `min`: Minimum time tracked
- `max`: Maximum time tracked

#### Get Single Todo
```http
GET /api/todos/{id}
```

#### Update Todo
```http
PUT /api/todos/{id}
```
Request Body:
```json
{
    "title": "string",
    "assignee": "string",
    "due_date": "YYYY-MM-DD",
    "status": "pending|open",
    "priority": "low|medium|high",
    "time_tracked": "integer"
}
```

#### Delete Todo
```http
DELETE /api/todos/{id}
```

### Data Export

#### Export Todos
```http
GET /api/todos/export
```
Query Parameters:
- `start`: Start date for filtering
- `end`: End date for filtering
- `min`: Minimum time tracked
- `max`: Maximum time tracked

## Requirements

- PHP >= 8.0
- Laravel Framework
- MySQL Database
- Composer

## Installation

1. Clone the repository
```bash
git clone [repository-url]
cd todo-api
```

2. Install dependencies
```bash
composer install
```

3. Copy environment file and configure
```bash
cp .env.example .env
```
Edit `.env` with your database credentials

4. Generate application key
```bash
php artisan key:generate
```

5. Run database migrations
```bash
php artisan migrate
```

6. Start the development server
```bash
php artisan serve
```

## Usage

The API is ready to use at `http://localhost:8000/api/`

## Error Handling

The API returns standard HTTP status codes:
- 200: Success
- 201: Created
- 400: Bad Request
- 404: Not Found
- 500: Internal Server Error

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

MIT License - see LICENSE file

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
