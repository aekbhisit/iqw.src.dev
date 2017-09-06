<?php
class website_search_dynamic{
	private $_errors;
	private $_labels;
	public 	$results;
	private $_search_term;
	private $_original_search_term;
	public 	$base_uri;
	private $_search_priority_change;
	public 	$searching; // are we searching or not
	private $_can_pdf;
	private $_search_depth;
	private $ch;
	private $_crawled_urls;
	public $current_host; // used in scraping to determine the current host we're searching
	public $current_path; // used in scraping to determine the current path on the host we're searching.
	public $valid_hosts;
	private $_content_hash; // used to remove duplicate content pages from the results.
	public $hit_urls;
	private $search_url_limit;
	private $show_update_progress;
	/**
	 * Constructor. Init's everything to the default values and finds the base url for search links.
	 *
	 */
	public function __construct(){
		$this->start_time = microtime(true);
		$this->_labels = array();
		$this->_errors = array();
		$this->results = array();
		$this->_can_pdf = true;
		$this->_search_depth = 0; 
		$this->valid_hosts = array();
		$this->_content_hash = array();
		$this->url_args = '';
		$this->hit_urls=0;
		$this->search_url_limit = 20; // for demo mode
		$this->show_update_progress = false; 
		if(isset($_REQUEST['iframe'])){
			$this->url_args = 'iframe=true&';
			$GLOBALS['_SEARCH_SHOW_BOX'] = false;
		}
		@exec('pdftotext -v',$pdftotext,$return);
		if($return == 1 || $return == 127){
			// no pdftotext support
			$this->_can_pdf = false;
		}
		$folder = trim(dirname($_SERVER['REQUEST_URI']),'/\\');
		$this->base_uri = 'http://'.$_SERVER['HTTP_HOST'] . '/' . (strlen($folder) ? $folder . '/' : '');
		$this->_search_priority_change = 0;
		
		// start curl up.
		// if no curl, hit an error.
		if(!function_exists('curl_init')){
			$this->error('CURL is not installed on this hosting account. Please contact your hosting provider and ask for it to be enabled.',true);
		}else{
			$this->ch = curl_init();
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	        @curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
	        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; PHP Website Search)");
	        curl_setopt($this->ch, CURLOPT_HEADER, 0);
		}
	}
	/**
	 * Preparing for muli-lingual version. good old sprintf and an array
	 *
	 * @param string $word
	 * @return string
	 */
	public function label($word){
		$argv = func_get_args();
		// see if the first one is a lang label
		if(isset($this->_labels[$word])){
			$argv[0] = $this->_labels[$word];
		}
		return call_user_func_array('sprintf',$argv); 
	}
	/**
	 * Record an error, fatal or just informative
	 *
	 * @param string $message
	 * @param bool $fatal
	 */
	public function error($message,$fatal=false){
		$this->_errors[] = array($message,$fatal);
	}
	/**
	 * Just checks if there's a fatal error or not
	 *
	 * @return unknown
	 */
	private function _fatal_error(){
		foreach($this->_errors as $error){
			if($error[1])return true;
		}
		return false;
	}
	/**
	 * Starts the ball rolling on a search for a particular keyword.
	 *
	 * @param string $search_term
	 */
	public function search($search_term){
		if($this->_fatal_error())return; // dont search on error.
		// pass this off to our recursive private methods that get a list of all files and search each one.
		//@set_time_limit(0);
		$this->searching = true;
		// do different types of searches, search for the entire string on it's own eg "testing a search"
		// then search for each string on it's own eg: "testing" "search" and join the results together.
		$this->_build_file_list();
		$this->_original_search_term = $search_term;
		$search_term = preg_replace('/\s+/',' ',$search_term);
		$splits = array($search_term=>true);
		$foo = explode(" ",$search_term);
		if(count($foo)>1){
			for($x=(count($foo)); $x > max(0,count($foo)-3); $x--){
				// when x is 1 we're adding all individual words.
				for($start_word=0;$start_word<count($foo);$start_word++){
					$this_foo = array_slice($foo,$start_word,count($foo));
					if(count($this_foo) < $x)continue;
					$this_search = '';
					$y=$x; 
					while($y>0 && $this_foo){
						$this_search .= array_shift($this_foo) . ' ';
						$y--;
					}
					$this_search = trim($this_search);
					if(strlen($this_search) >= $GLOBALS['_SEARCH_MIN_CHARS']){
						$splits [$this_search] = true;
					}
				}
			}
		}
		if(_SEARCH_DEBUG)print_r($splits);
		$last_length=false;
		foreach($splits as $search_term => $tf){
			if(!$last_length)$last_length = count(explode(" ",$last_length));
			$this->_search_term = $search_term;
			$this->_search_file_list(); // search list of files for results.
			$this->_custom_search(); // do any callback functions.
			if($last_length != count(explode(" ",$search_term))){
				// if we're searching on less words than previous search (ie: more generic search) reduce priority of these results a little.
				$last_length = count(explode(" ",$search_term));
				$this->_search_priority_change -= 0.1;
				$this->_search_depth++;
			}
		}
		$this->_highlight_results();
		$this->_sort_results();
	}
	
	public function update_index(){
		$_REQUEST['skip_cache'] = true;
		$this->show_update_progress = true;
		$this->_build_file_list();
	}
	/**
	 * Does the nasty work of hunting the specified websites for a list of html files.
	 * We build up 3 member arrays:
	 *  this->_html_files for all the plain text searchable URL's out there
	 *  this->_file_files for all the non-searchable files we match file names on
	 *  this->_pdf_files for all the pdf files we can convert into text for searching (only on compatible hosting accounts)
	 *
	 */
	private function _build_file_list(){
		// _SEARCH_HTML_WEBSITES
		$this->_html_folders = array();
		if(!$GLOBALS['_SEARCH_HTML_WEBSITES']){
			$GLOBALS['_SEARCH_HTML_WEBSITES'] = array(
				'http://'.$_SERVER['HTTP_HOST'].str_replace('\\','/',dirname($_SERVER['REQUEST_URI']).'/'),
			);
		}
		$cache_file = $GLOBALS['_SEARCH_CACHE_FOLDER']."search_data";
		$this->_crawled_urls = array();
		$this->_html_files = array();
		$this->_file_files = array();
		$this->_pdf_files = array();
		$cached = false;
		if( (!isset($_REQUEST['skip_cache']) && !$this->show_update_progress) && is_file($cache_file) && filemtime($cache_file)>strtotime("-".$GLOBALS['_SEARCH_CACHE_LENGTH']." days")){
			$foo = unserialize(file_get_contents($cache_file));
			if($foo){
				$this->_crawled_urls = $foo[0];
				$this->_html_files = $foo[1];
				$this->_file_files = $foo[2];
				$this->_pdf_files = $foo[3];
				unset($foo);
				$cached=true;
			}
		}
		if(!$cached){
			if(isset($GLOBALS['_SEARCH_HTML_WEBSITES']) && is_array($GLOBALS['_SEARCH_HTML_WEBSITES'])){
				foreach($GLOBALS['_SEARCH_HTML_WEBSITES'] as $website_url){
					// first build a list of valid hosts to scrape.
					if($this->valid_url($website_url)){
						$parts = parse_url($website_url);
						$this->valid_hosts[$parts['host']] = true;
					}
				}
				foreach($GLOBALS['_SEARCH_HTML_WEBSITES'] as $website_url){
					// do a curl request on this url, and recursively search
					// each link on the page (up to the specified depth) 
					// check url is valid first
					if($this->valid_url($website_url)){
						if(_SEARCH_DEBUG){
							echo '<ul class="search_debug">';
						}
						$this->_crawl_url($website_url);
						if(_SEARCH_DEBUG){
							echo '</ul>';
						}
					}
				}
			}
			// cache these
		
			file_put_contents($cache_file,serialize(array($this->_crawled_urls,$this->_html_files,$this->_file_files,$this->_pdf_files)));
		}
		
		
	}
	
	public function valid_url($url){
		$url = trim($url);
		$url = str_replace(' ','%20',$url);
		if(function_exists('filter_var')){
			return true ;// filter_var($url,FILTER_VALIDATE_URL);
		}else{
			// hacky fallback if filter_var doesn't exist. ie: older php5 installs.
			$pattern = "/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
			//return preg_match($pattern,$url);
			return true;
		}
	}
	
	private function _crawl_url($url,$depth=0,$current_host='',$current_path = ''){
		// quick hack to provide an "updating index" preview. 
		if($this->show_update_progress){
			echo '<script language="javascript">document.getElementById("update_progress").innerHTML = "';
			echo 'Scraping URL number '.count($this->_crawled_urls).' ';
			
			//if(_SEARCH_DEMO && isset($_SESSION['_search_demo_url']) && $_SESSION['_search_demo_url']){
			if(_SEARCH_DEMO){
				echo ' of ' . $this->search_url_limit . ' (DEMO MODE)';
			}
			echo '<br> <span>('.htmlspecialchars($url).')</span> <br>';
			echo '";</script>';
			@flush();
			@ob_flush();
		}
		if(_SEARCH_DEBUG){
			echo '<li>';
		}
		if(_SEARCH_DEBUG){
			echo " - try2: $url <br>";
		}
		$url = trim($url);
		$url = preg_replace('/#.*$/','',$url);
		if(!$url || (preg_match('#^\w+:#i',$url) && !preg_match('#^https?:#i',$url))){
			//$this->error('Link not valid: '.$url);
			if(_SEARCH_DEBUG){
				echo "Skipping URL '$url' <br>";
			}
			return false;
		}
		if(!preg_match('#^https?://#i',$url)){
			$url = 'http://' . 
			$current_host . 
			(($url[0] == '/') ? '' : $current_path) .
			$url;
		}
		if($depth > $GLOBALS['_SEARCH_HTML_DEPTH']){
			//$this->error('TOO DEEP: '.$url);
			if(_SEARCH_DEBUG){
				//echo "Skipping URL too deep ($depth) '$url' <br>";
			}
			return false;
		}
		$parts = parse_url($url);
		$valid_host = false;
		foreach($this->valid_hosts as $host => $tf){
			if(preg_match('#'.preg_quote($parts['host'],'#').'#i',$host)){
				$valid_host = true;
			}
		}
		if(!$valid_host){
			// stay on the same host.
			//$this->error('Host not valid: '.$url);
			if(_SEARCH_DEBUG){
				echo "Host not valid: ($depth) '$url' <br>";
			}
			return false;
		}
		
		if(!$this->valid_url($url)){
			if(_SEARCH_DEBUG){
				echo "URL not valid: ($depth) '$url' <br>";
			}
			$this->error('URL not valid: '.$url);
			return false;
		}
		// pull out the path for relative urls:
		$current_host = strtolower($parts['host']);
		$current_path = (isset($parts['path'])) ? str_replace('//','/',$parts['path']) : '/';
		if($current_path[strlen($current_path)-1] != '/'){
			$current_path = dirname($current_path) . '/';
			$current_path = str_replace('\\','/',$current_path);
		}
		// quick hack to do a realpath() on the url. ie: resolve the "/../" links.
		while(preg_match('#/[^/]+/\.\./?#',$url)){
			$url = preg_replace('#/[^/]+/\.\./?#', '/', $url);
		}
		$url = str_replace('/..','',$url); // could this be bad? hmm. nah.
		$url = preg_replace('#([^:])//#','$1/',$url); // could this be bad? hmm. nah.
		if(_SEARCH_DEBUG)echo "Crawling URL at depth $depth '$url' <br>";
		$url_lower = strtolower($url);
		if(!isset($this->_crawled_urls[$url_lower])){
			// check if the slash or non-slash exist
			if(isset($this->_crawled_urls[$url_lower.'/'])){
				$url_lower.='/';
			}else if(isset($this->_crawled_urls[rtrim($url_lower,'/')])){
				$url_lower = rtrim($url_lower,'/');
			}
		}
		// we skip this url if we've already crawled it, and all its children links.
		// if this url has child links, and it was crawled at the search depth, and we're at a lower depth than before, then we continue on crawling this page cos we may have missed some files. 
		if(
			// if we've crawled this url before, and it doesn't have children.
			(isset($this->_crawled_urls[$url_lower]) && is_array($this->_crawled_urls[$url_lower]) && !$this->_crawled_urls[$url_lower]['children'])
			||
			// if we've crawled this url before, and we crawled it at a limit less than the global limit (means we've crawled this urls chidlrenalready )
			(isset($this->_crawled_urls[$url_lower]) && is_array($this->_crawled_urls[$url_lower]) && $this->_crawled_urls[$url_lower]['depth'] < $GLOBALS['_SEARCH_HTML_DEPTH'])
			||
			// if we've crawled this url before, and it does have children, but we're already at the limit, so no point crawling the children
			(isset($this->_crawled_urls[$url_lower]) && is_array($this->_crawled_urls[$url_lower]) && $this->_crawled_urls[$url_lower]['children'] && $depth >= $GLOBALS['_SEARCH_HTML_DEPTH'])
			//(isset($this->_crawled_urls[$url_lower]) && is_array($this->_crawled_urls[$url_lower]) && $this->_crawled_urls[$url_lower]['depth'] < $GLOBALS['_SEARCH_HTML_DEPTH']))
			|| 
			// or its the oldschool cached method, we just skip the url.
			isset($this->_crawled_urls[$url_lower]) && !is_array($this->_crawled_urls[$url_lower]) 
		){
			if(_SEARCH_DEBUG)echo "Already crawled '$url_lower' <br>";
			return true;
		}
		
		$this->_crawled_urls[$url_lower] = array(
			'depth' => $depth,
			'children' => 0,
		);
		
		
		// if this url is an invalid one, just return.
		foreach($GLOBALS['_SEARCH_ALL_IGNORE'] as $regex){
			if(preg_match($regex,$url)){
				if(_SEARCH_DEBUG)echo "Ignoring URL '$url' <br>";
				//$this->error('IGNORE: '.$url);
				return false;
			}
		}
		// find out the extension / meta type of this page, to see if we either:
		//  - download this file as html for indexing
		//  - save a link to this file 
		//  - download the pdf for text processing.
		$file_regexes = $GLOBALS['_SEARCH_FILES_INCLUDE'];
		foreach($file_regexes as $regex){
			if(preg_match($regex,$url)){
				$this->_file_files[$url] = $url;
				// todo: HEAD this url to see if it exists? nah.
				// dont continue processing once we've found a file download link.
				// only continue processing if this is a pdf, and we can pdf.
				if($this->_can_pdf && preg_match('#\.pdf$#i',$url)){
					
				}else{
					// no pdf support or no pdf found this time.
					if(_SEARCH_DEBUG)echo "NO PDF SUPPORT FOR '$url' <br>";
					return true;
				}
			}
		}
		// we cache this url data to make futher calls quicker.
		$url_data = false;
		$cache_file = $GLOBALS['_SEARCH_CACHE_FOLDER'].md5($url);
		// see if we have a valid cache file for this url.
		//if((!isset($_REQUEST['skip_cache']) && !$this->show_update_progress) && is_file($cache_file) && (($this->hit_urls > 7) || filemtime($cache_file)>strtotime("-".$GLOBALS['_SEARCH_CACHE_LENGTH']." days"))){
		if((!isset($_REQUEST['skip_cache'])) && is_file($cache_file) && (($this->hit_urls > 7) || filemtime($cache_file)>strtotime("-".$GLOBALS['_SEARCH_CACHE_LENGTH']." days"))){
			// read that file out, json'd, and use it to search
			$url_data = unserialize(file_get_contents($cache_file));
			if($url_data['url'] != $url){
				// weird if this happens, but just to be safe.
				if(_SEARCH_DEBUG){
					echo "Cache file error: '".$url_data['url']."' doesnt match '".$url."' <br>\n";
				}
				$url_data = false;
			}
		}
		if(!$url_data){
			// create a new cache for this URL
			$url_data = array(
				'url' => $url,
			);
			//head call for content type:
			curl_setopt($this->ch, CURLOPT_HEADER, true); // header will be at output
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
			curl_setopt($this->ch, CURLOPT_URL, $url);
			curl_setopt($this->ch, CURLOPT_NOBODY, true);
			ob_start(); // hack for curl issue on head requests.
			$data = curl_exec($this->ch);
			$data2 = ob_get_clean();
			if(!$data && $data2)$data = $data2; // sometimes head request prints data instead of returning. odd. this fixes.
			//echo "<pre>$data</pre>";flush();
			if(!$data){
				if(_SEARCH_DEBUG)echo "Unable to get DATA FOR '$url' <br>";
				return false;
			}
			if(curl_errno($this->ch)){
			    $this->error('Curl error: ' . curl_error($this->ch));
			}
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET'); // reset
			curl_setopt($this->ch, CURLOPT_NOBODY, false);
			curl_setopt($this->ch, CURLOPT_HEADER, false); // reset
			if(_SEARCH_DEMO){
				curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
			}
			// find the last content type
			$final_url = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
			if(!$final_url){
				// for any redirects that hpapen.
				// todo - check we havent' been redirected to another website.
				$final_url = $url;
			}
			$url_data['final_url'] = $final_url;
			
			// now check content type.
			$url_data['content_type'] = false;
			if(preg_match_all('#content-type:\s?(.*)#i',$data,$matches)){
				end($matches[1]);
				$url_data['content_type'] = current($matches[1]);
			}
			
			// if we get to here then we can grab the page content, and index it!
			curl_setopt($this->ch, CURLOPT_URL, $url_data['final_url']);
			$url_data['data'] = curl_exec($this->ch);
			$content_hash = md5($url_data['data']);
			if(isset($this->_content_hash[$content_hash])){
				// we have crawled an identical page before.. skip this one from results.
				if(_SEARCH_DEBUG)echo "DUPLICATE CONTENT - IGNORING '" . $url_data['final_url'] . "' and '$url_lower' <br>";
				return false;
			}
			$this->_content_hash[$content_hash] = true;
			
			$this->hit_urls ++;
			
			$url_data['content_hash'] = $content_hash;
			$url_data['size'] = curl_getinfo($this->ch, CURLINFO_SIZE_DOWNLOAD);
			
			$url_data['html'] = false;
			$url_data['pdf'] = false;
			
			// different content type processing.
			
			foreach($GLOBALS['_SEARCH_HTML_INCLUDE'] as $html_include){
				// match teh content type againts indexable content types:
				if(preg_match($html_include,$url_data['content_type'])){
					$url_data['html'] = true;
				}
			}
			if($url_data['html']){
				if(preg_match('#<title>(.*)</title>#imsU',$url_data['data'],$matches)){
					$url_data['title'] = $matches[1];
				}
				preg_match_all('#<meta(.*)>#imsU',$url_data['data'],$matches);
				foreach($matches[1] as $meta_match){
					preg_match_all('#(\w+)=([\'"])([^\2]*)\2#msU',$meta_match,$meta_matches);
					$meta_sort = array();
					foreach($meta_matches[1] as $key=>$val){
						$meta_sort[strtolower($val)] = $meta_matches[3][$key];
					}
					if(isset($meta_sort['name']) && isset($meta_sort['content'])){
						$url_data[strtolower($meta_sort['name'])] = $meta_sort['content'];
					}
				}
				// alt tags? meh maybe. 
				//preg_match_all('#alt=([\'"])([^\1]+)\1#imsU',$url_data['data'],$alt_matches);
				
				// find all links on a page.
				if(preg_match_all('#<a[^>]+href=([\'"])(.*)\1#imsU',$url_data['data'],$matches)){
					$url_data['links'] = array_unique($matches[2]);
				}
				// remove custom are between <!-- hidesearch start --> and <!-- hidesearch end --> from html code
				$url_data['data'] = preg_replace('#<!--\s*hidesearch start\s*-->.*<!--\s*hidesearch end\s*-->#ismU','',$url_data['data']);
				// remove contents of javascript and css blocks, and tags, left with searchable text:
				$url_data['data'] = preg_replace('#<script[^>]*>.*</script>#ismU','',$url_data['data']);
				$url_data['data'] = preg_replace('#<style[^>]*>.*</style>#ismU','',$url_data['data']);
				$url_data['data'] = preg_replace("/\s+/",' ',strip_tags($url_data['data']));
			}
			
			if($this->_can_pdf && !$url_data['html']){
				foreach($GLOBALS['_SEARCH_PDF_INCLUDE'] as $html_include){
					// match the content type againts indexable PDF content types:
					if(preg_match($html_include,$url_data['content_type'])){
						$url_data['pdf'] = true;
						// convert that pdf content to text for indexing..
						$pdf_file = $GLOBALS['_SEARCH_CACHE_FOLDER'].'temppdf_'.md5($url).'.pdf';
						$pdf_text_file = $GLOBALS['_SEARCH_CACHE_FOLDER'].'temppdf_'.md5($url).'.txt';
						file_put_contents($pdf_file,$url_data['data']);
						$command = 'pdftotext -nopgbrk -layout "'.$pdf_file.'" '.$pdf_text_file;
						exec($command,$output,$return);
						if($return == 1 || $return == 127 || !is_file($pdf_text_file)){
							// failed to convert pdf - we hope... ? 
							$url_data['data'] = false;
						}else if(is_file($pdf_text_file)){
							// pdf converted to text, read it back into our cachable array..
							$url_data['data'] = file_get_contents($pdf_text_file);
						}
						@unlink($pdf_file);
						@unlink($pdf_text_file);
						break;
					}
				}
			}
			
			file_put_contents($cache_file,serialize($url_data));
		}else{
			// valid url data
			if(isset($url_data['content_hash'])){
				$this->_content_hash[$url_data['content_hash']] = true;
			}
		}
		
		//ob_start();print_r($url_data);echo "<pre>".htmlspecialchars(ob_get_clean())."</pre>";
		
		foreach($GLOBALS['_SEARCH_HTML_INCLUDE'] as $html_include){
			// match teh content type againts indexable content types:
			if(preg_match($html_include,$url_data['content_type'])){
				$this->_html_files[$url] = $url;
			}
		}
		if($this->_can_pdf){
			foreach($GLOBALS['_SEARCH_PDF_INCLUDE'] as $html_include){
				// match the content type againts indexable PDF content types:
				if(preg_match($html_include,$url_data['content_type'])){
					$this->_pdf_files[$url] = $url;
					break;
				}
			}
		}
		
		// find links if we're in a html file
		if(_SEARCH_DEBUG){
			echo " for url (".$url_data['html'].") '$url' links: ".count($url_data['links']). '<br>';
		}
		if(isset($url_data['links'])){
			$this->_crawled_urls[$url_lower]['children'] = count($url_data['links']);
		}
		if($url_data['html'] && isset($url_data['links']) && is_array($url_data['links'])){
			if($depth < $GLOBALS['_SEARCH_HTML_DEPTH']){
				foreach($url_data['links'] as $link){
					if(_SEARCH_DEBUG){
						echo '<ul class="search_debug">';
					}
					if(_SEARCH_DEMO){ // && isset($_SESSION['_search_demo_url']) && $_SESSION['_search_demo_url']){
						if(count($this->_crawled_urls) >= $this->search_url_limit){
							if(_SEARCH_DEBUG){
								echo 'Past limit';
							}
							return false;
						}
					}
					$this->_crawl_url($link,$depth+1,$current_host,$current_path);
					if(_SEARCH_DEBUG){
						echo '</ul>';
					}
				}
			}
		}
		if(_SEARCH_DEBUG){
			echo '</li>';
		}
		return true;
	}
	
	/**
	 * Simple function to turn a directory into an array.
	 * Just recursivelent iterates through available directories and appends them to an array.
	 *
	 * @param string $directory
	 * @param bool $recursive
	 * @param bool $include_folders
	 * @return array 
	 */
	private function _directory_to_array($directory, $recursive, $include_folders = false) {
		$array_items = array();
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, $this->_directory_to_array($directory. "/" . $file, $recursive));
						}
						if($include_folders){
							$file = $directory . "/" . $file;
							$array_items[] = preg_replace("/\/\//si", "/", $file);
						}
					} else {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}
	/**
	 * Now that the file list is generated, this actually does the grunt and iterates
	 *  over the file list to find any matching files.
	 *
	 */
	private function _search_file_list(){
		// search all the html files, grab the meta tags, alt tags, and the stripped html version. store in cache with an md5sum so we know if it changes.
		// saves re-processing the file each time if it doesn't change often.
		
		foreach($this->_html_files as $url){
			$cache_file = $GLOBALS['_SEARCH_CACHE_FOLDER'].md5($url);
			// see if we have a valid cache file for this url.
			if(is_file($cache_file)){
				$url_data = unserialize(file_get_contents($cache_file));
			}
			// now we have the file data, search to see if its a match.
			if($url_data){
				//print_r($url_data);
				$this->_search_file_data($url_data,$url);
				unset($url_data);
			}
		}
		$search_term = preg_quote($this->_search_term,'/');
		foreach($this->_file_files as $url){
			if(!$GLOBALS['_SEARCH_COMBINE'] && isset($this->results[$url]))continue;
			$this_result = false;
			if(preg_match('/'.$search_term.'/i',$url)){
				$this_result = array(
					"title" => basename($url),
					"importance" => 0.7, // 1 will push result to the top.
					"summary" => 'Downloadable File', // change for JPEG or diff file types maybe?
				);
			}
			if($this_result){
				$this->_build_result($this_result,array(),$url);
				unset($this_result);
			}
		}
		// this loop will only fire if $this->_can_pdf is true. ie: array will be empty
		foreach($this->_pdf_files as $url){
			$cache_file = $GLOBALS['_SEARCH_CACHE_FOLDER'].md5($url);
			// see if we have a valid cache file for this url.
			if(is_file($cache_file)){
				$url_data = unserialize(file_get_contents($cache_file));
			}
			// now we have the file data, search to see if its a match.
			if($url_data){
				$this->_search_file_data($url_data,$url);
				unset($url_data);
			}
		}
	}
	/**
	 * Used for searching array representation of a file using regex.
	 * Returns a "result" array with the info to display in a search result.
	 * 
	 * @param array $file_data
	 * @param string $url
	 * @return array
	 */
	private function _search_file_data($file_data,$url=false){
		$search_term = preg_quote($this->_search_term,'/');
		if(!$url)$url = $file_data['url'];
		$this_result = array(
			'importance' => 0,
		);
		
		// TODO - what if they match more than one of the below?
		// eg: a title, and a body. give more importance?? 
		// set this as a flag so we can easily change later on
		$combine_result_scores = $GLOBALS['_SEARCH_COMBINE'];
		
		// hacky loop so we can break on combine results. 
		// bad dave bad! oh well it was a quick adon at the end.
		do{
			//echo "CHecking $search_term against ".$file_data['title']."<br>";
			if(isset($file_data['title']) && preg_match('/'.$search_term.'/i',$file_data['title'])){
				// matches the page title, priority 1!
				$this_result["importance"] += 1; // 1 will push result to the top.
				$this_result["summary"] = (isset($file_data['description']))?$file_data['description']:''; // meta description if it exists.
				if(!$combine_result_scores)break;
			}
			if(isset($file_data['description'])){
				if(preg_match('/'.$search_term.'/i',$file_data['description'])){
					// matches the page title, priority 0.8!
					$this_result["importance"] += 0.8; // 1 will push result to the top.
					$this_result["summary"] = (isset($file_data['description']))?$file_data['description']:''; // meta description if it exists.
					if(!$combine_result_scores)break;
				}
			}
			if(preg_match('/'.$search_term.'/i',$url)){
				$this_result["importance"] += 0.5; // 1 will push result to the top.
				$this_result["summary"] = (isset($file_data['description']))?$file_data['description']:''; // meta description if it exists.
				if(!$combine_result_scores)break;
			}
			if(isset($file_data['keywords'])){
				if(preg_match('/'.$search_term.'/i',$file_data['keywords'])){
					$this_result["importance"] += 0.5; // 1 will push result to the top.
					$this_result["summary"] = (isset($file_data['keywords']))?$file_data['keywords']:''; // meta description if it exists.
					if(!$combine_result_scores)break;
				}	
			}
			if(isset($file_data['data'])){
				if(preg_match_all('/'.$search_term.'/i',$file_data['data'],$matches)){
					$this_result["importance"] += 0.4 + (count($matches[0]) * 0.2); 
					$this_result["summary"] = (isset($file_data['data']))?$file_data['data']:''; // meta description if it exists.
					if(!$combine_result_scores)break;
				}
			}
		}while(false);
		
		if($this_result && $this_result["importance"]){
			return $this->_build_result($this_result,$file_data,$url);
		}
		return false;
	}
	/**
	 * Builds up a result array for displaying results on the page.
	 * Includes things like the item title, search ranking, link etc..
	 *
	 * @param array $this_result - results passed in from external search result, we basically just sanatise and return this array.
	 * @param array $file_data - array representation of the item we are searching.
	 * @param string $url - front end url of the item we are searching
	 * @return array
	 */
	private function _build_result($this_result,$file_data,$url){
		
		if(_SEARCH_DEBUG)echo "Match on $url for ".$this->_search_term . "<br>";
		
		if(!isset($this_result['importance']))$this_result['importance'] = 0;
		if(!isset($this->results[$url])){
			// set the first result if it doesn't exist.
			$this->results[$url] = $this_result;
		}else if($GLOBALS['_SEARCH_COMBINE']){
			// really shouldn't be here if search_combine isn't set anyways. as only 1 result per url will be returned.but just to be safe.
			// already exists. increment importance
			$this->results[$url]['importance'] += $this_result['importance'];
		}
		// decrement the search priority on secondary searches.
		if(isset($this->results[$url]['importance']) && $this->_search_priority_change != 0 && !isset($this->results[$url]['importance_change'])){
			$this->results[$url]['importance'] += $this->_search_priority_change;
			$this->results[$url]['importance_change'] = true; // so we only decrement the importance once
		}
		if(!isset($file_data['title'])){
			$file_data['title'] = basename($url);
		}
		// this sanitisation should really be done once during the highlight step.
		// this is an area we could improve speed later:
		if(!isset($this->results[$url]['title']))$this->results[$url]['title'] = $file_data['title']; // main title to display, keyword wil be highlighted automatically.
		if(!isset($this->results[$url]['url']))$this->results[$url]['url'] = $url; // url that is used in link
		if(!isset($this->results[$url]['url_display']))$this->results[$url]['url_display'] = $url; // url that is displayed to the user
		if(!isset($this->results[$url]['depth']))$this->results[$url]['depth'] = $this->_search_depth; // url that is displayed to the user
		if(!isset($this->results[$url]['search_term'])){
			// keep a record of every matching search term on this result. so we can highlight them later.
			$this->results[$url]['search_term'] = array(); 
		}
		$this->results[$url]['search_term'] [] = $this->_search_term; 
		if((!isset($this->results[$url]['summary']) || !$this->results[$url]['summary'])&&$file_data['data']){
			$this->results[$url]['summary']=$file_data['data'];
		}
		$this->results[$url]['imp'] = $this->_search_priority_change;
		
		return true;
	}
	/**
	 * Calls any user defined functions to (eg:) search a mysql database for results to include with our own html results.
	 *
	 */
	private function _custom_search(){
		$callbacks = explode(',',$GLOBALS['_SEARCH_CALLBACKS']);
		foreach($callbacks as $callback){
			$callback = trim($callback);
			if($callback && function_exists($callback)){
				$user_results = call_user_func($callback,$this->_search_term,$this->_search_depth);
				if($user_results && is_array($user_results)){
					foreach($user_results as $key => &$result){
						if(!$GLOBALS['_SEARCH_COMBINE'] && isset($this->results[$key]))continue;
						$this->_build_result($result,array(),((isset($result['url']))?$result['url']:false));
					}
				}
			}
		}
	}
	/**
	 * Highlights any matching text in the search results, and
	 *  trim's it in a pretty fucking fancy way to show only a summary of the matching text.
	 * Yew
	 *
	 */
	private function _highlight_results(){
		foreach($this->results as $url => &$this_result){
			if(!$this_result['url_display'] && $this_result['url']){
				$this_result['url_display'] = $this_result['url'];
			}
			foreach($this_result['search_term'] as $search_term){
				$search_term = preg_quote($search_term,'/');
				// highlight the term within this search result.
				foreach(array('title','summary','url_display') as $highlight_item){
					if($this_result[$highlight_item] && preg_match('/'.$search_term.'/i',$this_result[$highlight_item])){
						if($highlight_item != 'url_display' && strlen($this_result[$highlight_item]) > $GLOBALS['_SEARCH_SUMMARY_LENGTH']){
							$boobs = ceil(($GLOBALS['_SEARCH_SUMMARY_LENGTH']-strlen($this->_search_term))/2);
							preg_match('/(.{0,'.$boobs.'})('.$search_term.')(.{0,'.$boobs.'})/i',$this_result[$highlight_item],$matches);
							// want to even out the strings a bit so if highlighted term is at end of string, put more characters infront.
							$before_limit = $after_limit = ($boobs - 2);
							if(strlen($matches[1])>=$before_limit && strlen($matches[3])>=$after_limit){
								// leave limit alone.
							}else if(strlen($matches[1])<$before_limit){
								$after_limit += $before_limit - strlen($matches[1]);
								$before_limit = strlen($matches[1]);
								preg_match('/(.{0,'.($before_limit+2).'})('.$search_term.')(.{0,'.($after_limit+2).'})/i',$this_result[$highlight_item],$matches);
							}else if(strlen($matches[3])<$after_limit){
								$before_limit += $after_limit - strlen($matches[3]);
								$after_limit = strlen($matches[3]);
								preg_match('/(.{0,'.($before_limit+2).'})('.$search_term.')(.{0,'.($after_limit+2).'})/i',$this_result[$highlight_item],$matches);
							}
							$this_result[$highlight_item] = (strlen($matches[1])>$before_limit) ? '...'.substr($matches[1],-$before_limit) : $matches[1];
							$this_result[$highlight_item] .= $matches[2];
							$this_result[$highlight_item] .= (strlen($matches[3])>$after_limit) ? substr($matches[3],0,$after_limit).'...' : $matches[3];
							
						}
						// bad now that we're in a loop. it's cutting the <span> part in half sometimes.. do highlight step after string cutting complete.
						//$this_result[$highlight_item] = preg_replace('/'.$search_term.'/i','<span style="background-color:#FFFA97">$0</span>',$this_result[$highlight_item]);
					}else if(strlen($this_result[$highlight_item]) > $GLOBALS['_SEARCH_SUMMARY_LENGTH']){
						$this_result[$highlight_item] = substr($this_result[$highlight_item],0,$GLOBALS['_SEARCH_SUMMARY_LENGTH']).'...';
					}
				}
			}
			// now that all the strings are the right length, we go through and lightlight
			foreach($this_result['search_term'] as $search_term){
				$search_term = preg_quote($search_term,'/');
				// highlight the term within this search result.
				foreach(array('title','summary','url_display') as $highlight_item){
					$this_result[$highlight_item] = preg_replace('/'.$search_term.'/i','<span style="background-color:#FFFA97">$0</span>',$this_result[$highlight_item]);
				}
			}
		}
	}
	/**
	 * Sorts the results based on 'importance'
	 * Also highlights any matching text in the search results, and
	 *  trim's it in a pretty fucking fancy way to show only a summary of the matching text.
	 * Yew
	 *
	 */
	private function _sort_results(){
		uasort($this->results,array($this,'_sort_importance'));
	}
	/**
	 * Just a uasort function for above.
	 */
	private function _sort_importance($a,$b){
		return $a['importance']<=$b['importance'];
	}
	
	public function  reBuildFile(){
		$this->_build_file_list();
	}
	
	public function resultToArray(){
			if($this->searching && !$this->_fatal_error()){
				// just cos i dont like calling count() all the time
				$number_results = count($this->results);
				// work out total number of pages.
				$total_pages 	= ceil($number_results / $GLOBALS['_SEARCH_PER_PAGE']);
				// work out what page we're looking at, and stop people making it < > the page limit
				$current_page 	= (isset($_REQUEST['pg'])&&(int)$_REQUEST['pg'])?(int)$_REQUEST['pg']:0; // start at 0 
				if($current_page < 0 || $current_page >= $total_pages)$current_page = 0;
				// some more uber awesomeness to find out the results start and end count:
				$results_start 	= ($current_page * $GLOBALS['_SEARCH_PER_PAGE']) + 1;
				$results_end 	= min(($current_page * $GLOBALS['_SEARCH_PER_PAGE']) + $GLOBALS['_SEARCH_PER_PAGE'],$number_results);
				// slice the results up to only have the ones from thsi page.
				$result['number_results']  =$number_results  ;
				$result['current_page']  =$current_page  ;
				$result['total_pages']  =$total_pages  ;
				$result['search_start']  =$results_start  ;
				$result['search_end']  =$results_end  ;
				$result['search_result']  = array_slice($this->results,$current_page*$GLOBALS['_SEARCH_PER_PAGE'],$GLOBALS['_SEARCH_PER_PAGE'],true);
			}else{
				$result['search_result'] = false;
			}
			return $result;
	}
	
	public function resultToJSON(){
			if($this->searching && !$this->_fatal_error()){
				// just cos i dont like calling count() all the time
				$number_results = count($this->results);
				// work out total number of pages.
				$total_pages 	= ceil($number_results / $GLOBALS['_SEARCH_PER_PAGE']);
				// work out what page we're looking at, and stop people making it < > the page limit
				$current_page 	= (isset($_REQUEST['pg'])&&(int)$_REQUEST['pg'])?(int)$_REQUEST['pg']:0; // start at 0 
				if($current_page < 0 || $current_page >= $total_pages)$current_page = 0;
				// some more uber awesomeness to find out the results start and end count:
				$results_start 	= ($current_page * $GLOBALS['_SEARCH_PER_PAGE']) + 1;
				$results_end 	= min(($current_page * $GLOBALS['_SEARCH_PER_PAGE']) + $GLOBALS['_SEARCH_PER_PAGE'],$number_results);
				// slice the results up to only have the ones from thsi page.
				$result['number_results']  =$number_results  ;
				$result['current_page']  =$current_page  ;
				$result['total_pages']  =$total_pages  ;
				$result['search_start']  =$results_start  ;
				$result['search_end']  =$results_end  ;
				$result['search_result']  = array_slice($this->results,$current_page*$GLOBALS['_SEARCH_PER_PAGE'],$GLOBALS['_SEARCH_PER_PAGE'],true);
			}else{
				$result['search_result'] = false;
			}
			return json_encode($result);
	}
	
		public function render(){
		// render results to the screen.
		if(is_file($GLOBALS['_SEARCH_FILE_HEADER'])){
			include($GLOBALS['_SEARCH_FILE_HEADER']);
		}
		?>
		<div class="phpsearch_wrapper">
		<?php
		if($GLOBALS['_SEARCH_SHOW_BOX']){
			if(_SEARCH_DEMO){
				?>
				<div style="float:right; font-size:0.8em;"><?php
				if(isset($_SESSION['_search_demo_url']) && $_SESSION['_search_demo_url']){ ?>
				<blink>DEMO</blink> Searching <?php echo htmlspecialchars($_SESSION['_search_demo_url']); ?>. <a href="?leave_demo">Leave Demo</a> <br>
				<?php } ?><a href="http://codecanyon.net/item/php-search-engine/89499?ref=dtbaker" target="_blank">Click here to get dynamic search on your site</a>
				<br>
				<a href="phpsearch_files/admin.php">View administration page</a>
				</div>
				<?php
			}
			?>
			<form action="?<?php echo $this->url_args;?>" method="get">
			<input type="text" name="search" class="phpsearch_input" size="35" autocomplete="off" value="<?php echo (isset($_REQUEST['search']))?htmlspecialchars($_REQUEST['search']):'';?>"> 
			<input type="submit" name="go" value="<?php echo $this->label('Search Site');?>" class="phpsearch_button">	
			</form>
			<?php
		}
		if(count($this->_errors)){
			// render errors first:
			echo '<div class="phpsearch_error">';
			echo '<ul>';
			foreach($this->_errors as $error){
				echo '<li>' . $error[0] . '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
		if($this->searching && !$this->_fatal_error()){
			// just cos i dont like calling count() all the time
			$number_results = count($this->results);
			// work out total number of pages.
			$total_pages 	= ceil($number_results / $GLOBALS['_SEARCH_PER_PAGE']);
			// work out what page we're looking at, and stop people making it < > the page limit
			$current_page 	= (isset($_REQUEST['pg'])&&(int)$_REQUEST['pg'])?(int)$_REQUEST['pg']:0; // start at 0 
			if($current_page < 0 || $current_page >= $total_pages)$current_page = 0;
			// some more uber awesomeness to find out the results start and end count:
			$results_start 	= ($current_page * $GLOBALS['_SEARCH_PER_PAGE']) + 1;
			$results_end 	= min(($current_page * $GLOBALS['_SEARCH_PER_PAGE']) + $GLOBALS['_SEARCH_PER_PAGE'],$number_results);
			// slice the results up to only have the ones from thsi page.
			$page_results = array_slice($this->results,$current_page*$GLOBALS['_SEARCH_PER_PAGE'],$GLOBALS['_SEARCH_PER_PAGE'],true);
			?>
			<div class="phpsearch_header">
				<?php if($number_results){ 
					echo $this->label('Results <b>%s</b> to <b>%s</b> of <b>%s</b> for search <b>%s</b>.',$results_start,$results_end,$number_results,htmlspecialchars($this->_original_search_term));
					echo ' ';
					echo $this->label('(<b>%01.2f</b> seconds)',max(microtime(true)-$this->start_time,0.01));
				} ?>
			</div>
			<div class="phpsearch_results">
				<?php if(!count($page_results)){ 
					echo $this->label('Your search for <b>%s</b> did not match any documents.',htmlspecialchars($this->_original_search_term));
				}else{ 
					?>
					<ul class="phpsearch_list">
					<?php
					foreach($page_results as $key => $result){
						?>
					
						<li>
							<div class="phpsearch_list_item">
								<h3><a href="<?php echo $result['url'];?>"<?php if(!$GLOBALS['_SEARCH_SHOW_BOX'])echo ' target="_blank"';?>><?php echo $result['title'];?></a></h3>
								<p><?php echo $result['summary'];?></p>
								<span class="phpsearch_importance"><?php echo $this->label('Importance: %s',$result['importance']);?></span>
								<div class="phpsearch_link"><a href="<?php echo $result['url'];?>"<?php if(!$GLOBALS['_SEARCH_SHOW_BOX'])echo ' target="_blank"';?>><?php echo $result['url_display'];?></a></div>
							</div>
						</li>
						
					<?php } ?>
					</ul>
				<?php } ?>
			</div>
			<div class="phpsearch_pagination">
				<?php if($current_page > 0){ ?>
				<a href="?<?php echo $this->url_args;?>search=<?php echo urlencode($this->_original_search_term);?>&pg=<?php echo $current_page-1;?>" class="phpsearch_bn">&larr; <?php echo $this->label('Previous');?></a>
				<?php } ?>
				<span></span>
				<?php for($x=0;$x<$total_pages;$x++){ ?>
				<a href="?<?php echo $this->url_args;?>search=<?php echo urlencode($this->_original_search_term);?>&pg=<?php echo $x;?>" class="phpsearch_pg"><?php echo $x+1;?></a>
				<span></span>
				<?php } ?>
				<?php if($current_page < ($total_pages-1)){ ?>
				<a href="?<?php echo $this->url_args;?>search=<?php echo urlencode($this->_original_search_term);?>&pg=<?php echo $current_page+1;?>" class="phpsearch_bn"><?php echo $this->label('Next');?> &rarr;</a>
				<?php } ?>
			</div>
			<?php
		}
		?>
		</div>
		<?php
		if(is_file($GLOBALS['_SEARCH_FILE_FOOTER'])){
			include($GLOBALS['_SEARCH_FILE_FOOTER']);
		}
	}
	
} 
?>