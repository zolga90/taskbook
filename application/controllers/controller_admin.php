<?php

class Controller_Admin extends Controller
{
	function __construct()
	{
		$this->model = new Model_Admin();
		$this->view = new View();
	}

	public function action_index()
	{
        if (!$this->isAuth()){
            $this->view->generate('login_view', $data);
        } else {
            header('Location: /');
        }
	}

    /**
     * Логинимся
     */
    public function action_tryLogin()
    {
        $data = $_POST;
        $reqiered_fields = array('name', 'pass');
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
        
        $set = $this->model->tryLogin($data);
        if (!$set) {
            die(json_encode(array(
                'status' => 'error',
                'message' => 'Неправильная пара логин-пароль',
            )));
        }

        setcookie("isAuth", $data['name'], time()+3600, '/');
        die(json_encode(array(
            'status' => 'ok',
        )));
    }
    
    /**
     * Проверяем, авторизован ли пользователь
     */
    public function isAuth()
    {
        return isset($_COOKIE['isAuth']);
    }
    
    /**
     * Разлогиниваемся
     */
    public function action_logout()
    {
        setcookie("isAuth", '', time()+3600, '/');
        header('Location: /');
    }
}
