<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php print $vars['title']; ?></title>
        <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="../css/bootstrap-icons.css" type="text/css">
        <link rel="stylesheet" href="../css/style.css" type="text/css">
        <link href="../images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo $this->get_url() ?>"><?php echo $this->config['SITE_NAME']; ?></a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item ms-5">
                                <a class="nav-link active" aria-current="page" href="<?php echo $this->get_url() ?>" tabindex="-1">Главная</a>
                            </li>
                            <?php if (!empty($vars['auth'])): ?>
                                <li class="nav-item ms-5">
                                    <a class="nav-link" href="<?php echo $this->get_url('admin_authors_list') ?>" tabindex="-1">Все авторы</a>
                                </li>
                                <li class="nav-item ms-5">
                                    <a class="nav-link" href="<?php echo $this->get_url('add_author') ?>" tabindex="-1">Добавить автора</a>
                                </li>
                                <li class="nav-item ms-5">
                                    <a class="nav-link" href="<?php echo $this->get_url('admin_books_list') ?>" tabindex="-1">Все книги</a>
                                </li>
                                <li class="nav-item ms-5">
                                    <a class="nav-link" href="<?php echo $this->get_url('add_book') ?>" tabindex="-1">Добавить книгу</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item me-5">
                            <?php if (empty($vars['auth'])): ?>
                                <a class="nav-link" href="<?php echo $this->get_url('auth') ?>" tabindex="-1">Вход</a>
                            <?php else: ?>
                                <a class="nav-link" href="<?php echo $this->get_url('exit') ?>" tabindex="-1">Выход</a>
                            <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main class="container container-main">
<?php
    if (array_key_exists('errors', $vars) && is_array($vars['errors'])) {
        foreach ($vars['errors'] as $error) {
            print('<div class="alert alert-danger alert-dismissible fade show mt-3 flash" role="alert">');
            print($error);
            print('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
            print('</div>');
        }
    }
    if (array_key_exists('messages', $vars) && is_array($vars['errors'])) {
        foreach ($vars['messages'] as $message) {
            print('<div class="alert alert-success alert-dismissible fade show mt-3 flash" role="alert">');
            print($message);
            print('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
            print('</div>');
        }
    }
    if (!empty($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $error) {
            print('<div class="alert alert-danger alert-dismissible fade show mt-3 flash" role="alert">');
            print($error);
            print('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
            print('</div>');
        }
        $_SESSION['errors'] = NULL;
    }
    if (!empty($_SESSION['messages'])) {
        foreach ($_SESSION['messages'] as $message) {
            print('<div class="alert alert-success alert-dismissible fade show mt-3 flash" role="alert">');
            print($message);
            print('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
            print('</div>');
        }
        $_SESSION['messages'] = NULL;
    }
?>