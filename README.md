# Homestead
Для запуска проекта требуется docker.
1) Быть в корневой папке
2) В терминале написать: docker compose up -d
3) После создания всех контейнеров в docker, зайти на сайт по ссылке http://localhost/
Если не отображаются фотографии на сайте:
1) Удалить файл storage по пути homestead-main/public/public
2) В терминале зайти в контейнер php-1 (docker exec -it <id-container-php-1> bash)
3) Написать php artisan storage:link
4) Обновить страницу (Ctrl + F5)
