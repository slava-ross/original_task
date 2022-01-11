<?php
class db {
    /**
     * -D - Локальная защищённая ссылка на объект связи с БД;
     * -V- {db_link} @:db_link ссылка на линк БД;
     */
    private $db_link = NULL;

    /**
     * -D, Method- Конструктор - Установка соединения с БД, выбор рабочей кодировки клиентской стороны;
     * -V- {String} @dbHost: IP-адрес или доменное имя сервера БД;
     * -V- {String} @dbLogin: Логин пользователя БД;
     * -V- {String} @dbPass: Пароль пользователя БД;
     * -V- {String} @dbName: Имя БД;
     */
    public function __construct($dbHost, $dbName, $dbLogin, $dbPass)
    {
        $dsn = "mysql:host=" . $dbHost . "; dbname=" . $dbName . "; charset=utf8";
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $this->db_link = new PDO($dsn, $dbLogin, $dbPass, $options);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * -D, Method- Запрос к БД;
     * @param $SQLQuery - строка SQL-запроса;
     * @param $qType - тип запроса;
     * @param null $parameters - параметры для подстановки в параметризованный запрос;
     * -V- {Array} @resultArr: массив с результатом выполнения запроса;
     * -V- {Boolean} @success: успешность выполнения запроса;
     * -V- {Array} @errors: массив сообщений об ошибках;
     * -V- {Int} @id: идентификатор полученного из БД объекта;
     * -R- @return Array(
        'success'	=> (bool),		// true - успешное выполнение, false - есть ошибки;
        'id'		=> (int),		// идентификатор объекта;
        'result'	=> array(),		// массив полученных данных;
        'errors'	=> array(),		// массив ошибок в строчном виде;
     );
     */
    public function db_query($SQLQuery, $qType, $parameters = null)
    {
        $result = array();
        $success = false;
        $errors = array();
        $id = NULL;

        switch ($qType) {
            case 'insert':
            case 'update':
            case 'delete':
            $stmt = $this->db_link->prepare($SQLQuery);
            $stmt->execute($parameters);
            $success = true;
            $id = $this->db_link->lastInsertId();
            break;
            case 'select':
                $stmt = $this->db_link->prepare($SQLQuery);
                $stmt->execute($parameters);
                $result = $stmt->fetchAll();
                $success = true;
            break;
            case 'select_row':
                $stmt = $this->db_link->prepare($SQLQuery);
                $stmt->execute($parameters);
                $result = $stmt->fetch();
                $success = true;
            break;
            default:
                $errors[] = "Неверный тип запроса";
        }
        return array(
            'success'       => $success,
            'result'	    => $result,
            'errors'	    => $errors
        );
    }
}
