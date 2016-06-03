<?php
namespace herysepty\Libraries;

class FMeasure 
{
	private $tweets  = array();
	public function fMeasure()
	{
		$tweets[0] = "tweet 1";
		$tweets[1] = "tweet 2";
		$tweets[3] = "tweet 3";


		print_r($tweets);
	}
}