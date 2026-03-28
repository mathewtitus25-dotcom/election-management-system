# 🚀 Zero-to-Hero Deployment Guide (Step-by-Step)

This guide takes you from your local computer to a live, hosted website for free.

## Phase 1: Create a Free Database (Supabase)
Cloud hosting needs a real database that stays online 24/7.
1.  Go to [Supabase.com](https://supabase.com/) and click **Start your project**.
2.  Log in with your **GitHub account**.
3.  Click **New Project** and name it `ElectionSystem`.
4.  **Important:** Set a strong Database Password and **SAVE IT** somewhere safe.
5.  Choose the Region closest to you (e.g., Mumbai, Singapore, etc.).
6.  Once the project is ready (takes ~2 mins):
    *   Go to **Project Settings** (gear icon) -> **Database**.
    *   Look for the **Connection String** section and select **URI**.
    *   It will look like this: `postgresql://postgres.[ID]:[PASSWORD]@aws-0-[REGION].pooler.supabase.com:5432/postgres`
    *   **Keep this URI ready.**

## Phase 2: Create Free File Storage (Cloudflare R2)
Because Laravel Cloud is "serverless," it deletes local photos every time you update your site. We use R2 to keep photos forever.
1.  Go to [Cloudflare.com](https://www.cloudflare.com/) and create a free account.
2.  On the left sidebar, click **R2** -> **Overview** -> **Create bucket**.
3.  Name it `election-photos` and click **Create bucket**.
4.  Inside your bucket, go to the **Settings** tab:
    *   Find **Public Access** -> **Custom Domains** or **R2.dev Subdomain**.
    *   Click **Allow Access** for the `r2.dev` subdomain (this lets your website show the photos).
    *   **Copy the Public URL** (e.g., `https://pub-xxxx.r2.dev`).
5.  Go back to the main **R2** page (the overview):
    *   On the right, click **Manage R2 API Tokens**.
    *   Click **Create API token**.
    *   Name it `ElectionApp`, select **Admin Read & Write**, and click **Create Token**.
    *   **CRITICAL:** Save the **Access Key ID**, **Secret Access Key**, and the **Endpoint** (everything before `/election-photos`).

## Phase 3: Push Your Code to GitHub
1.  Open your terminal in VS Code.
2.  Type these commands:
    ```bash
    git add .
    git commit -m "Final code for cloud deployment"
    git push origin main
    ```

## Phase 4: Deploy on Laravel Cloud
1.  Go to [Laravel Cloud](https://cloud.laravel.com/) and log in with GitHub.
2.  Click **Create Project** -> **Select your Repo**.
3.  Go to the **Environment Variables** tab before deploying.
4.  **Copy-Paste these variables from your local .env, but CHANGE these specific ones:**

### Set Database (From Supabase Step 1):
```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-xxxx.pooler.supabase.com  # From your Supabase URI
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxx                # From your Supabase URI
DB_PASSWORD=YourSupabasePassword         # The one you saved!
```

### Set Storage (From Cloudflare Step 2):
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=xxxxxxx                # From Cloudflare Token
AWS_SECRET_ACCESS_KEY=xxxxxxx            # From Cloudflare Token
AWS_DEFAULT_REGION=auto
AWS_BUCKET=election-photos
AWS_ENDPOINT=https://xxxx.r2.cloudflarestorage.com
AWS_URL=https://pub-xxxx.r2.dev          # Your Public R2 URL
```

### Set App Security:
```env
APP_ENV=production
APP_DEBUG=false
```

5.  Click **Deploy**.
6.  Once the "Build" is finished, go to the **Console** tab in Laravel Cloud and run:
    ```bash
    php artisan migrate:fresh --seed
    ```

## 🏁 Done!
Click your website link provided by Laravel Cloud. Your "Three Panchayat Digital Election Management System" is now LIVE! 🗳️ 🎉
