# Приложение "Картотека книг" (original_task)

## Установка
- Задача выполнена с использованием PHP v7.4.8
- Для нормальной работы приложения в его корневой директории (там же, где расположен index.php) необходим файл
  *.htaccess* с содержимым:

```
Options +FollowSymLinks -Indexes
RewriteEngine On
RewriteRule ^([0-9A-Za-z-_]+)/?$ /index.php?page=$1 [L,QSA]
```
- Для запуска приложения необходимо выполнить настройки окружения создав файл *.env* из шаблона *.env_default*:

```
SITE_NAME - указать имя приложения, отображаемое в логотипе заголовка
ADMIN_LOGIN - учётное имя пользователя с правами администратора
ADMIN_PASS - пароль администратора

DB_HOST - IP-адрес сервера СУБД
DB_NAME - имя базы данных
DB_USER - учётное имя пользователя базы данных
DB_PASS - пароль пользователя базы данных

```
## Техническое задание

Реализовать картотеку. Есть книги, их авторы и читатели. У каждой книги есть автор, автор может иметь несколько книг.
Читатель фиксируется как «обращение» к книге.

Веб приложение должно состоять из 2 частей: 
1.	Открытая часть
2.	Закрытая часть (только для админов)

В Открытой части должно быть реализовано следующее:
1.	Форма авторизации для админов и перехода в закрытую часть
2.	Интерфейс поиска книги по наименованию/автору (AJAX запрос на серверную часть, ответ в JSON,
    вывод результата в виде списка без перезагрузки страницы). Выводимые данные (наименование книги, автор,
    кол-во читателей, отсортировано от более популярного вниз)

В Закрытой части:
1.	Интерфейс для создания, редактирования и удаления книг/авторов.

В Серверная части:
1.	Каждый запрос поиска книги/автора должен автоматически создавать читателей на весь найденный объем книг.

Требования:
1.	Использовать PHP
2.	Не использовать никаких фрэймворков.
3.	PostgreSQL (можно MySQL)
4.	Bootstrap для верстки (не обязательно)

Ожидаемый результат:
1.	Исходный код
2.	Скрипт создания БД
3.	Требования к системе и инструкция по установке
