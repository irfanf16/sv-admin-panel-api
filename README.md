# Service Vendor - Admin Panel API

**RESTful Laravel API** powering the admin panel of a multi-vendor service marketplace.

## Tech Stack

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![REST API](https://img.shields.io/badge/REST_API-009688?style=flat)

## Features

- Analytics and reporting endpoints
- Vendor approval and management
- Category and service configuration
- Image handling with Croppa
- Model-level caching for performance
- Sanctum API authentication

## Getting Started

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate && php artisan serve
```

## License
MIT
