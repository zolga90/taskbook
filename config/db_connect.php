<?

	function connect()
    {		
		if ($connserv=mysql_connect(CONN_HOST,CONN_USER,CONN_PASS))
			{
				
				mysql_select_db(DB_NAME);// выбираем нужную базу
				
				/*--------------------устанавливаем кодировку подключения----------------------*/
				mysql_query ("SET CHARACTER SET 'UTF8'");
				mysql_query ("SET CHARACTER SET_CLIENT = 'UTF8'");
				mysql_query ("SET CHARACTER SET_RESULTS = 'UTF8'");
				mysql_query ("SET COLLATION_CONNECTION = 'utf8_general_ci'");
				mysql_query ("SET NAMES UTF8");
				return true;
			}
		else
			{
				/*----------- если не удалось подключиться, выводим ошибку подключения-----------*/
				if (DEVELOP) { $this->echo_error_message(mysql_error()); }
				return false;
			}
	}
?>