<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Задачник</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/style.css" >
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
    </head>
    <body class="bg-light">
        <header>
            <nav class="navbar navbar-light bg-light justify-content-between">
                <?php if($data['isAuth']): ?>
                    <a class="btn btn-outline-success my-2 my-sm-0" href="/admin/logout" role="button">Выйти</a>
                <?php else: ?>
                    <a class="btn btn-outline-success my-2 my-sm-0" href="/admin" role="button">Авторизоваться</a>
                <?php endif ?>
            </nav>
        </header>

        <main role="main" class="container">
            <h1>Задачник</h1>
            <div class="form-row my-3">
                <div class="form-group col-md-2">
                    <p>Сортировать по:</p>
                </div>
                <div class="form-group col-md-4">
                    <select class="form-control js-order">
                        <option value="id" class="js-order">По умолчанию</option>
                        <option value="name">Имя пользователя</option>
                        <option value="email">Email</option>
                        <option value="is_done">Статус</option>
                    </select>
                </div>
                <span class='sort up js-sort' data-sort="asc"></span>
            </div>
            <?php if (!empty($data['tasks'])): ?>
                <table cellspacing="0" cellpadding="5" class='table js-table'>
                    <thead>
                        <tr>
                            <th>Имя пользователя</th>
                            <th>e-mail</th>
                            <th>Текст задачи</th>
                            <th>Статус</th>
                            <?php if($data['isAuth']): ?>
                                <th></th>
                                <th></th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['tasks'] as $task): ?>
                            <tr class="task" data-task="<?=$task['id']?>">
                                <td>
                                    <?php if($data['isAuth']): ?><input class="form-control" type="text" name="name" value="<?=$task['name']?>" /><?php else: ?><?=$task['name']?> <?php endif ?>
                                </td>
                                <td>
                                    <?php if($data['isAuth']): ?><input class="form-control" type="text" name="email" value="<?=$task['email']?>" /><?php else: ?><?=$task['email']?> <?php endif ?>
                                </td>
                                <td>
                                    <?php if($data['isAuth']): ?><textarea class="form-control" name="task"><?=$task['task']?></textarea><?php else: ?><?=$task['task']?> <?php endif ?>
                                </td>
                                <td>
                                    <span class="js-is-done"><?=htmlspecialchars($task['is_done'])?></span><br><span class='js-is-edit'><?=htmlspecialchars($task['is_edit'])?></span>
                                </td>
                                <?php if($data['isAuth']): ?>
                                    <td>
                                        <?php if($task['is_done'] == 'Не выполнено'): ?><button type="submit" class="btn btn-primary js-task-done" name="done">Выполнено</button><?php endif ?>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary js-task-edit" name="edit">Сохранить</button>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>
            <nav aria-label="...">
                <?=$data['pagination']?>
            </nav>
            <h4>Добавить новую задачу</h4>
            <div class="bd-example my-3">
                <form method="post" action="">
                    <div class="form-group">
                        <label>Имя</label>
                        <input id='field_name' type="text" class="form-control" placeholder="Имя" name="name">
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input id='field_email' type="text" class="form-control" placeholder="E-mail" name="email">
                    </div>
                    <div class="form-group">
                        <label>Текст задачи</label>
                        <textarea id='field_task' class="form-control" rows="3" name="task"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary js-add-task" name="send">Сохранить</button>
                </form>
            </div>
        </main>
    </body>
</html>