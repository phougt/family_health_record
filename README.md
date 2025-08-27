# Family Health Record API

A Laravel API for managing family health records with group-based collaboration and role management.

## Features

- Multi-group family health management
- Role-based permissions (Owner/Member/Custom)
- Medical record tracking with doctors/hospitals
- Secure invite system for family members
- Tag-based organization
- Document storage

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL

## Quick Setup

```bash
# Clone and install
git clone https://github.com/phougt/family_health_record.git
cd family_health_record
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (configure .env first)
php artisan migrate
php artisan db:seed

# Start server
php artisan serve
```

API available at: `http://localhost:8000/api/v1`

## Key API Endpoints

```
# Authentication
POST /api/v1/sign-up
POST /api/v1/login
POST /api/v1/logout

# Groups
GET/POST /api/v1/group
GET/PUT/DELETE /api/v1/group/{id}

# Users & Roles
GET/PUT /api/v1/user
POST /api/v1/user/{id}/group-role

# Healthcare
GET/POST /api/v1/group/{id}/hospital
GET/POST /api/v1/group/{id}/doctor
GET/POST /api/v1/group/{id}/record-type

# Organization
GET/POST /api/v1/group/{id}/tag
GET/POST /api/v1/group/{id}/invite-link
```

## Default Test User

- Email: `mint@gmail.com`
- Password: `123456789`
