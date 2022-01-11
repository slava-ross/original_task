<h1 class="text-center mt-4">Авторы</h1>
<div class="row mt-3">
    <table class="table table-striped">
        <thead>
        <tr class="d-flex">
            <th scope="col" class="col-2 col-sm-1">#</th>
            <th scope="col" class="col-6 col-sm-9">Ф. И. О. автора</th>
            <th scope="col" class="col-4 col-sm-2">Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($vars['authors'] as $key => $author) { ?>
            <tr class="d-flex">
                <th scope="row" class="col-2 col-sm-1"><?php echo $key + 1; ?></th>
                <td class="col-6 col-sm-9"><?php echo $author['author_name']; ?></td>
                <td class="col-4 col-sm-2">
                    <a href="<?php echo $this->get_url('edit_author?id=' . $author['author_id']); ?>" class="btn btn-warning btn-sm" title="Редактировать"><i class="bi bi-pencil"></i></a>
                    <a href="<?php echo $this->get_url('delete_author?id=' . $author['author_id']); ?>" class="btn btn-danger btn-sm btn-del" title="Удалить"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
