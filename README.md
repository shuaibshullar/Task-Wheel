<div align="center">

# 📝 Task Wheel Project 📝

</div>
<br>

## 📖 About Task Wheel project
This project is a task management application that features a unique and interactive **Task Wheel** interface to display tasks intuitively. It provides a structured way to organize and track workflow based on categories and user roles.

### 💻 Built With
*   **Framework:** Laravel 13.18
*   **PHP:** php 8.3
*   **Node.js:** node.js v24.18.0
*   **Npm:** npm v12.0.2
*   **Composer:** composer v2.10.2
*   **Frontend & Reactivity:** Laravel Livewire & Blade Templating Engine (Built entirely within the Laravel ecosystem without relying on external JavaScript frameworks like React).
*   **Database:** Database-agnostic (Thanks to Laravel's abstraction, the project is flexible and can run on MySQL, PostgreSQL, SQLite, etc.).

### ✨ Key Features
*   **Interactive UI:** Displays tasks using a visually appealing Task Wheel format.
*   **Task & Category Management:** Full CRUD (Create, Read, Update, Delete) operations for tasks and limited CR (Create, Read) for categories.
*   **Authentication System:** Secure login, logout, and password recovery (reset) functionality.
*   **Role-Based Access Control (RBAC):**
    *   **Admins:** Have full access to add, edit, and delete tasks and categories. They also have the privilege to promote other users to the Admin role.
    *   **Regular Users:** Have read-only access, allowing them to browse the Task Wheel and view task details.

### 🚧 Current Limitations (Roadmap)
*   **Profile Management:** User profile editing (e.g., updating name or changing password directly from the profile) is not yet supported. Currently, passwords can only be changed via the forgotten password reset flow.
*   **Email Verification:** The system does not yet include an email verification step during registration.
*   **Category Management:** You can't delete and modify category.
*   **UI & UX:** The project doesn't support responsive page for mobile. It only supports desktop view so far.
*   **Dark and Light Mode:** The project supports dark mode only so far.

<br>

## 🚀 Getting Started
1.  **First, installation:** you have to install **[PHP](https://www.php.net/downloads.php), [Composer](https://getcomposer.org/download/) and [Node.js](https://nodejs.org/en/download)** also you can see **[Laravel installation page](https://laravel.com/framework/docs/installation).**
When you install them for the first time be sure about environment variables. Also, you may need to change **php.ini file** to apply some extensions which composer need them.

*   **

2.  **Second, deployment:** After download this project (clone it) and install all things, you have to **copy (.env.example) file and rename it to (.env)** and change **APP_DEBUG** to false and **APP_ENV** to production and **Mailer Options** and **Database Options.** You can see **[Laravel configuration page](https://laravel.com/framework/docs/13.x/configuration).** After that, you have to trigger this command: 
    
    ```bash
    composer run setup-production
    ```
    
    After that, if you change the **Database Connection** any time, you must trigger this command:
    
    ```bash
    php artisan migrate --force 
    ```
    
    You can see **[Laravel Deployment Page](https://laravel.com/framework/docs/13.x/deployment)** for more information or how to deploy this project to real server.

*   **

3.  **Third, optimization (caching):** to optimize this project you can trigger this command:

    ```bash
    composer run optimize-production
    ```

*   **

4.  **Finally, running :** if you want to run this project in your local machine, you can trigger this command:
    
    ```bash
    composer run serve
    ```
    
    This command with run **php server** and **queue server**.

<br>

## 🤖 Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install **[Laravel Boost](https://laravel.com/docs/ai)** to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

<br>

## ⚖️ License

This project is open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT).**
