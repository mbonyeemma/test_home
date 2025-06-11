docker-compose up -d --build

---
docker exec -it laravel56-app bash
php artisan key:generate
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000

Laravel is now at: http://localhost:8000
phpMyAdmin is at: http://localhost:8080