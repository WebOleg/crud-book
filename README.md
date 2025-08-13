# 📚 Довідник Книг - Laravel Додаток

Професійна система управління книгами та авторами з повним функціоналом CRUD.

## 🚀 Основні можливості

-   Управління книгами з завантаженням зображень
-   Управління авторами з валідацією
-   Розширений пошук за назвою та автором
-   AJAX інтерфейс з модальними вікнами
-   Пагінація по 15 елементів
-   61 тест з повним покриттям

## 🛠 Технічний стек

-   Laravel 11, PHP 8.2+, MySQL 8.0
-   Bootstrap 5, jQuery, модульний JavaScript
-   Service Layer, API Resources

## 🚀 Швидкий старт

```bash
# 1. Клонування
git clone <repository-url>
cd book-directory

# 2. Встановлення
composer install
cp .env.example .env
php artisan key:generate

# 3. База даних (Docker)
docker-compose up -d
php artisan migrate --seed

# 4. Запуск
php artisan storage:link
php artisan serve
Відкрийте: http://localhost:8000

🧪 Тестування
bash# Швидкий запуск
./run-tests.sh

# Або вручну
docker-compose -f docker-compose.test.yml up -d
php artisan migrate:fresh --env=testing
php artisan test --env=testing

📊 Моніторинг
bash# Перевірка стану
curl http://localhost:8000/health

# Очищення файлів
php artisan images:cleanup --dry-run

🎯 Використання

Автори:

Пошук за прізвищем/ім'ям
Сортування клікнутим на заголовки
Додавання через "Add Author"

Книги:

Окремий пошук за назвою та автором
Завантаження обкладинки (JPG/PNG, до 2МБ)
Вибір кількох авторів

⚙️ Валідація

Автори:

Прізвище: обов'язкове, мінімум 3 символи
Ім'я: обов'язкове

Книги:

Назва: обов'язкова
Дата публікації: обов'язкова
Автори: мінімум один
Зображення: JPG/PNG, до 2МБ

🏆 Enterprise фічі

Health Check: /health
Performance Logging
Cache Management
File Cleanup команди
Database Optimization
61 тест (184 assertions) ✅

```
