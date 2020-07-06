<?php

class Model_Main extends Model
{
	/**
     * Получаем все задачи
     */
	public function getAllTasks()
	{
        $this->db_connect();
        $data = $this->sql_get('SELECT * FROM tasks');
        return $data;
	}

	/**
     * Получаем задачи
     */
	public function getTasks($order='id', $sort='asc', $limit=3, $ofset=0)
	{
        $this->db_connect();
        $sql = 'SELECT id, name, email, task, IF(is_done = 1, "Выполнено", "Не выполнено") is_done, IF(is_edit = 1, "Отредактировано администратором", "") is_edit 
                FROM tasks 
                ORDER BY ' . $order . ' ' . $sort . '
                LIMIT ' . $ofset . ',' . $limit;
        $data = $this->sql_get($sql);
        // var_dump($data); die($sql);
        return $data;
	}
}
