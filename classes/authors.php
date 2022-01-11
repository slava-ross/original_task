<?php
/**
 *	-D- Класс @authors - работа с авторами;
 */
class authors {
    /**
     * -D- Локальный защищённый экземпляр объекта БД;
     * -V- {db} @db: БД;
     */
    private $db = NULL;

    /**
     * -D, Method- Экземпляр объекта БД;
     * -V- {db} @db: БД;
     */
    public function setDB( $db ) {
        $this->db = $db;
    }

    /**
     * -D- @getAuthorsList - Метод чтения списка авторов из БД;
     * @return array
     */
    public function getAuthorsList () {
        $success = false;
        $authors = NULL;
        $errors = array();

        $query = '
		        SELECT id AS author_id, name AS author_name
                FROM `author` 
                ORDER BY name;
			';
        $res = $this->db->db_query($query, 'select');
        if ($res['success']) {
            $success = true;
            $authors = $res['result'];
        } else {
            $errors = $res['errors'];
        }
        return array(
            'success'	=> $success,
            'authors'	=> $authors,
            'errors'	=> $errors
        );
    }

    /**
     * -D- @getAuthors - Метод чтения одного автора по ID;
     * @param $authorId
     * @return array
     */
    public function getAuthor($authorId) {
        $success = false;
        $author = NULL;
        $errors = array();

        $parameters = ['id' => $authorId];
        $query = 'SELECT * FROM author WHERE id=:id;';
        $res = $this->db->db_query($query, 'select_row', $parameters);
        //echo "<pre>";var_dump($res['result']);die();
        if ($res['success']) {
            if ($res['result']) {
                $success = true;
                $author = $res['result'];
            } else {
                $errors[] = "Автор не найден!";
            }
        } else {
            $errors = $res['errors'];
        }
        return array(
            'success'   => $success,
            'errors'    => $errors,
            'author'    => $author
        );
    }

    /**
     * -D, Method- Метод выполняющий валидацию ввода полей информации об авторе и добавляющий её в БД;
     * @param $authorName
     * @return array
     */
    public function addAuthor($authorName) {
        $success = false;
        $id = NULL;
        $errors = array();

        $authorName = trim($authorName);

        if (empty($authorName)) {
            $errors[] = "Укажите имя автора!";
        } elseif (mb_strlen($authorName, 'utf-8') > 50) {
            $errors[] = "Имя автора не должно быть более 50 символов!";
        }

        if (count($errors) === 0) {
            $parameters = ['author_name' => $authorName];
            $query = 'SELECT COUNT(*) AS cnt FROM author WHERE name=:author_name;';
            $res = $this->db->db_query($query, 'select_row', $parameters);
            if ($res['success']) {
                if ($res['result']['cnt'] !== 0) {
                    $errors[] = "Такой автор уже есть!";
                } else {
                    $query = 'INSERT INTO author (name) VALUES (:author_name);';
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
     * -D, Method- Метод выполняющий валидацию ввода полей информации об авторе и изменяющий её в БД;
     * @param $authorId
     * @param $authorName
     * @return array
     */
    public function updateAuthor($authorId, $authorName) {
        $success = false;
        $id = NULL;
        $errors = array();

        $authorName = trim($authorName);

        if (empty($authorName)) {
            $errors[] = "Укажите имя автора!";
        } elseif (mb_strlen($authorName, 'utf-8') > 50) {
            $errors[] = "Имя автора не должно быть более 50 символов!";
        }

        if (count($errors) === 0) {
            $parameters = ['author_id' => $authorId, 'author_name' => $authorName];
            $query = 'SELECT COUNT(*) AS cnt FROM author WHERE name=:author_name AND id<>:author_id;';
            $res = $this->db->db_query($query, 'select_row', $parameters);
            if ($res['success']) {
                if ($res['result']['cnt'] !== 0) {
                    $errors[] = "Такой автор уже есть!";
                } else {
                    $query = 'UPDATE author SET name=:author_name WHERE id=:author_id;';
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
     * -D- @delAuthor - Метод удаления автора из БД;
     * @param $authorId
     * @return array
     */
    public function delAuthor($authorId) {
        $success = false;
        $errors = array();
        $parameters = ['id' => $authorId];

        $query = 'SELECT COUNT(*) AS cnt FROM author WHERE id=:id;';
        $res = $this->db->db_query($query, 'select_row', $parameters);
        if ($res['success']) {
            if ($res['result']['cnt'] !== 0) {
                $query = 'DELETE FROM author WHERE id=:id;';
                $res = $this->db->db_query($query, 'delete', $parameters);
                if ($res['success']) {
                    $success = true;
                } else {
                    $errors = $res['errors'];
                }
            } else {
                $errors[] = "Автор не найден!";
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
