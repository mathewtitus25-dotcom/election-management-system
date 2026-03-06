# Laravel Backend for Election System

This directory contains the generated Laravel backend code to replace the Motoko actor logic.

## Setup Instructions

Since this code was generated as a set of file artifacts, you need to initialize a full Laravel environment around it.

1.  **Initialize Project** (if not already done via `composer create-project`):
    ```bash
    composer install
    cp .env.example .env
    php artisan key:generate
    ```

2.  **Database Setup**:
    - Configure your `.env` file with MySQL credentials.
    - Run migrations:
    ```bash
    php artisan migrate
    ```

3.  **Run Development Server**:
    ```bash
    php artisan serve
    ```

## API Documentation

- **Auth**: `/api/register`, `/api/login`, `/api/user`
- **Panchayats**: `/api/panchayats`
- **Voters**: `/api/voters` (Register), `/api/votes` (Cast Vote)
- **BLOs**: `/api/blo/voters/pending` (Approve/Reject)
- **Admin**: `/api/admin/blos`, `/api/admin/election/config`
- **Results**: `/api/results`

## Directory Structure

- `app/Models`: Eloquent models (User, Panchayat, Voter, etc.)
- `app/Http/Controllers/Api`: API Controllers mapping to original Motoko functions.
- `database/migrations`: Database schema definitions.
- `routes/api.php`: API Route definitions.
