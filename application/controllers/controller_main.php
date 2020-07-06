<?php

class Controller_Main extends Controller
{
	function __construct()
	{
		$this->model = new Model_Main();
		$this->view = new View();
	}

	public function action_index()
	{
        $data = array();
        $data['tasks'] = $this->model->getTasks();
        $data['pagination'] = $this->pagination();
        $data['isAuth'] = $this->isAuth();
		$this->view->generate('main_view', $data);
	}

    /**
     * Выводим пагинацию
     */
    public function pagination($page = 1)
    {
        $allTasks = $this->model->getAllTasks();
        $count = 3;
        $tasks_count = count($allTasks);
        $pages = ceil(count($allTasks)/$count);
        
        if($pages > 1){
			$pagination = '<ul class="pagination js-pagination">';
			for($i=1;$i<=$pages;$i++){
				if ($i == $page)
					$class='active';
				else
					$class='';
				$pagination .= '<li class="page-item ' . $class . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
			}						
			$pagination .= '';
		
		}
		else{
			$pagination = '';
		}		
		
		return $pagination;
    }

    /**
     * Добавляем задачу
     */
    public function action_addTask()
    {
        $data = $_POST;
        $reqiered_fields = array('name', 'email', 'task');
        $empty_fields = array();
        // проверка полей
        foreach($reqiered_fields as $field)
        {
            if (empty($data[$field])) {
                $empty_fields[] = $field;                
            }
        }        
        if ($empty_fields) {
            die(json_encode(array(
                'status' => 'error',
                'message' => 'Заполнены не все поля',
                'fields' => $empty_fields,
            )));            
        }
        // прверка email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            die(json_encode(array(
                'status' => 'error',
                'message' => 'Неверно указан email',
                'fields' => array('email'),
            )));       
        }

        $this->model->insert('tasks', array(
            'name' => $data['name'],
            'email' => $data['email'],
            'task' => $data['task'],
        ));

        die(json_encode(array(
            'status' => 'ok',
            'message' => 'Задача добавлена',
        )));   
    }

    /**
     * Сортируем
     */
    public function action_sortTask()
    {
        $data['order'] = $_POST['order'];
        $data['sort'] = $_POST['sort'];
        $data['page'] = $_POST['page'];
        $limit = 3;
        $ofset = $limit * ($data['page'] - 1);
        $data['tasks'] = $this->model->getTasks($data['order'], $data['sort'], $limit, $ofset);
        $content = '';

        if (!empty($data['tasks'])) {
            foreach ($data['tasks'] as $task)
            {
                $content .= '<tr class="task" data-task=' . $task['id'] . '>
                        <td>' . ($this->isAuth() ? '<input class="form-control" type="text" name="name" value="' . $task['name'] . '" />': $task['name']) . '</td>
                        <td>' . ($this->isAuth() ? '<input class="form-control" type="text" name="email" value="' . $task['email'] . '" />': $task['email']) . '</td>
                        <td>' . ($this->isAuth() ? '<textarea class="form-control" name="task">' .  htmlspecialchars($task['task']) . '</textarea>' :  htmlspecialchars($task['task'])) . '</td>
                        <td><span class="js-is-done">' . $task['is_done'] . '</span><br><span class="js-is-edit">' . $task['is_edit'] . '</span></td>
                        '. (($this->isAuth()) ? '<td>' . ($task['is_done'] == 'Не выполнено' ? '<button type="submit" class="btn btn-primary js-task-done" name="done">Выполнено</button>' : '') . '</td>' : '' ). '
                        '. (($this->isAuth()) ? '<td><button type="submit" class="btn btn-primary js-task-edit" name="edit">Сохранить</button></td>' : '' ). '
                    </tr>';
            }
        }
    
        die(json_encode(array(
            'status' => 'ok',
            'content' => $content,
        )));
    }

    /**
     * Отмечаем задачу выполненной
     */
    public function action_taskDone()
    {
        if(!$this->isAuth()) {            
            die(json_encode(array(
                'status' => 'errorAuth',
            )));
        }
        $id = $_POST['id'];
        $this->model->update('tasks', array(
            'is_done' => 1,
        ), array(
            'id' => $id
        ));
        
        die(json_encode(array(
            'status' => 'ok',
        )));
    }

    /**
     * Редактируем задачу
     */
    public function action_taskEdit()
    {
        if(!$this->isAuth()) {            
            die(json_encode(array(
                'status' => 'errorAuth',
            )));
        }
        $data = $_POST;
        // прверка полей
        $reqiered_fields = array('name', 'email', 'task');
        $empty_fields = array();
        foreach($reqiered_fields as $field)
        {
            if (empty($data[$field])) {
                $empty_fields[] = $field;                
            }
        }        
        if ($empty_fields) {
            die(json_encode(array(
                'status' => 'error',
                'message' => 'Заполнены не все поля',
                'fields' => $empty_fields,
            )));
        }
        // проверка email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            die(json_encode(array(
                'status' => 'error',
                'message' => 'Неверно указан email',
                'fields' => array('email'),
            )));       
        }
        
        $this->model->update('tasks', array(
            'name' => $data['name'],
            'email' => $data['email'],
            'task' => $data['task'],
            'is_edit' => 1,
        ), array(
            'id' => $data['id']
        ));
        
        die(json_encode(array(
            'status' => 'ok',
        )));
    }

    /**
     * Проверяем, что пользователь авторизован
     */
    public function isAuth()
    {
        return isset($_COOKIE['isAuth']);
    }
}