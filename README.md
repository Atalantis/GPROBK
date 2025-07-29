# Insuractio Project Management

Insuractio is a Laravel-based platform designed to manage university thesis projects. It offers tools for professors and students to collaborate efficiently with Kanban boards, Gantt and calendar views, file sharing and real-time notifications. An AI assistant is included to help draft and reformulate comments.

## Features

- Dashboard with project statistics
- CRUD management for projects, tasks, categories and milestones
- Kanban board to visualise task status
- Gantt chart and calendar views
- File attachments and link sharing
- Comment system enhanced by an AI assistant (OpenAI)
- Real-time notifications via Pusher/Slack
- Custom fields and search functionality
- Role management for professors and students

## Requirements

- PHP 8.2 or higher with required extensions
- Node.js 18 or later
- Composer and npm
- A database (SQLite by default)

Optional services:

- Pusher credentials for broadcasting notifications
- `OPENAI_API_KEY` for AI assistance
- `GEMINI_API_KEY` if using Gemini

## Installation

1. Clone the repository and install PHP dependencies:
   ```bash
   composer install
   ```
2. Install front-end dependencies:
   ```bash
   npm install
   ```
3. Copy the example environment file and adjust values:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Create `database/database.sqlite` or configure another database connection in `.env`.
4. Run the database migrations:
   ```bash
   php artisan migrate
   ```
5. Build the assets (or start the dev server):
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

## Development

To start a local environment with queue worker, logs and Vite running, use:

```bash
composer dev
```

This command launches `php artisan serve`, listens for queued jobs and watches assets for changes.

## Testing

Execute the test suite with:

```bash
composer test
```

## License

This project is open-sourced software licensed under the MIT license.
