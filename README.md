# 10000 Hour - Master Any Skill Through Deliberate Practice

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?logo=php)
![SQLite](https://img.shields.io/badge/Database-SQLite-green?logo=sqlite)
![License](https://img.shields.io/badge/License-Proprietary-yellow)

A comprehensive habit tracking web application inspired by the **10,000-hour rule**, which suggests that mastering any skill requires approximately 10,000 hours of deliberate practice. This Laravel-based platform helps users track their practice sessions, visualize progress, earn achievements, and stay motivated on their journey to mastery.

## 📋 Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
- [Features in Detail](#features-in-detail)
- [Admin Panel](#admin-panel)
- [Contributing](#contributing)
- [Troubleshooting](#troubleshooting)
- [Future Enhancements](#future-enhancements)

## 🎯 Overview

**10000 Hour** is a full-featured habit tracking application designed to help users achieve mastery in any skill through consistent, tracked practice. Whether you're learning to code, play an instrument, speak a language, or master any other skill, this application provides the tools you need to track your journey, visualize your progress, and stay motivated.

The application is built on the philosophy championed by researchers like Anders Ericsson and popularized by Malcolm Gladwell - that expertise in any field requires approximately 10,000 hours of deliberate practice.

## ✨ Key Features

### 🎯 **Habit Tracking**
- Create and manage multiple habits/skills simultaneously
- Track practice time with precision (hours, minutes, seconds)
- Daily check-ins to log practice sessions
- Time-based tracking with duration logging
- Automatic calculation of total hours practiced
- Smart duplicate habit prevention

### 📊 **Analytics & Visualization**
- **Progress Tracking**: Real-time progress towards the 10,000-hour goal
- **Daily Statistics**: Today's practice duration and progress percentage
- **Weekly & Monthly Analytics**: Comprehensive statistics with graphs
- **Success/Failure Metrics**: Track days you met your daily targets
- **Streak Tracking**: Current streak and longest streak calculations
- **Best Day Stats**: Identify your most productive practice days
- **Average Hours**: Calculate average practice time per day
- **Visual Charts**: Interactive charts showing practice patterns over time

### 🏆 **Gamification**
- **Badge System**: Earn badges for achieving milestones
- **User Badges**: Track earned achievements
- **Leaderboard**: Compare your progress with other users
- **Goal Markers**: Automatic detection when you reach the 10,000-hour milestone
- **Achievement Tracking**: Celebrate progress at various milestones

### 📝 **Community Features**
- **Blog Platform**: Share your journey and insights
- **Blog Comments**: Engage with other users' stories
- **Like System**: Support and encourage fellow practitioners
- **User Profiles**: Showcase your progress and achievements
- **Social Engagement**: Connect with like-minded individuals

### 👤 **User Management**
- Secure authentication system
- User registration and login
- Profile management
- Account deletion with confirmation
- Session management

### 🔐 **Admin Panel**
- User management dashboard
- Admin role assignment/removal
- Blog moderation and approval system
- Blog post approval/rejection workflow
- User statistics and analytics
- Content management

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 10.x
- **Language**: PHP 8.1+
- **Database**: SQLite (easily switchable to MySQL/PostgreSQL)
- **Authentication**: Laravel Sanctum
- **ORM**: Eloquent

### Frontend
- **Templating**: Blade
- **CSS**: Custom stylesheets
- **JavaScript**: Vanilla JavaScript with Axios
- **Build Tool**: Vite
- **Charts**: Chart.js (for analytics)

### Development Tools
- **Dependency Management**: Composer (PHP), NPM (JavaScript)
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint

## 📦 System Requirements

- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **Node.js**: >= 16.x
- **NPM**: >= 8.x
- **SQLite**: >= 3.x (or MySQL/PostgreSQL if preferred)
- **Web Server**: Apache/Nginx (development server included)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd 10000-hour
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Setup

The project comes with a pre-configured `.env` file for local development. Review and modify it if needed:

```bash
# The .env file is already present with default configuration
# Review database settings, app name, etc.
cat .env
```

Key environment variables:
```env
APP_NAME="10000 Hour"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/home/qknot/10000-hour/database/database.sqlite
```

### 5. Generate Application Key

If not already set:

```bash
php artisan key:generate
```

### 6. Database Setup

The project uses SQLite by default. The database file is already present at `database/database.sqlite`.

If starting fresh or need to reset:

```bash
# Create new SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed with sample data
php artisan db:seed
```

### 7. Build Frontend Assets

```bash
# Development build
npm run dev

# Or production build
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## ⚙️ Configuration

### Database Configuration

By default, the application uses SQLite. To switch to MySQL or PostgreSQL:

1. Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

2. Create the database and run migrations:
```bash
php artisan migrate
```

### Admin User Setup

The application includes a migration that ensures an admin user exists. Check `database/migrations/2025_11_13_102705_ensure_admin_user.php` for details.

To create additional admin users, use the admin panel after logging in with an existing admin account.

## 📖 Usage

### For Regular Users

1. **Register/Login**: Create an account or log in
2. **Create a Habit**: Set up a skill you want to master
   - Name your habit (e.g., "Guitar Practice", "Python Programming")
   - Set your daily target (in hours)
   - Define your ultimate goal (default: 10,000 hours)
3. **Log Practice Time**: 
   - Use the check-in feature to log completed practice sessions
   - Track time with precision (hours:minutes:seconds)
4. **View Analytics**: 
   - Monitor your progress dashboard
   - View detailed analytics and charts
   - Track streaks and achievements
5. **Earn Badges**: Unlock achievements as you hit milestones
6. **Engage with Community**:
   - Share your journey through blog posts
   - Comment on others' posts
   - Give and receive support

### For Administrators

1. **Access Admin Panel**: Navigate to `/admin/dashboard`
2. **Manage Users**:
   - View all registered users
   - Assign/remove admin privileges
   - Monitor user statistics
3. **Moderate Content**:
   - Review pending blog posts
   - Approve or reject submissions
   - Manage published content

## 📁 Project Structure

```
10000-hour/
├── app/
│   ├── Console/           # Artisan commands
│   ├── Exceptions/        # Exception handlers
│   ├── Http/
│   │   ├── Controllers/   # Application controllers
│   │   │   ├── Auth/      # Authentication controllers
│   │   │   ├── AdminController.php
│   │   │   ├── BadgeController.php
│   │   │   ├── BlogController.php
│   │   │   ├── CommentController.php
│   │   │   ├── HabitsController.php
│   │   │   ├── LeaderboardController.php
│   │   │   ├── LikeController.php
│   │   │   └── ProfileController.php
│   │   └── Middleware/    # HTTP middleware
│   ├── Models/            # Eloquent models
│   │   ├── Badge.php
│   │   ├── Blog.php
│   │   ├── BlogComment.php
│   │   ├── BlogLike.php
│   │   ├── User.php
│   │   ├── UserBadge.php
│   │   ├── habits.php
│   │   └── habits_logs.php
│   ├── Providers/         # Service providers
│   └── Services/          # Business logic services
├── bootstrap/             # Framework bootstrap
├── config/                # Configuration files
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   ├── seeders/           # Database seeders
│   └── database.sqlite    # SQLite database file
├── public/                # Public assets
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
│       ├── admin/         # Admin panel views
│       ├── auth/          # Authentication views
│       ├── badges/        # Badge views
│       ├── blog/          # Blog views
│       ├── habits/        # Habit tracking views
│       ├── leaderboard/   # Leaderboard views
│       ├── profile/       # Profile views
│       └── layouts/       # Layout templates
├── routes/
│   ├── api.php            # API routes
│   ├── channels.php       # Broadcast channels
│   ├── console.php        # Console routes
│   └── web.php            # Web routes
├── storage/               # Generated files
├── vendor/                # Composer dependencies
├── node_modules/          # NPM dependencies
├── .env                   # Environment configuration
├── artisan                # Artisan CLI
├── composer.json          # PHP dependencies
├── package.json           # JavaScript dependencies
├── vite.config.js         # Vite configuration
└── README.md             # This file
```

## 🗄️ Database Schema

### Main Tables

#### **users**
- User authentication and profile information
- Fields: id, name, email, password, is_admin, timestamps

#### **habits**
- User's tracked habits/skills
- Fields: 
  - id, user_id, habit_name
  - daily_count (target hours per day)
  - goal_hours (default: 10000)
  - goal_reached_at
  - timestamps

#### **habits_logs**
- Individual practice session records
- Fields:
  - id, habit_id
  - log_date
  - duration (in seconds)
  - timestamps

#### **badges**
- Achievement definitions
- Fields: id, name, description, icon, requirement, timestamps

#### **user_badges**
- Badges earned by users
- Fields: id, user_id, badge_id, earned_at, timestamps

#### **blogs**
- Community blog posts
- Fields:
  - id, user_id, title, content
  - is_approved, approved_by, approved_at
  - rejected_at, rejection_reason
  - timestamps

#### **blog_likes**
- Blog post likes
- Fields: id, blog_id, user_id, timestamps

#### **blog_comments**
- Blog post comments
- Fields: id, blog_id, user_id, content, timestamps

## 🔌 API Endpoints

### Authentication
- `GET /register` - Registration form
- `POST /store` - Register new user
- `GET /login` - Login form
- `POST /authenticate` - Authenticate user
- `POST /logout` - Logout user

### Habits
- `GET /habits/{id}` - View habit dashboard
- `GET /habits/{id}/analisis` - View habit analytics
- `POST /habits/checkin` - Log a check-in
- `POST /habits/log-time` - Log practice time
- `POST /habits/store` - Create new habit
- `PUT /habits/{id}` - Update habit
- `DELETE /habits/{id}` - Delete habit

### Analytics API
- `GET /api/getdata/{id}` - Get habit analytics data (JSON)
  - Returns: logs, statistics, progress, streaks, etc.

### Blogs
- `GET /` - Blog listing (home page)
- `GET /blog/create` - Create blog form
- `POST /blog/store` - Save new blog post
- `GET /blog/{id}` - View blog post
- `PUT /blog/{id}` - Update blog post
- `DELETE /blog/{id}` - Delete blog post

### Social Features
- `POST /blog/{id}/like` - Toggle like on blog
- `POST /blog/{id}/comment` - Comment on blog
- `DELETE /comment/{id}` - Delete comment

### Badges & Leaderboard
- `GET /badges` - View badges
- `GET /leaderboard` - View leaderboard

### Profile
- `GET /profile` - View profile
- `DELETE /profile/delete` - Delete account

### Admin
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - Manage users
- `POST /admin/users/{user}/make-admin` - Grant admin role
- `POST /admin/users/{user}/remove-admin` - Revoke admin role
- `GET /admin/blogs` - Manage blogs
- `POST /admin/blogs/{id}/approve` - Approve blog
- `POST /admin/blogs/{id}/reject` - Reject blog

## 🎨 Features in Detail

### Habit Analytics

The application provides comprehensive analytics through the `habits` model:

**Statistics Calculated:**
- **Total Hours**: Cumulative practice time across all sessions
- **Today's Progress**: Current day's practice time and percentage of daily goal
- **Daily Target**: User-defined practice goal (converted to seconds for precision)
- **Success/Failure Stats**: Days meeting vs. missing daily targets
- **Average Hours Per Day**: Mean practice time calculated from first log
- **Current Streak**: Consecutive days of practice
- **Best Day**: Day with maximum practice hours
- **Weekly Stats**: Practice summary for the past 7 days
- **Monthly Stats**: Practice summary for the past 30 days
- **Goal Progress**: Percentage progress towards 10,000-hour goal
- **Goal Reached**: Boolean indicating if goal has been achieved

**Duration Tracking:**
- Precision tracking in seconds
- Formatted display (HH:MM:SS)
- Aggregation by date for historical analysis

### Badge System

Users can earn badges based on:
- Total hours practiced
- Streak milestones
- Goal achievement
- Community engagement
- Consistency metrics

### Blog Approval Workflow

1. User creates blog post
2. Post enters "pending" state
3. Admin reviews content
4. Admin approves or rejects with reason
5. Approved posts appear on home page
6. Users can like and comment on approved posts

## 🔐 Admin Panel

The admin panel provides:

- **Dashboard**: Overview of platform statistics
- **User Management**: 
  - View all users
  - Grant/revoke admin privileges
  - Monitor user activity
- **Content Moderation**:
  - Review pending blog posts
  - Approve quality content
  - Reject inappropriate posts
  - Delete any blog post
  - Manage comments

Access: Navigate to `/admin/dashboard` (requires admin privileges)

## 🤝 Contributing

This appears to be a personal project. If you'd like to contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request with detailed description

## 🐛 Troubleshooting

### Database Issues

**Problem**: "Database not found" error
```bash
# Solution: Create SQLite database
touch database/database.sqlite
php artisan migrate
```

**Problem**: Migration errors
```bash
# Solution: Fresh migration
php artisan migrate:fresh
```

### Permission Issues

**Problem**: Storage directory not writable
```bash
# Solution: Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### Frontend Build Issues

**Problem**: Vite errors
```bash
# Solution: Clear cache and rebuild
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Authentication Issues

**Problem**: Session expires too quickly
```env
# Solution: Increase session lifetime in .env
SESSION_LIFETIME=1440  # 24 hours in minutes
```

## 🚀 Future Enhancements

Potential features for future development:

- **Mobile App**: Native iOS/Android applications
- **Social Features**: Follow users, share achievements
- **Teams**: Group habits, team challenges
- **Export**: Data export (CSV, PDF reports)
- **Reminders**: Email/push notifications for practice
- **Categories**: Organize habits by category
- **Notes**: Add notes to practice sessions
- **Voice Logging**: Quick voice-based time logging
- **Calendar View**: Calendar-based visualization
- **Insights**: AI-powered practice insights
- **API**: Public API for third-party integrations
- **Widgets**: Desktop/mobile widgets for quick logging

## 📄 License

Proprietary - All rights reserved.

## 👨‍💻 Author

**QKnot**

---

## 🌟 Motivation

> "Andrej Karpathy believes that mastering any skill requires consistent practice over time — the idea behind the 10,000 Hour app is to help users track and focus on deliberate practice to achieve expertise."

This application is built on the principle that **deliberate practice** is the key to mastery. Whether you're learning to code, play music, speak a language, or master any craft, consistent tracking and measurement of your practice time can help you stay motivated and achieve your goals.

The 10,000-hour milestone isn't just a number—it's a testament to your dedication and commitment to excellence.

---

**Start your journey to mastery today!** 🎯
