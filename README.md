# Startup Management

A modern web application built with Laravel and TailwindCSS for managing startup operations efficiently.

## 🚀 Features

-   Modern and responsive UI with TailwindCSS
-   Secure authentication system
-   RESTful API architecture
-   Efficient asset management with Laravel Mix

## 📋 Prerequisites

Before you begin, ensure you have met the following requirements:

-   PHP >= 8.0
-   Composer
-   Node.js & NPM
-   MySQL/PostgreSQL

## 🛠️ Installation

1. Clone the repository:

```bash
git clone https://github.com/bimashazaman/startup-management-system
cd startup-management
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install NPM dependencies:

```bash
npm install
```

4. Create a copy of the .env file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure your database in the .env file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:

```bash
php artisan migrate
```

8. Compile assets:

```bash
npm run dev
```

## 🚀 Usage

To start the development server:

```bash
php artisan serve
```

For compiling assets and watching for changes:

```bash
npm run watch
```

## 🔧 Development

Want to contribute? Great!

1. Fork the repository
2. Create a new branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Commit your changes (`git commit -m 'Add some amazing feature'`)
5. Push to the branch (`git push origin feature/amazing-feature`)
6. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

-   Bimasha Zaman

## 🙏 Acknowledgments

-   Laravel Team
-   TailwindCSS Team
-   All contributors who help to make this project better

## 📞 Contact

If you have any questions, feel free to reach out:

-   Project Link: [https://github.com/bimashazaman/startup-management-system](https://github.com/bimashazaman/startup-management-system)
