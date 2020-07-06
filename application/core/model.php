<?php

class Model
{    
    private $link;
    public function __construct()
    {
        $this->db_connect();
    }

    public function db_connect()
    {		
		if ($connserv=mysqli_connect(CONN_HOST,CONN_USER,CONN_PASS,DB_NAME))
			{
                $this->link = $connserv;
				mysqli_query ($this->link, "SET CHARACTER SET 'UTF8'");
				mysqli_query ($this->link, "SET CHARACTER SET_CLIENT = 'UTF8'");
				mysqli_query ($this->link, "SET CHARACTER SET_RESULTS = 'UTF8'");
				mysqli_query ($this->link, "SET COLLATION_CONNECTION = 'utf8_general_ci'");
				mysqli_query ($this->link, "SET NAMES UTF8");
				return true;
			}
		else
			{
				if (DEVELOP) { $this->echo_error_message(mysqli_error()); }
				return false;
			}
	}

    public function query($sql)
    {        
        return $query = mysqli_query($this->link, $sql);
    }

    public function sql_get($sql)
    {
		$result = $this->query($sql);
		if(mysqli_num_rows($result)>0) {
			$arrray = array();
			while($row = mysqli_fetch_assoc($result)){
				$arrray[] = $row;
			}
		}
		return $arrray;
	}

    public function insert($table, $data)
    {
        $keys  = join(',', array_keys($data));
        $vals  = join(',', $this->escape(array_values($data)));
        $sql   = 'INSERT INTO ' . $table . '(' . $keys . ') VALUES (' . $vals . ')';
        $res_q = $this->query($sql);
        return ($res_q ? mysqli_insert_id($this->link) : false);
    }

    public function update($table, $data, $expr = null)
    {
        $data = $this->sql_prepare($data, ',');
        if (is_null($expr)) {
            throw new Exception("защита от перезатирания всех данных");
        } elseif ($expr === false) {
            $sql = 'UPDATE `' . $table . '` SET ' . $data;
        } else {
            $expr = (is_array($expr)) ? $this->sql_prepare($expr, ' AND ') : '`id` = ' . $this->escape($expr);

            $sql = 'UPDATE `' . $table . '` SET ' . $data . ' WHERE (' . $expr . ')';
        }
        return $this->query($sql);
    }

    public function escape($str, $quot = '"')
    {
        if (is_array($str)) {
            foreach ($str as &$val) {
                $val = $this->escape($val);
            }
        }
        return (is_array($str)) ? $str : $quot . mysqli_real_escape_string($this->link, trim($str)) . $quot;
    }

    private function sql_prepare($data, $glue)
    {
        $vals = array();
        foreach ($data as $key => $val) {
            $vals[] = '`' . $key . '`=' . $this->escape($val);
        }
        return join($glue, $vals);
    }
}