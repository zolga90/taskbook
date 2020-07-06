<?php

class View
{
	function generate($content, $data = null)
	{		
		require_once 'application/views/'.$content.'.php';
	}
}
