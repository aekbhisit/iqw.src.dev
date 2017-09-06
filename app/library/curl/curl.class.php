<?php
/**
******************************************************
* @file curl.class.php
* @brief wArLeY_cURL: Use this class to get full webpage, send data, get data and all universe possibilities.
* @author Evert Ulises German Soto
* @version 1.0
* @date August 2012
*******************************************************/

class wArLeY_cURL{
	private $err_msg = "";
	private $opt_followlocation = false;
	private $options = array(
		"url" => "",
		"type" => "POST",
		"redirect" => "0",
		"timeout" => "0",
		"referer" => "",
		"return_transfer" => "0",
		"user_agent" => "",
		"header" => "0",
		"post" => "0",
		"post_fields" => "",
		"data" => "plain",
		"data_filename" => "example.html",
		"proxy" => "",
		"proxy_userpwd" => "",
		"proxy_type" => CURLPROXY_HTTP //CURLPROXY_SOCKS5
	);

	/** 
	* @brief Constructor, initialize class values.
	* @param array $options, load the required values for the user.
	*/
	public function __construct($options){
		if((string)$options['url']==""){
			$this->err_msg = "Error: the argument url is required.";
			return false;
		}

		foreach($this->options as $c=>$v){
			if((string)$options[$c]!="") $this->options[$c] = $options[$c];
			if(trim($c)=="redirect" && (integer)$this->options[$c]>0) $this->opt_followlocation = true;
		}
	}

	/** 
	* @brief Execute, this execute the curl function.
	* @return object, this object can be string with full request, or the filename with full request for work with this.
	*/
	public function Execute(){
		$data = $this->options['data'];
		$data_filename = $this->options['data_filename'];

		// Check if cURL installed
		if(!function_exists('curl_init')){
			$this->err_msg = "Error: Sorry cURL is not installed!";
			return false;
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->options['url']);
		((string)$this->options['referer']!=="") ? curl_setopt($ch, CURLOPT_REFERER, $this->options['referer']) : curl_setopt($ch, CURLOPT_REFERER, "http://www.google.com/?q=bitches+open+legs");
		((string)$this->options['user_agent']!=="") ? curl_setopt($ch, CURLOPT_USERAGENT, $this->options['user_agent']) : curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0");
		((string)$this->options['header']==="1") ? curl_setopt($ch, CURLOPT_HEADER, 1) : curl_setopt($ch, CURLOPT_HEADER, 0);
		((string)$this->options['return_transfer']==="0") ? curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0) : curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		((integer)$this->options['timeout']>0) ? curl_setopt($ch, CURLOPT_TIMEOUT, $this->options['timeout']) : curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		((string)$this->options['type']==="GET") ? curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET") : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

		$fp = "";
		switch($data){
			case "web":
			case "file":
				$fp = fopen($data_filename, "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				break;
		}

		if($this->opt_followlocation===true){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $this->options['redirect']);
		}else{
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		}

		if((string)$this->options['proxy']!==""){
			curl_setopt($ch, CURLOPT_PROXYTYPE, $this->options['proxy_type']);
			curl_setopt($ch, CURLOPT_PROXY, $this->options['proxy']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->options['proxy_userpwd']);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}

		if((string)$this->options['post']==="1" && gettype($this->options['post_fields'])==="array"){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->options['post_fields']);
		}else{
			curl_setopt($ch, CURLOPT_POST, 0);
		}

		$tmp_output = curl_exec($ch);
		$tmp_error = curl_error($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if($httpCode === 404){
			$this->err_msg = "Error: 404, Page Not Found.";
			return false;
		}elseif($httpCode !== 200){
			$this->err_msg = "Error: ". $httpCode .", operation denied.";
			return false;
		}

		if($tmp_error){
			$this->err_msg = "Error: ". $tmp_error;
			return false;
		}

		if($data!="plain"){ fclose($fp); return $data_filename; }else{ return $tmp_output; }
	}

	/** 
	* @brief getError, get the latest error ocurred in the class.
	* @return string, this is the latest error description.
	*/
	public function getError(){
		return trim($this->err_msg)!="" ? "<span style='display:block;color:#FF0000;background:#FFEDED;font-weight:bold;border:2px solid #FF0000;padding:2px 4px 2px 4px;margin-bottom:5px'>".$this->err_msg."</span><br />" : "";
	}
}
?>