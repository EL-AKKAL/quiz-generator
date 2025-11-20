📘 Quiz Generator – v1.0.0

Modern quiz creation & answer collection built with Laravel 12, Inertia.js, and Vue 3.
Fully responsive, dark/light mode, and designed for speed, simplicity, and reliability.

🚀 Overview

Quiz Generator is a full-stack mini-application that allows authenticated users to create and manage quizzes with multiple question types, and allows non-authenticated users to submit answers securely without accessing the rest of the app.

The goal of this project is to demonstrate a clean and modern Laravel + Inertia + Vue architecture, with real features like scheduling jobs, notifications, PEST tests, and automatic seeders for demo content.

🧩 Features
✅ Quiz Management

Create, edit, publish, and delete quizzes.

Add unlimited questions & options.

Automatic slug generation.

Quiz expiration logic (with scheduled job that disables expired quizzes).

Responsive layout with dark & light mode.

📝 Question Types Supported

Text (short answer)

Radio (single choice using radio input)

Select(single choice using select)

Multi-Select (checkboxes)

📥 Answer Submission (Public)

Non-logged-in users can:

Access a quiz through a public link.

Submit answers without needing an account.

Answers are validated and stored securely.

Visual restrictions prevent access to any internal route.

🔔 Notifications

Quiz owners receive notifications when someone submits answers.

Notifications stored in database & visible in the UI.

🧪 Testing (PEST)

Unit tests and feature tests for:

Quizzes management with different cases (with or without questions , with old/new picture...)

Jobs & scheduling

Authentication and registration.

🛠 Developer Tools

Factories + seeders for easy demo data.

Custom artisan commands to generate fake quizzes/questions.

Daily scheduled job to disable expired quizzes.

🎨 UI & UX

Fully responsive (desktop, tablet, mobile).

Dark mode & light mode toggle.

Clean Vue 3 component architecture.

🏗️ Tech Stack
Backend

Laravel 12.x

Inertia.js

MySQL

Laravel Scheduler (Cron jobs)

Laravel Notifications

Frontend

Vue 3.x (Composition API)

Inertia.js

TailwindCSS

Shadcn-vue / Custom components

📦 Installation
1. Clone the repository
git clone https://github.com/your-username/quiz-generator.git
cd quiz-generator

2. Install PHP dependencies
   composer install
   
3. Install JS dependencies
   npm install
# or
bun install

4. Environment setup
   cp .env.example .env
php artisan key:generate

Configure database credentials inside .env.

5. Run migrations & seeders
   php artisan migrate --seed

7. Start the development server
   composer run dev

   ⏰ Scheduled Jobs
✔ DisableExpiredQuizzesJob

Runs daily to:

Check quizzes with expire_date < now()

Automatically set status = false

Listed under:
php artisan schedule:list

🧪 Running Tests

Run the entire test suite:
php artisan test

or using PEST directly:
./vendor/bin/pest

run a specific file test :
./vendor/bin/pest/YOUR_FILE.test

📡 Routes

Only public routes:

/view/{quiz:slug} (get) → View quiz & answer

/view/{quiz:slug} (post) → Submit answers

Everything else requires authentication.

🎨 Dark / Light Mode

The UI supports:

Manual toggle saved in local storage and cookie

📱 Responsive Design

Layout is tested on:

Desktop

Tablet

Mobile

🤝 Contributing

Pull requests are welcome!
For major changes, please open an issue first to discuss your idea.


📄 License

This project is open-sourced under the MIT License.
