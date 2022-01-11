<form method="post" class="container col-sm-3">
    <div class="form-group">
        <label for="author_name">Ф. И. О. автора</label>
        <input id="author_name" name="author_name" type="text" class="form-control" value="<?php echo $vars['author']['name'] ?? '' ?>">
        <input name="author_id" type="hidden" class="form-control" value="<?php echo $vars['author']['id'] ?? '' ?>">
    </div>
    <input type="submit" name="submit" value="<?php echo $vars['btn_text'] ?>" class="btn btn-outline-success">
</form>