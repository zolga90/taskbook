<?php

class Model_Admin extends Model
{
	/**
     * Проверяем, есть ли такой пользователь
     */
	public function tryLogin($data)
	{
        $this->db_connect();
        $sql = 'SELECT * FROM admin WHERE name = "' . $data['name'] . '" and pass = "' . md5($data['pass']) . '"';
        $set = $this->sql_get($sql);
        return $set;
	}
}
