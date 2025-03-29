# Homestead
Для запуска проекта требуется docker:
1) Быть в корневой папке
2) В терминале написать: docker compose up -d
3) После создания всех контейнеров в docker, зайти на сайт по ссылке http://localhost/

Учётные записи (Пользователи - основные):
1) Арендатор: rent@mail.ru 12345678
2) Арендодатель: landlord@mail.ru 12345678 | landlord2@mail.ru 12345678 | landlord3@mail.ru 12345678 | landlord4@mail.ru 12345678
4) Администратор: admin@mail.ru 12345678

База данных (homestead) находится на http://localhost:7070/
*root* *root*

Если не отображаются фотографии на сайте:
1) Удалить файл storage по пути homestead-main/public/public
2) В терминале зайти в контейнер php-1 (docker exec -it id-container-php-1 bash)
3) Написать php artisan storage:link
4) Обновить страницу (Ctrl + F5)

Если не прогружаются графики в панели администратора:
1) Выйдите и зайдите обратно в этот раздел
