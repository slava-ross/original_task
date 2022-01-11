<form method="post" class="container col-sm-3">
    <div class="form-group">
        <label for="author_select">Автор</label>
        <select class="form-select" name="author_id">
            <option value="0">Выберите из списка</option>
            <?php
                foreach ($vars['authorsList'] as $author) {
                    print('<option value="' . $author['author_id'] . '"');
                    if ($vars['book']['author_id'] == $author['author_id']) print(' selected="selected"');
                    print('>' . $author['author_name'] . '</option>');
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="book_name">Название книги</label>
        <input id="book_name" name="book_name" type="text" class="form-control" value="<?php echo $vars['book']['name'] ?? '' ?>">
        <input name="book_id" type="hidden" class="form-control" value="<?php echo $vars['book']['id'] ?? '' ?>">
    </div>
    <input type="submit" name="submit" value="<?php echo $vars['btn_text'] ?>" class="btn btn-outline-success">
</form>
