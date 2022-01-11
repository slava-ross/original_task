<h1 class="text-center mt-4">Книги</h1>
<div class="row mt-3">
    <table class="table table-striped">
        <thead>
        <tr class="d-flex">
            <th scope="col" class="col-1 col-sm-1">#</th>
            <th scope="col" class="col-3 col-sm-3">Автор</th>
            <th scope="col" class="col-4 col-sm-5">Название книги</th>
            <th scope="col" class="col-2 col-sm-1">Кол-во читателей</th>
            <th scope="col" class="col-2 col-sm-2">Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($vars['books'] as $key => $book) { ?>
            <tr class="d-flex">
                <th scope="row" class="col-1 col-sm-1"><?php echo $key + 1; ?></th>
                <td class="col-3 col-sm-3"><?php echo $book['author_name']; ?></td>
                <td class="col-4 col-sm-5"><?php echo $book['book_name']; ?></td>
                <td class="col-2 col-sm-1"><?php echo $book['reader_count']; ?></td>
                <td class="col-2 col-sm-2">
                    <a href="<?php echo $this->get_url('edit_book?id=' . $book['book_id']); ?>" class="btn btn-warning btn-sm" title="Редактировать"><i class="bi bi-pencil"></i></a>
                    <a href="<?php echo $this->get_url('delete_book?id=' . $book['book_id']); ?>" class="btn btn-danger btn-sm btn-del" title="Удалить"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
