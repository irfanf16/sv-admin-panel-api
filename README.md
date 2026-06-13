# StaffViz Admin Panel API

> **Laravel 10 modular API** — Central control plane for the [StaffViz](https://www.staffviz.com) SaaS platform. Handles multi-tenant company provisioning, Stripe billing, RBAC for back-office users, and acts as an internal API gateway for the StaffViz application.

![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?style=flat-square&logo=php&logoColor=white)
![Sanctum](https://img.shields.io/badge/Laravel_Sanctum-3.2-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-13.x-635BFF?style=flat-square&logo=stripe&logoColor=white)
![nwidart](https://img.shields.io/badge/nwidart_modules-10.0-4479A1?style=flat-square)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white)

---

## Table of Contents
- [What is this?](#what-is-this)
- [Architecture](#architecture)
- [Tech Stack](#tech-stack)
- [Laravel Modules (9 modules)](#laravel-modules-9-modules)
- [Authentication](#authentication)
- [Database Schema](#database-schema)
- [Route Groups](#route-groups)
- [Spatie RBAC](#spatie-rbac)
- [Stripe Integration](#stripe-integration)
- [Multi-Tenant Provisioning](#multi-tenant-provisioning)
- [Affiliate Integration](#affiliate-integration)
- [Infrastructure](#infrastructure)

---

## What is this?

StaffViz has three backend services:
```
sv-admin-panel-api   — THIS REPO — Back-office API (company management, billing, RBAC)
sv-user-panel        — Employee-facing Laravel app (per-company DB switching)
(external DB servers) — Per-company isolated MySQL databases
```

This API is consumed by:
- `sv-angular-panel` — Angular 18 back-office SPA
- `sv-user-panel` — internally, for login validation and credential resolution

---

## Architecture

```
+-----------------------------------------------+
|         sv-admin-panel-api (Laravel 10)        |
|                                                 |
|  9 nwidart Modules:                             |
|  Affiliate | Articles | Contacts | Dashboard   |
|  Files | Plans | Settings | Support | Users    |
|                                                 |
|  Auth: Sanctum (primary) + Passport (OAuth2)   |
|  Internal: EnsureInternalCommunication token   |
|                                                 |
|  Master DB (typicms_ prefix):                  |
|  Companies, users, plans, billing, Stripe      |
|                                                 |
|  Per-tenant provisioning:                       |
|  createNewDatabaseService -> isolated MySQL DB  |
|  per company (named by company_initial)         |
+-----------------------------------------------+
         |              |              |
   sv-angular-panel  sv-user-panel  Stripe API
   (Angular 18 SPA)  (Laravel app)  Trackdesk
```

---

## Tech Stack

| Package | Version | Purpose |
|---|---|---|
| `laravel/framework` | ^10.10 | Core framework |
| `nwidart/laravel-modules` | ^10.0 | Modular architecture (9 modules) |
| `laravel/sanctum` | ^3.2 | Primary API token auth |
| `laravel/passport` | ^12.3 | OAuth2 (secondary) |
| `spatie/laravel-permission` | ^5.10 | RBAC (custom `portal_` table prefix) |
| `stripe/stripe-php` | ^13.11 | Stripe billing + subscriptions |
| `spatie/laravel-query-builder` | ^5.2 | Filterable/sortable query builder |
| `spatie/eloquent-sortable` | ^4.0 | Drag-and-drop model ordering |
| `spatie/laravel-translatable` | ^6.5 | Multi-language content |
| `genealabs/laravel-model-caching` | ^0.13.4 | Model-level query caching |
| `intervention/image` | ^2.7 | Image processing |
| `league/flysystem-aws-s3-v3` | 3.0 | AWS S3 storage |
| `maatwebsite/excel` | * | Excel export |
| `jenssegers/agent` | ^2.6 | Device/browser detection at login |
| `guzzlehttp/guzzle` | ^7.2 | HTTP calls to external services |

**Infrastructure:** Docker (PHP 8.2-fpm), database queue (2 workers: default + emails), Office365 SMTP.

---

## Laravel Modules (9 Modules)

| Module | Purpose |
|---|---|
| `Affiliate` | Trackdesk + Firstpromoter affiliate integration, webhook handling |
| `Articles` | Help/knowledge-base articles with pages and status management |
| `Contacts` | Contact form submissions with Spatie QueryBuilder filtering |
| `Dashboard` | Company CRUD, user management, company provisioning, status toggles |
| `Files` | File/media management (Admin + API controllers) |
| `Plans` | Stripe products/plans/add-ons, categories, coupons, subscriptions, invoices |
| `Settings` | Email templates, system configurations, holiday management |
| `Support` | Encrypted agent login codes for support impersonation |
| `Users` | Back-office portal user routes |

---

## Authentication

### 1. Sanctum (primary — all admin routes)
```
POST /api/login
  -> validates email + password
  -> collects ip, system_info, device_info (jenssegers/agent)
  -> if superuser: token abilities = ['*']
  -> else: embed ALL Spatie permissions as token ability strings
  -> return: token + permissions[] + superuser flag

GET /api/refresh
  -> issue new token with fresh permissions
  -> delete old token
```

Tokens stored in `personal_access_tokens` (extended with `ip`, `system_info`, `device_info` columns).

### 2. Internal API (machine-to-machine)
```
Header: token: {INTERNAL_SYSTEM_COMMUNICATION}
Routes: /api/staffviz/*  (35+ endpoints)
Used by: sv-user-panel -> this API for login, credential lookup, billing
```

### 3. Superusers
`users.superuser = true` -> token ability `['*']` -> bypasses all Spatie permission checks.

---

## Database Schema

**Table prefix:** `typicms_` on all master DB tables.

### Identity & Access
```
users              id, email, password, activated, superuser, first_name, last_name,
                   phone, image, stripe_customer_id, api_token
personal_access_tokens  Sanctum tokens + ip, system_info, device_info
portal_roles        Spatie roles (custom table name)
portal_permissions  Spatie permissions (custom table name)
portal_model_has_roles / portal_model_has_permissions / portal_role_has_permissions
role_types          role_id, role_type  (differentiates admin/advocate/agent)
```

### Company Management
```
companies           id, title, company_initial, no_of_employee, super_admin_id,
                    instance_id, logo, timezone, plan_id, plan_staus, subscription_id,
                    plan_expiry, status, has_setup, formation_type, payment_status,
                    grace_period, grace_period_name, grace_period_start_date,
                    company_admin_emails (JSON), advocate_id, closure_plan, plan_users

instances           id, instance, db_host, db_port, db_username, db_password, db_driver
                    (ALL fields AES-encrypted with ENC_KEY env var)

company_close_account  company_id, plan_type, reason, message, closing_time
company_configuration  per-company config key-values
```

### Billing & Stripe
```
products            id, stripe_id, product (JSON — full Stripe product object), sort_by
categories          id, title, proration, price_type(per_unit/one_time), discount_type, frequency(JSON)
category_features / category_features_list   plan tier feature relationships
subscriptions       company_id, subscription_id, plan_id, price_id, plan_staus, plan_expiry
invoices            invoice_id, stripe_customer_id, subscription_id, status, invoice(JSON)
addons_histories    company_id, product_id, type, start_date, expiry_date, remove_date, status
talk_to_sales       first_name, last_name, company, email, number_of_employees, reason
```

### Feature Module System (70+ modules — used for billing feature gating)
```
modules             id, title, url, icon, parent_module_id, module_order,
                    module_type, rules(JSON), status, dependent_module_id
module_features     billing features linked to modules
module_features_list  id, type(1=info/2=implementation), feature_key, feature_value, status
```

Top-level modules: My Dashboard, My Assignments, Time & Attendance, Projects, Courses, Quizzes, Reports, Recruitment, HR Management, Live Dashboard, Web/App Tracking, Snapshots, Chat, Zapier, Timesheets, Groups, Shifts, Breaks, and 50+ sub-modules.

### Affiliate & Misc
```
affiliates          tenantId, publicId, email, tierId, tierName, accountId  (Trackdesk)
emails              service(enum), subject, body, variables(JSON)
configurations      config_key, config_val (per-company defaults)
holidays            name, dates, year
jobs / failed_jobs  Queue tables
```

---

## Route Groups

**Public:**
```
POST /api/register
POST /api/login
GET  /api/refresh
POST /api/password/email
POST /api/password/reset
POST /api/stripe/webhook
POST /api/trackdesk/webhook
```

**Internal (EnsureInternalCommunication header):**
```
/api/staffviz/*  — 35+ endpoints:
  User CRUD, company CRUD, subscription management
  Stripe card/payment_method proxying
  Affiliate registration
  Addons history, child company creation
  Course job dispatching, bulk user terminations
```

**Authenticated Admin (Sanctum + ability middleware):**
```
Settings, Pages/Sections, Roles, Users  (abilities: settings.*/pages.*/permissions.*/users.*)
Dashboard: company CRUD, provisioning, status management  (ability: company_management.*)
Plans: features, coupons, add-ons, subscriptions, invoices  (ability: plan_packages.*/plans.*/products.*)
Support: validate_code, agent login
Talk to Sales, Contacts listing
```

---

## Spatie RBAC

**Custom table names** (all prefixed `portal_`):
- `portal_roles`, `portal_permissions`, `portal_model_has_permissions`, `portal_model_has_roles`, `portal_role_has_permissions`

**How it works:** All Spatie permissions are embedded into the Sanctum token at login time. Route middleware `abilities:permission.name` validates token contains the required ability — no DB lookup per request.

**Permission naming convention:**
```
settings.read / settings.update / settings.delete
pages.read / pages.create / pages.update / pages.delete
permissions.read / permissions.create / permissions.update / permissions.delete
users.read / users.create / users.update / users.delete
company_management.read / company_management.create / company_management.update
plan_packages.read / features.read / features.create / features.update / features.delete
coupons.* / addons.* / plans.* / products.*
support.read / talk_to_sales.read
```

---

## Stripe Integration

**Service:** `App\Services\StripeService` — wraps `\Stripe\StripeClient` (API version: `2023-10-16`).

**Capabilities:**
- Products: create, update, search, autopaged list
- Prices: create, search, deactivate
- Coupons: create, update, delete, apply to subscription
- Customers: upsert by email
- Subscriptions: create, retrieve, update (items/quantity), cancel, pause/resume
- Payment Methods: list, detach
- Cards: retrieve by customer
- Invoices: list, update, pay, upcoming preview
- Setup Intents: create + confirm (card capture without charge)

**Webhook (`POST /api/stripe/webhook`):**
Verified via `Stripe\Webhook::constructEvent()`. Dispatches `StripWebhookJob` async. Handles:
- `customer.subscription.*` (created, deleted, updated, paused, resumed, trial_will_end)
- `invoice.*` (paid, payment_failed, upcoming)

**Billing lifecycle jobs:**
| Job | Trigger |
|---|---|
| `StartTrialJob` | Trial begins |
| `LastDayOfTrialJob` | Day before trial ends |
| `XDaysLeftJob` | Configurable days-left reminder |
| `GracePeriodJob` | Post-payment-failure grace period |
| `StripWebhookJob` | All Stripe events |

**Grace Period Service:** Monitors companies with `past_due` status, sends escalating emails by days elapsed, eventually blocks user access.

**Local product mirror:** Stripe product/price data mirrored into `products` table as JSON blob — allows admin panel filtering without hitting Stripe API.

---

## Multi-Tenant Provisioning

When a new company is created:

```
1. Admin creates company via POST /api/company/store
2. createNewDatabaseService::createNewDatabase()
      |
      v
   Fetch AES-encrypted instance credentials from instances table
   (decrypted at runtime using ENC_KEY env var)
      |
      v
   DB::statement("CREATE DATABASE {company_initial}")
      |
      v
   Register dynamic Laravel connection for new DB
      |
      v
   Artisan::call('migrate', ['--database' => company_initial])
   Artisan::call('db:seed', ['--database' => company_initial])
      |
      v
   Assign Stripe customer to company owner
   Send company setup success email
```

**Batch migration** (deploy schema changes to all tenants):
```bash
php artisan custommigrationtth          # migrate all company DBs
php artisan custommigrationtth rollback # rollback all company DBs
```

---

## Affiliate Integration

| Platform | Purpose |
|---|---|
| **Trackdesk** | `App\Services\TrackdeskService` — create affiliates, handle `POST /api/trackdesk/webhook` |
| **Firstpromoter** | `App\Services\FirstpromoterService` — promoter lookup via `GET /api/staffviz/affiliate/{email}` |

Affiliate data stored locally in `affiliates` table (Trackdesk fields: tenantId, publicId, tierId, tierName, accountId, sourceId, fraudSuspicion).

---

## Infrastructure

```dockerfile
# Docker: PHP 8.2-fpm
# Startup: runs migrations.sh, then:
php artisan serve       # API server
php artisan schedule:work
php artisan queue:work --queue=emails   # email queue worker
php artisan queue:work                  # default queue worker
```

**Cache:** Sanctum permissions cached. Model-level caching via `genealabs/laravel-model-caching`.
**Mail:** Office365 SMTP (`smtp.office365.com:587/TLS`), sender: StaffViz.
**Encryption at rest:** AES for instance DB credentials using `ENC_KEY` env var.

## Getting Started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan passport:keys
php artisan serve
```

## Related Repositories

| Repo | Purpose |
|---|---|
| `sv-angular-panel` | Angular 18 back-office SPA (consumes this API) |
| `sv-user-panel` | Employee-facing Laravel app |
| `sv-frontend` | StaffViz marketing site |
