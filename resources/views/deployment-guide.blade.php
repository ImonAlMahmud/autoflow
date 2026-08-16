@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{
    copiedIndex: null,
    copyCode(text, index) {
        navigator.clipboard.writeText(text);
        this.copiedIndex = index;
        setTimeout(() => { this.copiedIndex = null; }, 2000);
    }
}">
    
    <!-- Page Header -->
    <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-800 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-semibold font-mono">
                    Ubuntu / Debian / Apache Linux Setup
                </span>
                <span class="text-xs text-slate-400">PHP 8.2+ Supported</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                🐧 Linux Server & Apache Deployment Guide
            </h1>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Step-by-step production setup commands for sysadmins. Click copy buttons to easily execute setup on your Linux VPS server.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('system-health') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition-all">
                ← System Health
            </a>
        </div>
    </div>

    <!-- STEP 1: PREREQUISITES & PHP 8.2 INSTALLATION -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <!-- Clean Step Header with Copy Button on the Right -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#EAECF0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 font-extrabold text-sm flex items-center justify-center border border-indigo-100 flex-shrink-0">
                    1
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Install Apache, MySQL & PHP 8.2 Extensions</h3>
                    <p class="text-xs text-[#667085]">Update system packages and install PHP extensions required for Laravel & Livewire.</p>
                </div>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode(`sudo apt update && sudo apt upgrade -y\nsudo apt install -y apache2 mysql-server git curl unzip zip\nsudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-mbstring php8.2-xml php8.2-curl php8.2-gd php8.2-bcmath\nsudo a2enmod rewrite\nsudo systemctl restart apache2`, 1)"
                type="button"
                class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5 self-start sm:self-auto flex-shrink-0"
            >
                <span x-text="copiedIndex === 1 ? '✓ Copied to Clipboard!' : '📋 Copy Step 1 Commands'"></span>
            </button>
        </div>

        <!-- Terminal Code Window -->
        <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                    <span class="ml-2 font-semibold text-slate-300">bash — terminal</span>
                </div>
            </div>
            <pre class="p-4 text-xs font-mono text-slate-100 overflow-x-auto leading-relaxed"><code>sudo apt update && sudo apt upgrade -y
sudo apt install -y apache2 mysql-server git curl unzip zip
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-mbstring php8.2-xml php8.2-curl php8.2-gd php8.2-bcmath
sudo a2enmod rewrite
sudo systemctl restart apache2</code></pre>
        </div>
    </div>

    <!-- STEP 2: INSTALL COMPOSER -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <!-- Clean Step Header with Copy Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#EAECF0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 font-extrabold text-sm flex items-center justify-center border border-purple-100 flex-shrink-0">
                    2
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Install Composer (PHP Dependency Manager)</h3>
                    <p class="text-xs text-[#667085]">Globally install Composer on your Linux server.</p>
                </div>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode(`curl -sS https://getcomposer.org/installer | php\nsudo mv composer.phar /usr/local/bin/composer\ncomposer --version`, 2)"
                type="button"
                class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5 self-start sm:self-auto flex-shrink-0"
            >
                <span x-text="copiedIndex === 2 ? '✓ Copied to Clipboard!' : '📋 Copy Step 2 Commands'"></span>
            </button>
        </div>

        <!-- Terminal Code Window -->
        <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                    <span class="ml-2 font-semibold text-slate-300">bash — terminal</span>
                </div>
            </div>
            <pre class="p-4 text-xs font-mono text-slate-100 overflow-x-auto leading-relaxed"><code>curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version</code></pre>
        </div>
    </div>

    <!-- STEP 3: CLONE REPO & PERMISSIONS -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <!-- Clean Step Header with Copy Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#EAECF0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 font-extrabold text-sm flex items-center justify-center border border-emerald-100 flex-shrink-0">
                    3
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Clone Repository & Configure Web Server Permissions</h3>
                    <p class="text-xs text-[#667085]">Clone Autoflow to `/var/www/autoflow` and grant `www-data` group permissions.</p>
                </div>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode(`cd /var/www/\nsudo git clone https://github.com/ImonAlMahmud/autoflow.git autoflow\ncd /var/www/autoflow\ncomposer install --no-dev --optimize-autoloader\nsudo chown -R www-data:www-data /var/www/autoflow\nsudo chmod -R 775 /var/www/autoflow/storage /var/www/autoflow/bootstrap/cache`, 3)"
                type="button"
                class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5 self-start sm:self-auto flex-shrink-0"
            >
                <span x-text="copiedIndex === 3 ? '✓ Copied to Clipboard!' : '📋 Copy Step 3 Commands'"></span>
            </button>
        </div>

        <!-- Terminal Code Window -->
        <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                    <span class="ml-2 font-semibold text-slate-300">bash — terminal</span>
                </div>
            </div>
            <pre class="p-4 text-xs font-mono text-slate-100 overflow-x-auto leading-relaxed"><code>cd /var/www/
sudo git clone https://github.com/ImonAlMahmud/autoflow.git autoflow
cd /var/www/autoflow
composer install --no-dev --optimize-autoloader
sudo chown -R www-data:www-data /var/www/autoflow
sudo chmod -R 775 /var/www/autoflow/storage /var/www/autoflow/bootstrap/cache</code></pre>
        </div>
    </div>

    <!-- STEP 4: DATABASE SETUP & ENVIRONMENT -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <!-- Clean Step Header with Copy Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#EAECF0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-800 font-extrabold text-sm flex items-center justify-center border border-amber-200 flex-shrink-0">
                    4
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Setup Environment (.env) & MySQL Database</h3>
                    <p class="text-xs text-[#667085]">Create MySQL database, run migrations, and seed default admin user.</p>
                </div>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode(`cp .env.example .env\nphp artisan key:generate\nsudo mysql -u root -e \"CREATE DATABASE autoflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\"\nsudo mysql -u root -e \"CREATE USER 'autoflow_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';\"\nsudo mysql -u root -e \"GRANT ALL PRIVILEGES ON autoflow.* TO 'autoflow_user'@'localhost'; FLUSH PRIVILEGES;\"\nphp artisan migrate --force\nphp artisan db:seed --force`, 4)"
                type="button"
                class="px-3.5 py-2 rounded-xl bg-amber-700 hover:bg-amber-800 active:bg-amber-900 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5 self-start sm:self-auto flex-shrink-0"
            >
                <span x-text="copiedIndex === 4 ? '✓ Copied to Clipboard!' : '📋 Copy Step 4 Commands'"></span>
            </button>
        </div>

        <!-- Terminal Code Window -->
        <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                    <span class="ml-2 font-semibold text-slate-300">bash — terminal</span>
                </div>
            </div>
            <pre class="p-4 text-xs font-mono text-slate-100 overflow-x-auto leading-relaxed"><code>cp .env.example .env
php artisan key:generate

# Create MySQL Database & User
sudo mysql -u root -e "CREATE DATABASE autoflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -u root -e "CREATE USER 'autoflow_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';"
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON autoflow.* TO 'autoflow_user'@'localhost'; FLUSH PRIVILEGES;"

# Run Database Migrations & Seeds
php artisan migrate --force
php artisan db:seed --force</code></pre>
        </div>
    </div>

    <!-- STEP 5: APACHE VIRTUALHOST CONFIGURATION -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <!-- Clean Step Header with Copy Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#EAECF0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 font-extrabold text-sm flex items-center justify-center border border-blue-100 flex-shrink-0">
                    5
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Configure Apache VirtualHost & SSL Certificate</h3>
                    <p class="text-xs text-[#667085]">Create Apache site config at `/etc/apache2/sites-available/autoflow.conf`.</p>
                </div>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode(`sudo nano /etc/apache2/sites-available/autoflow.conf\n\nsudo a2ensite autoflow.conf\nsudo systemctl reload apache2\n\nsudo apt install -y certbot python3-certbot-apache\nsudo certbot --apache -d autoflow.yourdomain.com`, 5)"
                type="button"
                class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5 self-start sm:self-auto flex-shrink-0"
            >
                <span x-text="copiedIndex === 5 ? '✓ Copied to Clipboard!' : '📋 Copy Step 5 Commands'"></span>
            </button>
        </div>

        <!-- Terminal Code Window -->
        <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                    <span class="ml-2 font-semibold text-slate-300">bash — terminal</span>
                </div>
            </div>
            <pre class="p-4 text-xs font-mono text-slate-100 overflow-x-auto leading-relaxed"><code>sudo nano /etc/apache2/sites-available/autoflow.conf

# Paste this VirtualHost Configuration:
&lt;VirtualHost *:80&gt;
    ServerName autoflow.yourdomain.com
    DocumentRoot /var/www/autoflow/public

    &lt;Directory /var/www/autoflow/public&gt;
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    &lt;/Directory&gt;

    ErrorLog ${APACHE_LOG_DIR}/autoflow_error.log
    CustomLog ${APACHE_LOG_DIR}/autoflow_access.log combined
&lt;/VirtualHost&gt;

# Enable Site & Restart Apache
sudo a2ensite autoflow.conf
sudo systemctl reload apache2

# Enable Free HTTPS SSL via Let's Encrypt Certbot
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d autoflow.yourdomain.com</code></pre>
        </div>
    </div>

    <!-- STEP 6: CRON WORKER SETUP FOR AUTOMATION -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <!-- Clean Step Header with Copy Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#EAECF0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 font-extrabold text-sm flex items-center justify-center border border-rose-100 flex-shrink-0">
                    6
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Setup Production Cron Schedule Worker</h3>
                    <p class="text-xs text-[#667085]">Add Laravel Scheduler cron job to execute background AI scans & Git pushes every minute.</p>
                </div>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode(`* * * * * cd /var/www/autoflow && php artisan schedule:run >> /dev/null 2>&1`, 6)"
                type="button"
                class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5 self-start sm:self-auto flex-shrink-0"
            >
                <span x-text="copiedIndex === 6 ? '✓ Copied Cron Line!' : '📋 Copy Cron Line'"></span>
            </button>
        </div>

        <!-- Terminal Code Window -->
        <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                    <span class="ml-2 font-semibold text-slate-300">bash — terminal</span>
                </div>
            </div>
            <pre class="p-4 text-xs font-mono text-slate-100 overflow-x-auto leading-relaxed"><code># Open www-data user crontab
sudo crontab -e -u www-data

# Add this line at the end of the file:
* * * * * cd /var/www/autoflow && php artisan schedule:run >> /dev/null 2>&1</code></pre>
        </div>
    </div>

</div>
@endsection
