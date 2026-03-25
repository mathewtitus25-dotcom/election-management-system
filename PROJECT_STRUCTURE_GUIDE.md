# Three-Panchayat Digital Election Management System - Complete Project Guide

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Root Directory Files](#root-directory-files)
4. [Main Directories](#main-directories)
5. [Application Layer (app/)](#application-layer-app)
6. [Database Layer (database/)](#database-layer-database)
7. [Frontend Layer (resources/)](#frontend-layer-resources)
8. [Routing (routes/)](#routing-routes)
9. [Configuration (config/)](#configuration-config)
10. [Public Assets (public/)](#public-assets-public)
11. [How Data Flows](#how-data-flows)
12. [Key Workflows](#key-workflows)

---

## 🎯 Project Overview

This is a **Laravel-based Digital Election Management System** for managing elections across three panchayats (local government units). The system handles:

- **Voter Registration** with OTP verification
- **Candidate Applications** and approval
- **BLO (Booth Level Officer)** management for voter approval
- **Admin** oversight and election configuration
- **Secure Voting** with one-vote-per-voter enforcement
- **Real-time Results** display

---

## 🛠️ Technology Stack

- **Backend Framework**: Laravel 11.x (PHP)
- **Database**: SQLite (can be changed to MySQL/PostgreSQL)
- **Frontend**: Blade Templates (Laravel's templating engine)
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Vanilla JS with Alpine.js
- **Authentication**: Laravel Sanctum (session-based)
- **Email**: Laravel Mail (for OTP)

---

## 📁 Root Directory Files

### `.env`
**Purpose**: Environment configuration file (CRITICAL - contains sensitive data)
- Database credentials
- Mail server settings (for OTP emails)
- Application key (encryption)
- App environment (local/production)

**What you can change**:
```env
DB_CONNECTION=sqlite          # Change to mysql, pgsql
MAIL_MAILER=smtp             # Email service
MAIL_FROM_ADDRESS=           # Your email
APP_NAME="Election System"   # Application name
```

### `.env.example`
**Purpose**: Template for `.env` file (safe to share, no sensitive data)

### `composer.json`
**Purpose**: PHP dependency manager configuration
- Lists all Laravel packages and libraries
- Defines autoloading rules
- Scripts for development tasks

**When to modify**: When adding new PHP packages via `composer require package-name`

### `package.json`
**Purpose**: JavaScript dependency manager
- Frontend build tools (Vite)
- CSS processors (Tailwind)

### `artisan`
**Purpose**: Laravel's command-line tool
- Run migrations: `php artisan migrate`
- Create controllers: `php artisan make:controller`
- Clear cache: `php artisan cache:clear`

### `spec.md`
**Purpose**: Project specification document
- Describes all features and requirements
- User roles and workflows
- System capabilities

### `README.md`
**Purpose**: Setup instructions and API documentation

---

## 📂 Main Directories

```
three-panchayat-digital-election-management-system/
├── app/              # Application logic (Controllers, Models)
├── bootstrap/        # Laravel framework initialization
├── config/           # Configuration files
├── database/         # Migrations, seeders, SQLite file
├── public/           # Publicly accessible files (entry point)
├── resources/        # Views (Blade templates), CSS, JS
├── routes/           # URL routing definitions
├── storage/          # Logs, cache, uploaded files
├── tests/            # Automated tests
└── vendor/           # Third-party packages (auto-generated)
```

---

## 🧩 Application Layer (app/)

This is where **your business logic** lives.

### `app/Models/` - Database Models (7 files)

Models represent database tables and define relationships.

#### **User.php**
- **Purpose**: Represents all users (Admin, BLO, Voter)
- **Key Fields**: name, email, password, role (admin/blo/voter)
- **Relationships**: 
  - `hasOne(Voter)` - A user can be a voter
  - `hasOne(BLO)` - A user can be a BLO
- **When to modify**: Adding new user fields or authentication methods

#### **Voter.php**
- **Purpose**: Stores voter-specific data
- **Key Fields**: user_id, voter_id_number, date_of_birth, panchayat_id, status (pending/approved/rejected)
- **Relationships**:
  - `belongsTo(User)` - Links to user account
  - `belongsTo(Panchayat)` - Which panchayat they belong to
  - `hasOne(Vote)` - Their vote record
- **When to modify**: Adding voter verification fields

#### **BLO.php**
- **Purpose**: Booth Level Officers who approve voters
- **Key Fields**: user_id, panchayat_id, is_active
- **Relationships**:
  - `belongsTo(User)`
  - `belongsTo(Panchayat)` - Assigned panchayat
- **When to modify**: Adding BLO permissions or assignments

#### **Candidate.php**
- **Purpose**: Election candidates
- **Key Fields**: name, email, panchayat_id, status (pending/approved/rejected), details (bio, photo, etc.)
- **Relationships**:
  - `belongsTo(Panchayat)`
  - `hasMany(Vote)` - Votes received
- **When to modify**: Adding candidate profile fields

#### **Panchayat.php**
- **Purpose**: Represents the three panchayats
- **Key Fields**: name, code, description
- **Relationships**:
  - `hasMany(Voter)`
  - `hasMany(BLO)`
  - `hasMany(Candidate)`
- **When to modify**: Adding panchayat metadata

#### **Vote.php**
- **Purpose**: Records cast votes
- **Key Fields**: voter_id, candidate_id, panchayat_id, voted_at
- **Relationships**:
  - `belongsTo(Voter)`
  - `belongsTo(Candidate)`
- **Security**: Ensures one vote per voter

#### **ElectionConfig.php**
- **Purpose**: Election settings
- **Key Fields**: start_date, end_date, is_active
- **When to modify**: Adding election parameters

---

### `app/Http/Controllers/` - Request Handlers (8 files)

Controllers handle HTTP requests and return responses.

#### **AuthController.php**
- **Purpose**: User login, logout, registration
- **Key Methods**:
  - `login()` - Authenticates users
  - `logout()` - Ends session
  - `register()` - Creates new user accounts
- **Routes**: `/login`, `/logout`, `/register`

#### **RegistrationController.php**
- **Purpose**: Voter and candidate registration with OTP
- **Key Methods**:
  - `showVoterForm()` - Display voter registration form
  - `registerVoter()` - Process voter registration
  - `verifyOtp()` - Verify OTP code
  - `showCandidateForm()` - Candidate application form
  - `registerCandidate()` - Process candidate application
- **Routes**: `/register/voter`, `/register/candidate`, `/verify-otp`

#### **VoterController.php**
- **Purpose**: Voter dashboard and voting
- **Key Methods**:
  - `dashboard()` - Show voter dashboard
  - `vote()` - Cast a vote
  - `showVotingPage()` - Display candidates
- **Routes**: `/voter/dashboard`, `/voter/vote`

#### **BLOController.php**
- **Purpose**: BLO voter approval workflow
- **Key Methods**:
  - `dashboard()` - Show pending voters
  - `approveVoter()` - Approve voter registration
  - `rejectVoter()` - Reject voter registration
- **Routes**: `/blo/dashboard`, `/blo/approve/{id}`, `/blo/reject/{id}`

#### **CandidateController.php**
- **Purpose**: Candidate profile and management
- **Key Methods**:
  - `dashboard()` - Candidate dashboard
  - `updateProfile()` - Edit candidate details
  - `withdraw()` - Withdraw from election
- **Routes**: `/candidate/dashboard`, `/candidate/update`

#### **AdminController.php**
- **Purpose**: Admin panel for system management
- **Key Methods**:
  - `dashboard()` - Admin overview
  - `manageBLOs()` - Create/assign BLOs
  - `manageCandidates()` - Approve/reject candidates
  - `configureElection()` - Set election dates
  - `removeCandidate()` - Remove candidate from election
- **Routes**: `/admin/dashboard`, `/admin/blos`, `/admin/candidates`, `/admin/election`

#### **ElectionController.php**
- **Purpose**: Public election results
- **Key Methods**:
  - `results()` - Display vote counts
- **Routes**: `/results`

#### **Controller.php**
- **Purpose**: Base controller (all controllers extend this)

---

### `app/Http/Middleware/` - Request Filters

#### **RoleMiddleware.php**
- **Purpose**: Restricts routes based on user role
- **Example**: Only admins can access `/admin/*` routes
- **How it works**: Checks `auth()->user()->role` before allowing access

---

### `app/Mail/` - Email Templates

#### **OtpMail.php**
- **Purpose**: Sends OTP verification emails
- **When triggered**: After voter/candidate registration
- **Template**: `resources/views/emails/otp.blade.php`

---

## 🗄️ Database Layer (database/)

### `database/migrations/` - Database Schema (10 files)

Migrations create and modify database tables. They run in order by filename timestamp.

#### **2024_01_01_000001_create_panchayats_table.php**
- **Creates**: `panchayats` table
- **Columns**: id, name, code, description, timestamps

#### **2024_01_01_000002_create_users_table.php**
- **Creates**: `users` table
- **Columns**: id, name, email, password, role, email_verified_at, otp, otp_expires_at, timestamps

#### **2024_01_01_000003_create_voters_table.php**
- **Creates**: `voters` table
- **Columns**: id, user_id, voter_id_number, date_of_birth, panchayat_id, status, timestamps

#### **2024_01_01_000004_create_blos_table.php**
- **Creates**: `blos` table
- **Columns**: id, user_id, panchayat_id, is_active, timestamps

#### **2024_01_01_000005_create_candidates_table.php**
- **Creates**: `candidates` table
- **Columns**: id, name, email, panchayat_id, status, timestamps

#### **2026_02_03_135548_add_details_to_candidates_table.php**
- **Modifies**: `candidates` table
- **Adds**: details column (JSON) for bio, photo, manifesto

#### **2024_01_01_000006_create_votes_table.php**
- **Creates**: `votes` table
- **Columns**: id, voter_id, candidate_id, panchayat_id, voted_at, timestamps

#### **2024_01_01_000007_create_election_config_table.php**
- **Creates**: `election_configs` table
- **Columns**: id, start_date, end_date, is_active, timestamps

#### **0001_01_01_000001_create_cache_table.php**
- **Creates**: `cache` table (Laravel system cache)

#### **0001_01_01_000002_create_jobs_table.php**
- **Creates**: `jobs` table (background job queue)

**How to run migrations**:
```bash
php artisan migrate        # Run all pending migrations
php artisan migrate:fresh  # Drop all tables and re-run
php artisan migrate:rollback # Undo last migration
```

---

### `database/seeders/` - Sample Data

#### **DatabaseSeeder.php**
- **Purpose**: Populate database with initial data
- **Example**: Create 3 panchayats, 1 admin user
- **Run**: `php artisan db:seed`

---

### `database/database.sqlite`
- **Purpose**: The actual SQLite database file
- **Contains**: All your data (users, votes, etc.)
- **Warning**: Deleting this file = losing all data

---

## 🎨 Frontend Layer (resources/)

### `resources/views/` - Blade Templates (HTML)

Blade is Laravel's templating engine. Files end in `.blade.php`.

#### **layouts/app.blade.php**
- **Purpose**: Master layout template
- **Contains**: Header, navigation, footer
- **Used by**: All other views extend this

#### **auth/** - Authentication Pages
- `login.blade.php` - Login form
- `register.blade.php` - Registration form
- `otp.blade.php` - OTP verification page

#### **voter/** - Voter Pages
- `dashboard.blade.php` - Voter dashboard
- `vote.blade.php` - Voting interface (shows candidates)

#### **blo/** - BLO Pages
- `dashboard.blade.php` - Pending voter approvals

#### **candidate/** - Candidate Pages
- `dashboard.blade.php` - Candidate profile
- `register.blade.php` - Candidate application form

#### **admin/** - Admin Pages
- `dashboard.blade.php` - Admin control panel

#### **election/** - Public Pages
- `results.blade.php` - Election results

#### **emails/** - Email Templates
- `otp.blade.php` - OTP email design

#### **errors/** - Error Pages
- `404.blade.php` - Page not found

#### **welcome.blade.php**
- **Purpose**: Homepage/landing page
- **Route**: `/`

---

### `resources/css/app.css`
- **Purpose**: Main stylesheet
- **Includes**: Tailwind CSS directives
- **Compiled to**: `public/build/assets/app.css`

### `resources/js/app.js`
- **Purpose**: Main JavaScript file
- **Includes**: Alpine.js, custom scripts
- **Compiled to**: `public/build/assets/app.js`

---

## 🛣️ Routing (routes/)

### `routes/web.php`
- **Purpose**: Defines all web routes (URLs)
- **Structure**:
  ```php
  Route::get('/url', [Controller::class, 'method'])->name('route.name');
  ```

**Key Route Groups**:
```php
// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/results', [ElectionController::class, 'results']);

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require login)
Route::middleware('auth')->group(function () {
    // Voter routes
    Route::middleware('role:voter')->prefix('voter')->group(function () {
        Route::get('/dashboard', [VoterController::class, 'dashboard']);
    });
    
    // BLO routes
    Route::middleware('role:blo')->prefix('blo')->group(function () {
        Route::get('/dashboard', [BLOController::class, 'dashboard']);
    });
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
    });
});
```

---

## ⚙️ Configuration (config/)

### `config/app.php`
- **Purpose**: Core application settings
- **Key Settings**: timezone, locale, app name

### `config/database.php`
- **Purpose**: Database connection settings
- **Default**: SQLite
- **Can change to**: MySQL, PostgreSQL

### `config/mail.php`
- **Purpose**: Email service configuration
- **Used for**: OTP emails

### `config/auth.php`
- **Purpose**: Authentication settings
- **Defines**: User model, guards, password reset

### `config/session.php`
- **Purpose**: Session management
- **Stores**: User login state

---

## 🌐 Public Assets (public/)

### `public/index.php`
- **Purpose**: Application entry point
- **All requests** go through this file first

### `public/build/`
- **Purpose**: Compiled CSS and JS files
- **Generated by**: `npm run build` or `npm run dev`

---

## 🔄 How Data Flows

### Example: Voter Registration Flow

1. **User visits** `/register`
   - Route: `routes/web.php` → `RegistrationController@showRegister`
   - Controller: `app/Http/Controllers/RegistrationController.php`
   - View: `resources/views/auth/register.blade.php`

2. **User submits form**
   - Route: `POST /register` → `RegistrationController@register`
   - Controller validates data
   - Creates `User` record (Model: `app/Models/User.php`)
   - Creates `Voter` record (Model: `app/Models/Voter.php`)
   - Sends OTP email (Mail: `app/Mail/OtpMail.php`)
   - Redirects to OTP verification page

3. **User enters OTP**
   - Route: `POST /verify-otp` → `RegistrationController@verifyOtp`
   - Controller checks OTP
   - Updates `email_verified_at` in database
   - Redirects to voter dashboard

4. **BLO approves voter**
   - Route: `POST /blo/approve/{id}` → `BLOController@approveVoter`
   - Updates `voters.status` to 'approved'
   - Voter can now vote

---

## 🔑 Key Workflows

### 1. Voter Registration
- User fills form → OTP sent → Email verified → BLO approval → Can vote

### 2. Candidate Registration
- Candidate applies → Admin reviews → Approved/Rejected → Appears on ballot

### 3. Voting
- Voter logs in → Sees candidates from their panchayat → Casts vote → Vote recorded

### 4. BLO Workflow
- BLO logs in → Sees pending voters in their panchayat → Approves/Rejects

### 5. Admin Workflow
- Admin logs in → Manages BLOs → Approves candidates → Configures election dates

---

## 🛠️ How to Modify the Code

### Adding a New Field to Voter Registration

1. **Create Migration**:
   ```bash
   php artisan make:migration add_phone_to_voters_table
   ```

2. **Edit Migration** (`database/migrations/xxxx_add_phone_to_voters_table.php`):
   ```php
   public function up() {
       Schema::table('voters', function (Blueprint $table) {
           $table->string('phone')->nullable();
       });
   }
   ```

3. **Run Migration**:
   ```bash
   php artisan migrate
   ```

4. **Update Model** (`app/Models/Voter.php`):
   ```php
   protected $fillable = ['user_id', 'voter_id_number', 'date_of_birth', 'panchayat_id', 'status', 'phone'];
   ```

5. **Update Form** (`resources/views/auth/register.blade.php`):
   ```html
   <input type="text" name="phone" placeholder="Phone Number">
   ```

6. **Update Controller** (`app/Http/Controllers/RegistrationController.php`):
   ```php
   $validated = $request->validate([
       'phone' => 'required|string|max:15',
       // ... other fields
   ]);
   ```

---

## 📚 Important Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName -m  # -m creates migration too

# Create new migration
php artisan make:migration migration_name

# Compile frontend assets
npm run dev      # Development (watch mode)
npm run build    # Production build
```

---

## 🎓 Learning Path

To understand and modify this project effectively:

1. **Start with Routes** (`routes/web.php`) - See what URLs exist
2. **Follow to Controllers** (`app/Http/Controllers/`) - See what happens when URL is visited
3. **Check Models** (`app/Models/`) - Understand database structure
4. **View Templates** (`resources/views/`) - See what users see
5. **Study Migrations** (`database/migrations/`) - Understand database schema

---

## 🔒 Security Notes

- **Never commit `.env`** to version control (contains passwords)
- **OTP expires** after 10 minutes
- **Passwords are hashed** using bcrypt
- **One vote per voter** enforced at database level
- **Role-based access** prevents unauthorized actions

---

## 📞 Need Help?

- **Laravel Documentation**: https://laravel.com/docs
- **Blade Templates**: https://laravel.com/docs/blade
- **Eloquent ORM**: https://laravel.com/docs/eloquent
- **Tailwind CSS**: https://tailwindcss.com/docs

---

**Last Updated**: February 2026
**Project Version**: 1.0
