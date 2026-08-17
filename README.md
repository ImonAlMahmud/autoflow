# ⚡ Autoflow — AI-Powered Automated Website Refresh & GitHub Sync Engine
> An Enterprise AI Automation Product by **Ideomet Technologies**

![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire 3](https://img.shields.io/badge/Livewire-3.x-4E56C6?style=for-the-badge&logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Groq AI](https://img.shields.io/badge/AI_Engine-Groq_Llama_3.3_70B-f50e0e?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

Autoflow is a modern, enterprise SaaS automated content management & AI refresh engine built with Laravel 11 and Livewire 3. It periodically scans static websites, uses high-speed Groq LLM intelligence to rewrite HTML text content, preserves 100% of inline CSS gradient styles & icons, and commits changes directly via GitHub REST API without requiring Git CLI binaries on the host (100% Zero-Disk server footprint).

---

## ✨ Key Features

- 🌐 **100% Cloud-Native GitHub API Sync**: Direct GitHub REST API integration using Personal Access Tokens (PAT). Rewrites trigger instant Vercel / Netlify live builds without needing Git CLI or storing local website files on the server.
- 🧹 **Zero Server Disk Footprint**: Processes HTML in-memory with automatic garbage cleanup routines to guarantee 0 MB server storage buildup.
- 🤖 **Groq LLM AI Engine**: High-speed text rewriting powered by `llama-3.3-70b-versatile` & custom AI models.
- 🎨 **CSS Gradient & Style Preservation**: Preserves `<span style="...">` gradients, FontAwesome icons (`<i>`), `<br>` breaks, and class names during rewrites.
- 🛡️ **Flexible Approval Modes**: Choose between **Automatic** (instant auto-push) or **Manual Review** (`Approve & Push` button with side-by-side diff review).
- 🔑 **Global GitHub Token (One-Time Setup)**: Configure a single global GitHub Personal Access Token in Settings to power all connected websites automatically.
- 🌐 **Multi-Site Manager**: Manage 50+ websites with automated time-interval scheduling (minutes, hours, days, months).
- 📊 **SaaS Marketing Suite**: Built-in modern public marketing pages (Home, About, How It Works, Pricing, Contact) branded for **Ideomet Technologies**.
- 🐧 **Linux & Shared Hosting Deployment**: Full cPanel web cron (`/cron/run`) and SSH setup compatibility.

---

## 🛠️ Technology Stack

- **Framework**: Laravel 11.x
- **Frontend / Reactivity**: Livewire 3.x, Alpine.js, Tailwind CSS
- **Database**: MySQL / SQLite
- **AI Integration**: Groq Cloud API (OpenAI Chat Completions format with Llama 3.3 70B)
- **Version Control Automation**: Direct GitHub REST API v3 Client (`GithubApiService`)

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
