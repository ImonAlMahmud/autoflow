# ⚡ Autoflow — AI-Powered Automated Website Refresh & GitHub Sync Engine
> An Enterprise AI Automation Product by **Ideomet Technologies**

![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire 3](https://img.shields.io/badge/Livewire-3.x-4E56C6?style=for-the-badge&logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Groq AI](https://img.shields.io/badge/AI_Engine-Groq_Llama_3.3_70B-f50e0e?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

Autoflow is a full-stack automated content management & AI refresh engine built with Laravel 11 and Livewire 3. It periodically scans websites, uses high-speed Groq LLM intelligence to rewrite HTML text content, preserves 100% of inline CSS gradient styles & icons, and automatically commits & pushes changes to GitHub remote repositories.

---

## ✨ Key Features

- 🤖 **Groq LLM AI Engine**: High-speed text rewriting powered by `llama-3.3-70b-versatile` & custom AI models.
- 🎨 **CSS Gradient & Style Preservation**: Preserves `<span style="...">` gradients, FontAwesome icons (`<i>`), `<br>` breaks, and class names during rewrites.
- 🌐 **Multi-Site Manager**: Manage 50+ websites with one-click site-wise log isolation and history tracking.
- ⚡ **Instant Execution & Loading Spinners**: Instant `Run Now` triggers with live status spinners and toast notifications.
- 🔄 **Automated Git Push**: Direct local & remote `git commit` and `git push origin main` execution.
- 📊 **SaaS Marketing Suite**: Built-in modern public marketing pages (Home, About, How It Works, Pricing, Contact) branded for **Ideomet Technologies**.
- 🐧 **Linux Server Setup Guide**: Built-in 1-click command copy deployment guide for sysadmins (Ubuntu, Debian, Apache, MySQL, Certbot SSL).

---

## 🛠️ Technology Stack

- **Framework**: Laravel 11.x
- **Frontend / Reactivity**: Livewire 3.x, Alpine.js, Tailwind CSS
- **Database**: MySQL / SQLite
- **AI Integration**: Groq Cloud API (OpenAI Chat Completions format)
- **Version Control Automation**: Native OS Git CLI (`GitService`)

---

## 🚀 Quick Setup & Installation

### 1. Clone Repository
```bash
git clone https://github.com/ImonAlMahmud/autoflow.git
cd autoflow
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database and AI API settings:
```ini
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_DATABASE=autoflow
SESSION_DRIVER=file
```

### 4. Database Migration & Seeding
```bash
php artisan migrate --seed
```

### 5. Run Local Development Server
```bash
php artisan serve
```
Open `http://127.0.0.1:8000` in your browser. Default Admin Credentials:
- **Email**: `admin@autoflow.local`
- **Password**: `password`

---

## 🏢 About Ideomet Technologies
Autoflow is developed and maintained by **Ideomet Technologies Limited** — an enterprise software development & AI engineering firm.

---

## 📄 License
This project is open-sourced software licensed under the [MIT license](LICENSE).
