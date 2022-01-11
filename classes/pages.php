<?php
/**
*   -D- @pages - Класс "сборщика страниц" (Page Controller);
*/
class pages
{
    /**
     * -D - Признак авторизации;
     * -V- {boolean} @authorized: БД;
     */
    private $authorized = NULL;

    /**
     * -D - Локальный защищённый экземпляр объекта БД;
     * -V- {db} @db: БД;
     */
    private $db = NULL;

    /**
     * -D - Локальный защищённый экземпляр массива настроек;
     * -V- {Array} @config: Массив настроек;
     */
    private $config = NULL;

    /**
     * -D, Method- Установка локального экземпляра объекта БД;
     * -V- {db} @db: БД;
     */
    public function setDB($db)
    {
        $this->db = $db;
    }

    /**
     * -D, Method- Сеттер конфигурации;
     * -V- {Array} @config: Массив настроек;
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * -D, Method- Формирование строки URL;
     * @param string $page
     * @return string
     */
    public function get_url($page = '')
    {
        return "http://" . $_SERVER['HTTP_HOST'] . "/$page";
    }

    /**
     *  -D- @getTemplate - Метод подключения шаблона с передачей ему необходимых для отображения страницы параметров;
     * @param $file
     * @param array $vars
     */
    public function getTemplate($file, $vars=array())
    {
        include "templates/" . $file;
    }

    /**
     * -D, Method- Сборка главной страницы;
     */
    private function index()
    {
        $this->getTemplate('header.tpl', array(
            'title'=>'Картотека',
            'auth'=>$this->authorized
        ));
        $this->getTemplate('main.tpl');
        $this->getTemplate('footer.tpl');
    }

    /**
     *	-D, Method- Формирование и отображение страницы "Вход" (Авторизация);
     */
    private function getAuthPage($user)
    {
        if (isset($_POST['submit'])) { // Если нажали "Вход"
            $result = $user->auth($_POST['login'], $_POST['passwd']);
            if ($result['success']) {
                header('Location: /admin_books_list');
                die;
            }
            else {
                $this->getTemplate('header.tpl', array(
                    'title'=>'Вход',
                    'auth'=>$this->authorized,
                    'errors' => $result['errors']
                ));
                $this->getTemplate('auth.tpl');
            }
        } else { // Если открылась страница авторизации
            $this->getTemplate('header.tpl', array(
                'title'=>'Вход',
                'auth'=>$this->authorized
            ));
            $this->getTemplate('auth.tpl');
        }
        $this->getTemplate('footer.tpl');
    }

    /**
     * -D, Method- Сборка страницы отображения списка всех книг в разделе администрирования;
     */
    private function getAdminBooksListPage()
    {
        if ($this->authorized) {
            include('classes/books.php');
            $books = new books;
            $books->setDB($this->db);

            $result = $books->getBooksList();

            if ($result['success']) {
                $this->getTemplate('header.tpl', array(
                    'title' => 'Управление картотекой - Книги',
                    'auth' => $this->authorized
                ));
                $this->getTemplate('admin_books_list.tpl', array(
                    'books' => $result['books']
                ));
                $this->getTemplate('footer.tpl');
            }
        } else {
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Сборка страницы отображения списка всех авторов в разделе администрирования;
     */
    private function getAdminAuthorsListPage()
    {
        if ($this->authorized) {
            include('classes/authors.php');
            $authors = new authors;
            $authors->setDB($this->db);

            $result = $authors->getAuthorsList();

            if ($result['success']) {
                $this->getTemplate('header.tpl', array(
                    'title' => 'Управление картотекой - Авторы',
                    'auth' => $this->authorized
                ));
                $this->getTemplate('admin_authors_list.tpl', array(
                    'authors' => $result['authors']
                ));
                $this->getTemplate('footer.tpl');
            }
        } else {
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Сборка страницы добавления нового автора в разделе администрирования;
     */
    private function getAddAuthorPage()
    {
        if ($this->authorized) {
            $result = array();
            $author = array();

            include('classes/authors.php');
            $authors = new authors;
            $authors->setDB($this->db);

            if (isset($_POST['submit'])) {

                $result = $authors->addAuthor($_POST['author_name']);

                if ($result['success']) {
                    $_SESSION['messages'][] = "Добавлен новый автор";
                    header('Location: /admin_authors_list');
                    die;
                }
            }

            $author['name'] = $_POST['author_name'] ?? '';

            $this->getTemplate('header.tpl', array(
                'title' => 'Управление картотекой - Новый автор',
                'auth' => $this->authorized,
                'errors' => $result['errors'] ?? ''
            ));
            $this->getTemplate('add_author.tpl', array(
                'author'    => $author,
                'btn_text'  => 'Добавить'
            ));
            $this->getTemplate('footer.tpl');
        } else {
            $_SESSION['errors'][] = "Сначала авторизуйтесь!";
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Сборка страницы редактирования автора в разделе администрирования;
     */
    private function getEditAuthorPage()
    {
        if ($this->authorized) {
            include('classes/authors.php');
            $authors = new authors;
            $authors->setDB($this->db);

            $authorId = (int)$_GET['id'] ?? NULL;

            if (isset($_POST['submit'])) {
                //echo "<pre>";var_dump($_POST);die();
                $result = $authors->updateAuthor((int)$_POST['author_id'], $_POST['author_name']);
                if ($result['success']) {
                    $_SESSION['messages'][] = "Автор отредактирован";
                    header('Location: /admin_authors_list');
                    die;
                } else {
                    $_SESSION['errors'] = $result['errors'];
                }
            }
            // Новая страница
            $result = $authors->getAuthor($authorId);
            if ($result['success']) {
                $author = $result['author'];
                //echo "<pre>";var_dump($author);die();
                $this->getTemplate('header.tpl', array(
                    'title' => 'Управление картотекой - Редактирование автора №' . $author['id'],
                    'auth' => $this->authorized,
                    'errors' => $result['errors']
                ));
                $this->getTemplate('edit_author.tpl', array(
                    'author' => $author,
                    'btn_text'  => 'Сохранить'
                ));
                $this->getTemplate('footer.tpl');

            } else {
                $_SESSION['errors'] = $result['errors'];
                header('Location: /admin_authors_list');
                die;
            }
        } else {
            $_SESSION['errors'][] = "Сначала авторизуйтесь!";
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Удаление автора;
     */
    private function deleteAuthor()
    {
        if ($this->authorized) {
            include('classes/authors.php');
            $authors = new authors;
            $authors->setDB($this->db);

            if (!empty($_GET['id'])) {
                $result = $authors->delAuthor((int)$_GET['id']);

                if ($result['success']) {
                    $_SESSION['messages'][] = "Автор удалён";
                } else {
                    $_SESSION['errors'] = $result['errors'];
                }
            } else {
                $_SESSION['errors'][] = 'Ошибка удаления автора';
            }
            header('Location: /admin_authors_list');
            die;
        } else {
            $_SESSION['errors'][] = "Сначала авторизуйтесь!";
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Сборка страницы добавления новой книги в разделе администрирования;
     */
    private function getAddBookPage()
    {
        if ($this->authorized) {
            $result = array();
            $book = array();

            include('classes/books.php');
            $books = new books;
            $books->setDB($this->db);

            $page_errors = [];

            if (isset($_POST['submit'])) {

                $result = $books->addBook($_POST['book_name'], $_POST['author_id']);

                if ($result['success']) {
                    $_SESSION['messages'][] = "Добавлена новая книга";
                    header('Location: /admin_books_list');
                    die;
                } else {
                    $page_errors = $result['errors'];
                }
            }

            $book['name'] = $_POST['book_name'] ?? '';
            $book['author_id'] = $_POST['author_id'] ?? '';

            include('classes/authors.php');
            $authors = new authors;
            $authors->setDB($this->db);
            $result = $authors->getAuthorsList();

            $this->getTemplate('header.tpl', array(
                'title'     => 'Управление картотекой - Новая книга',
                'auth'      => $this->authorized,
                'errors'    => array_merge($page_errors, $result['errors'])
            ));

            $this->getTemplate('add_book.tpl', array(
                'book'          => $book,
                'authorsList'   => $result['authors'],
                'btn_text'      => 'Добавить'
            ));
            $this->getTemplate('footer.tpl');
        } else {
            $_SESSION['errors'][] = "Сначала авторизуйтесь!";
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Сборка страницы редактирования книги в разделе администрирования;
     */
    private function getEditBookPage()
    {
        if ($this->authorized) {
            include('classes/books.php');
            $books = new books;
            $books->setDB($this->db);

            $bookId = (int)$_GET['id'] ?? NULL;
            $page_errors = [];

            if (isset($_POST['submit'])) {
                //echo "<pre>";var_dump($_POST);die();
                $result = $books->updateBook((int)$_POST['book_id'], $_POST['book_name'], (int)$_POST['author_id']);
                if ($result['success']) {
                    $_SESSION['messages'][] = "Книга отредактирована";
                    header('Location: /admin_books_list');
                    die;
                } else {
                    $page_errors = $result['errors'];
                }
            }  // Новая страница

            include('classes/authors.php');
            $authors = new authors;
            $authors->setDB($this->db);
            $result_authors = $authors->getAuthorsList();

            $result = $books->getBook($bookId);

            if ($result['success']) {
                $book = $result['book'];
                //echo "<pre>";var_dump($_POST['author_id']);die();
                $this->getTemplate('header.tpl', array(
                    'title'     => 'Управление картотекой - Редактирование книги №' . $book['id'],
                    'auth'      => $this->authorized,
                    'errors'    => array_merge($page_errors, $result['errors'])
                ));
                $this->getTemplate('edit_book.tpl', array(
                    'book' => $book,
                    'authorsList' => $result_authors['authors'],
                    'btn_text'  => 'Сохранить'
                ));
                $this->getTemplate('footer.tpl');

            } else {
                $_SESSION['errors'] = $result['errors'];
                header('Location: /admin_books_list');
                die;
            }
        } else {
            $_SESSION['errors'][] = "Сначала авторизуйтесь!";
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Удаление книги;
     */
    private function deleteBook()
    {
        if ($this->authorized) {
            include('classes/books.php');
            $books = new books;
            $books->setDB($this->db);

            if (!empty($_GET['id'])) {
                $result = $books->delBook((int)$_GET['id']);

                if ($result['success']) {
                    $_SESSION['messages'][] = "Книга удалена";
                } else {
                    $_SESSION['errors'] = $result['errors'];
                }
            } else {
                $_SESSION['errors'][] = 'Ошибка удаления книги';
            }
            header('Location: /admin_books_list');
            die;
        } else {
            $_SESSION['errors'][] = "Сначала авторизуйтесь!";
            header('Location: /auth');
            die;
        }
    }

    /**
     * -D, Method- Сборка содержимого Ajax-ответа в формате JSON для формирования результатов поиска по картотеке;
     */
    private function getBooksListPage()
    {
        include('classes/books.php');
        $books = new books;
        $books->setDB($this->db);

        $result = $books->searchBooks($_POST['author_name'], $_POST['book_name']);

        if ($result['success']) {
            $html = $books->makeRows($result['books']);
        } else {
            $html = $books->makeErrors($result['errors']);
        }

        print(json_encode([
            'success'   => $result['success'],
            'html'      => $html
        ]));
    }

    /**
     * -D, Method- Выход из режима администрирования;
     * @param $user
     */
    private function getExit($user)
    {
        $user->logout();
    }

    /**
     *  -D, Method- @router - метод задающий "маршрут" приложения для генерации соответствующей страницы
     *  @param $page
     */
    public function router($page)
    {
        include('classes/user.php');
        $user = new user;
        $user->setConfig($this->config);
        $this->authorized = $user->isAuth();

        /**
        *   -D- Выбор метода для генерации нужной страницы
        */
        switch ($page)
        {
            case 'list':
                $this->getBooksListPage();
                break;
            case 'auth':
                $this->getAuthPage($user);
                break;
            case 'admin_books_list':
                $this->getAdminBooksListPage();
                break;
            case 'admin_authors_list':
                $this->getAdminAuthorsListPage();
                break;
            case 'add_book':
                $this->getAddBookPage();
                break;
            case 'edit_book':
                $this->getEditBookPage();
                break;
            case 'delete_book':
                $this->deleteBook();
                break;
            case 'add_author':
                $this->getAddAuthorPage();
                break;
            case 'edit_author':
                $this->getEditAuthorPage();
                break;
            case 'delete_author':
                $this->deleteAuthor();
                break;
            case 'exit':
                $this->getExit($user);
                break;
            case 'main':
            default:
                $this->index();
        }
    }
}
