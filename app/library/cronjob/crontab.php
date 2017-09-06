<?
	/**
	 *	This class is here to help you create cron jobs with ease.
	 *
	 *	Version 0.1
	 *	-------------------------
	 *	Creating cron jobs functions
	 *
	 *	To do in next version: make cron job manipulations
	 *
	 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
	 *	@package
	 */
	class crontab{
		
		var $minute=NULL;
		var $hour=NULL;
		var $day=NULL;
		var $month=NULL;
		var $dayofweek=NULL;
		var $command=NULL;
		var $directory=NULL;
		var $filename="crons";
		var $crontabPath=NULL;
		var $handle=NULL;
		
		/**
		 *	Constructor. Attempts to create directory for
		 *	holding cron jobs
		 *
		 *	@author	Richard Sumilang	 <richard@richard-sumilang.com>
		 *	@param	string	$dir		 Directory to hold cron job files
		 *	@param	string	$filename	 Filename to write to
		 *	@param	string	$crontabPath Path to cron program
		 *	@access	public
		 */
		function crontab($dir=NULL, $filename=NULL, $crontabPath=NULL){
			$result				=(!$dir) ? $this->setDirectory("~/my_crontabs") : $this->setDirectory($dir);
			if(!$result)
				exit('Directory error');
			$result				=(!$filename) ? $this->createCronFile("crons") : $this->createCronFile($filename);
			if(!$result)
				exit('File error');
			$this->pathToCrontab=($crontabPath) ? NULL : $crontabPath;
		}
		
		

		/**
		 *	Set date parameters
		 *
		 *	If any parameters are left NULL then they default to *
		 *
		 *	A hyphen (-) between integers specifies a range of integers. For
		 *	example, 1-4 means the integers 1, 2, 3, and 4.
		 *
		 *	A list of values separated by commas (,) specifies a list. For
		 *	example, 3, 4, 6, 8 indicates those four specific integers.
		 *
		 *	The forward slash (/) can be used to specify step values. The value
		 *	of an integer can be skipped within a range by following the range
		 *	with /<integer>. For example, 0-59/2 can be used to define every other
		 *	minute in the minute field. Step values can also be used with an asterisk.
		 *	For instance, the value * /3 (no space) can be used in the month field to run the
		 *	task every third month...
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@param	mixed	$min		Minute(s)... 0 to 59
		 *	@param	mixed	$hour		Hour(s)... 0 to 23
		 *	@param	mixed	$day		Day(s)... 1 to 31
		 *	@param	mixed	$month		Month(s)... 1 to 12 or short name
		 *	@param	mixed	$dayofweek	Day(s) of week... 0 to 7 or short name. 0 and 7 = sunday
		 *	$access	public
		 */
		function setDateParams($min=NULL, $hour=NULL, $day=NULL, $month=NULL, $dayofweek=NULL){
			
			if($min=="0")
				$this->minute=0;
			elseif($min)
				$this->minute=$min;
			else
				$this->minute="*";
			
			if($hour=="0")
				$this->hour=0;
			elseif($hour)
				$this->hour=$hour;
			else
				$this->hour="*";
			$this->month=($month) ? $month : "*";
			$this->day=($day) ? $day : "*";
			$this->dayofweek=($dayofweek) ? $dayofweek : "*";
			
		}
		
		/**
		 *	Set the directory path. Will check it if it exists then
		 *	try to open it. Also if it doesn't exist then it will try to
		 *	create it, makes it with mode 0700
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@param	string	$directory	Directory, relative or full path
		 *	@access	public
		 *	@return	boolean
		 */
		function setDirectory($directory){
			if(!$directory) return false;
			
			if(is_dir($directory)){
				if($dh=opendir($directory)){
					$this->directory=$directory;
					return true;
				}else
					return false;
			}else{
				if(mkdir($directory, 0700)){
					$this->directory=$directory;
					return true;
				}
			}
			return false;
		}
		
		
		/**
		 *	Create cron file
		 *
		 *	This will create a cron job file for you and set the filename
		 *	of this class to use it. Make sure you have already set the directory
		 *	path variable with the consructor. If the file exists and we can write
		 *	it then return true esle false. Also sets $handle with the resource handle
		 *	to the file
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@param	string	$filename	Name of file you want to create
		 *	@access	public
		 *	@return	boolean
		 */
		function createCronFile($filename=NULL){
			if(!$filename)
				return false;
			
			if(file_exists($this->directory.$filename)){
				if($handle=fopen($this->directory.$filename, 'a')){
					$this->handle=&$handle;
					$this->filename=$filename;
					return true;
				}else
					return false;
			}
			
			if(!$handle=fopen($this->directory.$filename, 'a'))
				return false;
			else{
				$this->handle=&$handle;
				$this->filename=$filename;
				return true;
			}
		}
		
		
		/**
		 *	Set command to execute
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@param	string	$command	Comand to set
		 *	@access	public
		 *	@return	string	$command
		 */
		function setCommand($command){
			if($command){
				$this->command=$command;
				return false;
			}else
				return false;
		}
		
		
		
		/**
		 *	Write cron command to file. Make sure you used createCronFile
		 *	before using this function of it will return false
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@access	public
		 *	@return	void
		 */
		function saveCronFile(){
			$command=$this->minute." ".$this->hour." ".$this->day." ".$this->month." ".$this->dayofweek." ".$this->command."\n";
			if(!fwrite($this->handle, $command))
				return true;
			else
				return false;
		}
		
		
		
		
		/**
		 *	Save cron in system
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@access	public
		 *	@return boolean				true if successful else false
		 */
		function addToCrontab(){
			
			if(!$this->filename)
				exit('No name specified for cron file');
						
			if(exec($crontabPath."crontab ".$this->directory.$this->filename))
				return true;
			else
				return false;
		}
		
		
		/**
		 *	Destroy file pointer
		 *
		 *	@author	Richard Sumilang	<richard@richard-sumilang.com>
		 *	@access	public
		 *	@return void
		 */
		function destroyFilePoint(){
			fclose($this->handle);
			return true;
		}
		
		
		
	}
		
?>