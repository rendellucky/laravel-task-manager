# Task Manager

A small Laravel application for managing tasks with drag-and-drop priorities and projects.

## Features

- Create, edit and delete tasks (name, priority, timestamps).
- Reorder tasks with drag and drop. Priorities are updated automatically: the task at the top is **#1**, the next one **#2**, and so on.
- Bonus: projects. Pick a project from the dropdown to see only its tasks. Each project has its own priority list, and tasks without a project form their own list ("No project").
- Data is stored in MySQL.

## Requirements

- PHP 8.3 or newer (with the `pdo_mysql` extension)
- Composer 2
- Node.js 20+ and npm
- MySQL 8

Built with Laravel 13.39.0.

## Local setup

1. Install the dependencies:
   ```bash
   composer install
   npm install
   ```
2. Create the environment file and the app key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Create an empty MySQL database called `task_manager` and set your database credentials in `.env` (`DB_USERNAME`, `DB_PASSWORD`).
4. Create the tables (`--seed` adds sample projects and tasks):
   ```bash
   php artisan migrate --seed
   ```
5. Build the frontend assets and start the app:
   ```bash
   npm run build
   php artisan serve
   ```
6. Open http://127.0.0.1:8000.

During development, run `npm run dev` instead of `npm run build` to get hot reloading.

## Deployment

On a server with PHP 8.3+, Composer, Node.js and MySQL (for example, a VPS with Nginx and PHP-FPM, or Laravel Forge):

1. Clone the repository and point the web server's document root to the `public/` directory.
2. Install the dependencies and build the assets:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```
3. Create `.env` from `.env.example`, set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` and the database credentials, then run:
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan optimize
   ```
4. Make sure the web server can write to `storage/` and `bootstrap/cache/`.

For later updates: pull the changes, then repeat the commands from steps 2 and 3 (except `key:generate`).

## How it works

| Part | Location |
|---|---|
| Routes | `routes/web.php` |
| Task CRUD | `app/Http/Controllers/TaskController.php` |
| Drag-and-drop endpoint | `app/Http/Controllers/ReorderTasksController.php` → `app/Actions/ReorderTasks.php` |
| Projects | `app/Http/Controllers/ProjectController.php` |
| Validation | `app/Http/Requests/` |
| Priority rules | `app/Observers/TaskObserver.php` |
| Views | `resources/views/` (Blade + Tailwind CSS) |
| Drag and drop in the browser | `resources/js/app.js` (SortableJS) |

**Priorities.** The priority is managed by the application, not by the user, so it is not mass assignable. `TaskObserver` keeps every project's list a gap-free sequence:

- a new task goes to the bottom of its project's list;
- when a task is deleted, the tasks below it move one place up;
- when a task is moved to another project, the gap in the old list is closed and the task goes to the bottom of the new one.

**Reordering.** After a drop, the browser sends the new order of task IDs to `PATCH /tasks/reorder`. The `ReorderTasks` action checks that the request contains exactly the tasks of that project (so an outdated page can't create duplicate priorities) and saves the new priorities in a single database transaction.

## Possible improvements

- Feature tests for the task, project and reordering logic.
- Real-time updates between open browser tabs with Laravel Broadcasting (Reverb + Echo).
- User accounts, so each user has their own projects and tasks.
