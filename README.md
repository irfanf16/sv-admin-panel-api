# StaffViz Admin Panel API

Laravel 10 modular REST API powering the `sv-angular-panel` SPA — handles company lifecycle management, Stripe billing, module configuration, RBAC, and cross-service internal communication for the **StaffViz** SaaS platform.

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-Billing-635BFF?style=flat&logo=stripe&logoColor=white)
![Laravel Sanctum](https://img.shields.io/badge/Sanctum-Auth-FF2D20?style=flat)
![nwidart/modules](https://img.shields.io/badge/Modular-nwidart%2Flaravel--modules-orange?style=flat)
![Spatie](https://img.shields.io/badge/Spatie-Permissions%2FQuery--Builder-purple?style=flat)

## Features

**Authentication** — Register, login, logout, JWT refresh via Sanctum, password reset.

**Internal Service Communication** (`/api/staffviz/*`) — Protected by `EnsureInternalCommunication` middleware, consumed by `sv-user-panel`:
- Company CRUD: create, update, delete, invite owner, child company creation
- User CRUD, authentication, verification, company token reset
- Stripe subscriptions: show, update, update items, cancel, quantity updates
- Stripe cards, payment methods, invoices: list, pay, upcoming invoice
- Company lifecycle emails (trial, active, grace period, close account)
- GoHighLevel CRM webhooks per plan tier
- Affiliate save/retrieve, chatbot guest session tracking

**Plans & Packages** — CRUD for plans, features, add-ons, coupons, billing categories (per_unit / one_time).

**Settings** — Global configuration, cache management, email templates, holiday calendar.

**Users & Roles** — CRUD with ability middleware (`users.read/create/update/delete`), export, Spatie RBAC with custom role types.

**CMS** — Pages, sections, content blocks, file manager with upload/move/delete.

**Talk to Sales / Support / Close Accounts** — Lead capture, support tickets, account-closure request management.

## Database Schema (`admin_laravel_with_staffviz`)

| Table | Key Columns | Purpose |
|---|---|---|
| `users` | `id`, `email`, `password`, `first_name`, `last_name`, `superuser` | Admin portal users |
| `companies` | `id`, `title`, `plan_id`, `price_id`, `grace_period`, `payment_status`, `advocate` | SaaS company tenants |
| `subscriptions` | `company_id`, `subscription_id`, `plan_id`, `price_id`, `plan_staus`, `plan_expiry` | Company Stripe subscriptions |
| `products` | `id`, `stripe_id`, `product` (JSON) | Stripe product mirror |
| `categories` | `id`, `title`, `price_type`, `discount_type`, `frequency` (JSON) | Billing plan categories |
| `modules` | `id`, `name`, `type`, `status`, `dependent_module_id` | Platform feature modules |
| `configurations` | `id`, `key`, `value` | Global configuration KV store |
| `emails` | `id`, `subject`, `body`, `type`, `label` | Email templates |
| `holidays` | `id`, `name`, `date`, `recurring` | Holiday calendar |
| `talk_to_sales` | `first_name`, `last_name`, `company`, `email`, `phone`, `reason` | Sales leads |
| `company_close_account` | `company_id`, `plan_type`, `reason`, `closing_time` | Account-closure requests |
| `affiliate` | `email`, `affiliate_id`, `referral_code`, `commission` | Affiliate tracking |
| `invoices` | `company_id`, `stripe_invoice_id`, `amount`, `status`, `paid_at` | Stripe invoice mirror |
| `roles` / `permissions` | Spatie standard schema | RBAC |
| `files` / `folders` | `id`, `name`, `path`, `extension`, `filesize` | CMS file manager |
| `pages` / `page_sections` | `id`, `slug`, `title`, `body`, `sort_order` | CMS content |
| `deletion_log` | `entity_type`, `entity_id`, `deleted_by`, `reason` | Soft-delete audit |
| `java_client_release` | `version`, `platform`, `url`, `release_notes` | Desktop client versions |

## Architecture

```
sv-angular-panel (Angular SPA)
        │  REST + Sanctum + ability middleware
        ▼
sv-admin-panel-api (Laravel 10, nwidart/modules)
        ├── Modules/Plans      — Stripe products/subscriptions
        ├── Modules/Dashboard  — Company management
        ├── Modules/Users      — Admin user management
        ├── Modules/Settings   — Config, emails, holidays
        ├── Modules/Files      — CMS file manager
        └── Modules/Affiliate  — Affiliate program
        │
        ├── Stripe API         (subscriptions, invoices, customers)
        ├── sv-user-panel  ←── /api/staffviz/* internal calls
        └── sv-frontend    ←── company registration proxied via Guzzle
```

## Getting Started

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan serve
```

## Environment Variables

| Variable | Purpose |
|---|---|
| `DB_DATABASE` | Master DB (`admin_laravel_with_staffviz`) |
| `JS_KEY` | Internal communication secret (shared with `sv-user-panel`) |
| `STRIPE_SECRET_KEY` | Stripe billing key |
| `MAIL_*` | SMTP (Office 365 staging) |
| `REDIS_*` | Cache and queues |
| `AWS_*` | S3 file storage |

## License
MIT
