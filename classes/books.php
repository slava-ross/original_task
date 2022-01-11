<?php
/**
*	-D- Класс @books - работа с книгами;
*/
class books {
    /**
     * -D- Локальный защищённый экземпляр объекта БД;
     * -V- {db} @db: БД;
     */
    private $db = NULL;

    /**
     * -D, Method- Установка экземпляра объекта БД;
     * -V- {db} @db: БД;
     */
    public function setDB( $db ) {
        $this->db = $db;
    }

    /**
     * -D- Поиск по картотеке (открытая часть приложения)
     * @param $authorName
     * @param $bookName
     * @return array
     */
    public function searchBooks($authorName, $bookName) {
        $success = false;
        $errors = array();
        $books = array();

        $authorName = trim($authorName);
        $bookName = trim($bookName);

        if (empty($authorName) && empty($bookName)) {
            $errors[] = "Укажите название книги или имя автора!";
        }
        if (mb_strlen($authorName, 'utf-8') > 50) {
            $errors[] = "Имя автора не должно быть более 50 символов!";
        }
        if (mb_strlen($bookName, 'utf-8') > 100) {
            $errors[] = "Название книги не должно быть более 100 символов!";
        }

        if (count($errors) === 0) {
            $parameters = [
                'author_name' => "%$authorName%",
                'book_name' => "%$bookName%"
            ];

            $query = '
                SELECT
                    a.name AS author_name,
                    b.id AS book_id,
                    b.name AS book_name,
                    b.reader_count AS reader_count
                FROM author AS a 
                LEFT JOIN book AS b
                    ON a.id = b.author_id
                WHERE a.name LIKE :author_name AND b.name LIKE :book_name    
                ORDER BY b.reader_count DESC, a.name, b.name;
            ';

            $res = $this->db->db_query($query, 'select', $parameters);
            if ($res['success']) {
                $success = true;
                if (count($res['result']) > 0) {                   // Если нашли какие-то книги, то ...
                    foreach ($res['result'] as $book) {            // сформируем массив для просмотра с увеличенным на 1 количеством читателей
                        $book['reader_count'] = $book['reader_count'] + 1;
                        $books[] = $book;
                    }
                    $ids = array_column($books, 'book_id');     // получим id книг, которые будут получены читателями, и увеличим им количество просмотров в БД
                    $query = 'UPDATE book SET reader_count = reader_count + 1 WHERE id IN(' . implode(',', $ids) . ');';
                    $res = $this->db->db_query($query, 'update');
                    if (!$res['success']) {
                        $success = false;
                        $books = NULL;
                        $errors = $res['errors'];
                    }
                }
            } else {
                $errors = $res['errors'];
            }
        }
        return array(
            'success'   => $success,
            'books'     => $books,
            'errors'    => $errors
        );
    }

    /**
     * -D- Формирование рядов таблицы для Ajax-ответа
     * @param $booksArray
     * @return string
     */
    public function makeRows ($booksArray) {
        $rows = '';
        if (count($booksArray) !== 0) {
            foreach ($booksArray as $book) {
                $rows .= '<tr class="d-flex">';
                $rows .= '<td class="col-5 col-sm-5">' . $book['book_name'] . '</td>';
                $rows .= '<td class="col-4 col-sm-4">' . $book['author_name'] . '</td>';
                $rows .= '<td class="col-3 col-sm-3">' . $book['reader_count'] . '</td>';
                $rows .= '</tr>';
            }
        } else {
            $rows = '<tr><td colspan="3" class="text-center">По запросу ничего не найдено</td></tr>';
        }
        return $rows;
    }

    /**
     * -D- Формирование FLASH-сообщений об ошибках для Ajax-ответа
     * @param $errorsArray
     * @return string
     */
    public function makeErrors ($errorsArray) {
        $errors = '';
        if (count($errorsArray) !== 0) {
            foreach ($errorsArray as $error) {
                $errors .= '<div class="alert alert-danger alert-dismissible fade show mt-3 flash" role="alert">';
                $errors .= $error;
                $errors .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                $errors .= '</div>';
            }
        }
        return $errors;
    }

    /**
     * -D- @getBook - Метод чтения одной книги по ID;
     * @param $bookId
     * @return array
     */
    public function getBook($bookId) {
        $success = false;
        $book = NULL;
        $errors = array();

        $parameters = ['id' => $bookId];
        $query = 'SELECT * FROM book WHERE id=:id;';
        $res = $this->db->db_query($query, 'select_row', $parameters);
        if ($res['success']) {
            if ($res['result']) {
                $success = true;
                $book = $res['result'];
            } else {
                $errors[] = "Книга не найдена!";
            }
        } else {
            $errors = $res['errors'];
        }
        return array(
            'success'   => $success,
            'errors'    => $errors,
            'book'      => $book
        );
    }

    /**
     * -D- @getBooksList - Метод чтения списка книг из БД;
     * @return array
     */
    public function getBooksList () {
        $success = false;
        $books = NULL;
        $errors = array();

        $query = '
            SELECT
                a.name AS author_name,
                b.id AS book_id,
                b.name AS book_name,
                b.reader_count AS reader_count
            FROM author AS a 
            JOIN book AS b
                ON a.id = b.author_id
            ORDER BY a.name, b.name;
        ';
        $res = $this->db->db_query($query, 'select');
        if ($res['success']) {
            $success = true;
            $books = $res['result'];
        } else {
            $errors = $res['errors'];
        }
        return array(
            'success'	=> $success,
            'books'	    => $books,
            'errors'	=> $errors
        );
    }

    /**
     * -D, Method- Метод выполняющий валидацию ввода полей информации о книге и добавляющий её в БД;
     * @param $bookName
     * @param $authorId
     * @return array
     */
    public function addBook($bookName, $authorId) {
        $success = false;
        $id = NULL;
        $errors = array();

        $bookName = trim($bookName);

        if (empty($bookName)) {
            $errors[] = "Укажите название книги!";
        } elseif (mb_strlen($bookName, 'utf-8') > 100) {
            $errors[] = "Название книги не должно быть более 100 символов!";
        }
        if ($authorId == 0) {
            $errors[] = "Выберите автора";
        }

        if (count($errors) === 0) {
            $parameters = [
                'book_name' => $bookName,
                'author_id' => $authorId
            ];
            $query = 'SELECT COUNT(*) AS cnt FROM book WHERE name=:book_name AND author_id=:author_id;';
            $res = $this->db->db_query($query, 'select_row', $parameters);
            if ($res['success']) {
                if ($res['result']['cnt'] !== 0) {
                    $errors[] = "Такая книга уже есть в картотеке!";
                } else {
                    $query = 'INSERT INTO book (name, author_id) VALUES (:book_name, :author_id);';
                    $res = $this->db->db_query($query, 'insert', $parameters);
                    if ($res['success']) {
                        $success = true;
                        $id = $res['id'];
                    } else {
                        $errors = $res['errors'];
                    }
                }
            } else {
                $errors = $res['errors'];
            }
        }
        return array(
            'success'   => $success,
            'errors'    => $errors,
            'id'        => $id
        );
    }

    /**
     * -D, Method- Метод выполняющий валидацию ввода полей информации о книге и изменяющий её в БД;
     * @param $bookId
     * @param $bookName
     * @param $authorId
     * @return array
     */
    public function updateBook($bookId, $bookName, $authorId) {
        $success = false;
        $id = NULL;
        $errors = array();

        $bookName = trim($bookName);

        if (empty($bookName)) {
            $errors[] = "Укажите название книги!";
        } elseif (mb_strlen($bookName, 'utf-8') > 100) {
            $errors[] = "Название книги не должно быть более 100 символов!";
        }
        if ($authorId == 0) {
            $errors[] = "Выберите автора";
        }

        if (count($errors) === 0) {
            $parameters = [
                'book_id'   => $bookId,
                'book_name' => $bookName,
                'author_id' => $authorId
            ];
            $query = 'SELECT COUNT(*) AS cnt FROM book WHERE name=:book_name AND author_id=:author_id AND id<>:book_id;';
            $res = $this->db->db_query($query, 'select_row', $parameters);
            if ($res['success']) {
                if ($res['result']['cnt'] !== 0) {
                    $errors[] = "Такая книга уже есть!";
                } else {
                    $query = 'UPDATE book SET name=:book_name, author_id=:author_id WHERE id=:book_id;';
                    $res = $this->db->db_query($query, 'update', $parameters);
                    if ($res['success']) {
                        $success = true;
                        $id = $res['id'];
                    } else {
                        $errors = $res['errors'];
                    }
                }
            } else {
                $errors = $res['errors'];
            }
        }
        return array(
            'success'   => $success,
            'errors'    => $errors,
            'id'        => $id
        );
    }

    /**
     * -D- @delBook - Метод удаления книги из БД;
     * @param $bookId
     * @return array
     */
    public function delBook($bookId) {
        $success = false;
        $errors = array();
        $parameters = ['id' => $bookId];

        $query = 'SELECT COUNT(*) AS cnt FROM book WHERE id=:id;';
        $res = $this->db->db_query($query, 'select_row', $parameters);
        if ($res['success']) {
            if ($res['result']['cnt'] !== 0) {
                $query = 'DELETE FROM book WHERE id=:id;';
                $res = $this->db->db_query($query, 'delete', $parameters);
                if ($res['success']) {
                    $success = true;
                } else {
                    $errors = $res['errors'];
                }
            } else {
                $errors[] = "Книга не найдена!";
            }
        } else {
            $errors = $res['errors'];
        }
        return array(
            'success'	=> $success,
            'errors'	=> $errors
        );
    }
}
