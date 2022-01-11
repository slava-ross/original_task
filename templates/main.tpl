<h1 class="text-center mt-4">Поиск книг в картотеке</h1>
<form id="book-search-form" class="container col-sm-3" method="post">
    <input type="hidden" name="_token" value="<?php echo $_SESSION['token']; ?>">
    <div class="form-group">
        <label for="book_name">Название книги:</label>
        <input id="book_name" name="book_name" type="text" class="form-control" value="<?php echo $_POST['book_name'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="author_name">Автор:</label>
        <input id="author_name" name="author_name" type="text" class="form-control" value="<?php echo $_POST['author_name'] ?? '' ?>">
    </div>
    <input id="book-search" type="submit" name="submit" value="Поиск" class="btn btn-outline-success">
</form>

<!-- Прелоадер -->
<div id="loader" class="overlay-loader">
    <div class="loader-background"></div>
    <img class="loader-icon spinning-cog" src="../images/cog03.svg">
</div>

<div id="book-searching-result" class="container">
    <div class="row mt-3">
        <table class="table table-striped">
            <thead>
                <tr class="d-flex">
                    <th scope="col" class="col-5 col-sm-5">Название книги</th>
                    <th scope="col" class="col-4 col-sm-4">Автор</th>
                    <th scope="col" class="col-3 col-sm-3">Кол-во читателей</th>
                </tr>
            </thead>
            <tbody id="table-rows"></tbody>
        </table>
    </div>
</div>