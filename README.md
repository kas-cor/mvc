# MVC Framework - Modern PHP Application

A modern, secure, and extensible PHP MVC framework with Dependency Injection, Twig templating, CLI tools, and Docker support.

## 🚀 Features

- **PHP 8.1+** - Modern PHP features including typed properties, attributes, and match expressions
- **Dependency Injection Container** - Auto-wiring and service management
- **Twig Template Engine** - Secure and powerful templating
- **Doctrine ORM** - Database abstraction with migrations
- **Middleware System** - Auth, CSRF, Rate Limiting, Security Headers
- **CLI Console** - Generate controllers, models, run migrations
- **Caching** - File, Redis, Array drivers
- **Logging** - Monolog integration with rotating files
- **Validation** - Chainable validation rules
- **Docker Support** - Complete development environment
- **CI/CD Ready** - GitHub Actions workflow included

## 📋 Requirements

- PHP >= 8.1
- MySQL 8.0 or MariaDB 10.4+
- Composer 2.x
- (Optional) Docker & Docker Compose
- (Optional) Redis

## 🛠️ Installation

### Traditional Installation

1. Clone the repository:
```bash
git clone https://github.com/kas-cor/mvc.git
cd mvc
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Configure your database in `.env`:
```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=mvc_db
DB_USER=root
DB_PASSWORD=secret
```

5. Run migrations:
```bash
php vendor/bin/phinx migrate
# or
php bin/console database:migrate
```

6. Start the development server:
```bash
php -S localhost:8000 -t public
```

### Docker Installation

1. Clone and start containers:
```bash
git clone https://github.com/kas-cor/mvc.git
cd mvc
docker-compose up -d
```

2. Access the application at `http://localhost`
3. phpMyAdmin available at `http://localhost:8080`

## 📁 Project Structure

```
├── app/                    # Application code
│   ├── controllers/       # Controllers
│   ├── core/             # Core classes
│   ├── models/           # Legacy models
│   └── views/            # Legacy views
├── src/                   # Modern source code
│   ├── Cache/            # Cache managers
│   ├── Container/        # DI Container
│   ├── Console/          # CLI commands
│   ├── Controller/       # Base controllers
│   ├── Exception/        # Custom exceptions
│   ├── Logger/           # Logging
│   ├── Middleware/       # HTTP middleware
│   ├── Model/            # Doctrine entities
│   ├── Repository/       # Data repositories
│   ├── Service/          # Business logic
│   └── Validator/        # Validation
├── config/                # Configuration files
│   ├── components/       # Component configs
│   └── services/         # Service definitions
├── cache/                 # Application cache
├── logs/                  # Log files
├── public/                # Web root
├── tests/                 # PHPUnit tests
├── views/templates/       # Twig templates
├── bin/console           # CLI entry point
├── docker-compose.yml    # Docker configuration
└── Dockerfile            # Docker image
```

## 🎯 Usage

### Dependency Injection

```php
use src\Container\Container;

$container = new Container();

// Register services
$container->share('logger', fn() => new Logger());
$container->share('cache', fn() => new CacheManager());

// Auto-resolve classes
$service = $container->get(MyService::class);

// Call methods with DI
$container->call([$controller, 'action']);
```

### Creating Controllers

```bash
php bin/console make:controller Product
```

Generates: `app/controllers/ProductController.php`

### Creating Models

```bash
php bin/console make:model Category
```

Generates: `src/Model/Category.php` with Doctrine annotations

### Running Commands

```bash
# List routes
php bin/console route:list

# Clear cache
php bin/console cache:clear

# Run migrations
php bin/console database:migrate

# Seed database
php bin/console database:seed

# Run all tests
composer test

# Static analysis
composer phpstan

# Fix code style
composer cs-fix
```

### Using Twig Templates

```php
// In controller
return $this->render('home/index', [
    'title' => 'Welcome',
    'user' => $user
]);
```

```twig
{# views/templates/home/index.html.twig #}
{% extends "base.html.twig" %}

{% block content %}
    <h1>{{ title }}</h1>
    <p>Hello, {{ user.name }}!</p>
{% endblock %}
```

### Middleware

```php
// Add to routes
Route::add('/admin', 'AdminController@index')
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
```

Available middleware:
- `AuthMiddleware` - Require authentication
- `CsrfMiddleware` - CSRF protection
- `RateLimitMiddleware` - Rate limiting
- `SecurityHeadersMiddleware` - Security headers

## 🧪 Testing

```bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/Container/ContainerTest.php

# With coverage
vendor/bin/phpunit --coverage-html coverage
```

## 🔒 Security Features

- SQL Injection prevention (parameterized queries)
- XSS protection (auto-escaping in Twig)
- CSRF tokens
- Password hashing (bcrypt)
- Security headers (XSS, Clickjacking, etc.)
- Rate limiting
- Input validation

## 📦 Built-in Services

- **CacheManager** - Multiple cache drivers
- **Logger** - Monolog-based logging
- **Validator** - Chainable validation
- **SessionService** - Session management
- **AuthService** - Authentication helpers

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `composer test`
5. Submit a pull request

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

## 🙏 Credits

- Author: kas-cor
- Contributors: See GitHub contributors page

## 📞 Support

- Issues: https://github.com/kas-cor/mvc/issues
- Email: kascorp@gmail.com
