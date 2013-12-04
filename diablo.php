<?php

error_reporting(0);
session_start();
header('Content-Type: text/html; charset=utf-8');

class Diablo
{
	protected $_doc;
	protected $_lang;

	//PL//JĘZYK STRONY BLIZZ
	//EN//Blizz site language
	protected $_languages = array(
			"PL" => "1",
			"EN" => "2"
	);
	
	function __construct($lang) {		
		if ($lang == $this->_languages["PL"])
		{
			$this->_lang = 1;
		}
		else
		{
			$this->_lang = 2;
		}			
	}	

	//PL//Inicjalizacja mechanizmu przeszukiwania stron BLIZZ
	//EN//Initialization BLIZZ
	public function getDoc($number)
	{		
		if (null === $this->_doc) {			
			
			if ($number == 1)
			{
				if ($this->_lang == 1)
				{
					//$ch = curl_init('http://eu.battle.net/d3/pl/?page='.$number);				
					$ch = curl_init('http://eu.battle.net/d3/pl/');
				}
				else
				{
					//$ch = curl_init('http://eu.battle.net/d3/en/?page='.$number);
					$ch = curl_init('http://eu.battle.net/d3/en/');
				}
			}
			else
			{
				if ($this->_lang == 1)
				{
					//$ch = curl_init('http://eu.battle.net/d3/pl/?page='.$number);
					$ch = curl_init('http://eu.battle.net/d3/pl/blog/infinite?page='.$number.'&articleType=blog');
				}
				else
				{
					//$ch = curl_init('http://eu.battle.net/d3/en/?page='.$number);
					$ch = curl_init('http://eu.battle.net/d3/en/blog/infinite?page='.$number.'&articleType=blog');
				}
			}
					
			
			curl_setopt_array($ch, array(
					CURLOPT_RETURNTRANSFER => true,
			));
			$content = curl_exec($ch);

			$this->_doc = new DOMDocument();
			@$this->_doc->loadHTML($content);
		}
		return $this->_doc;
	}
		
	public function getPosts($number)
	{				
		$xpath = new DOMXPath($this->getDoc($number));
		$count = 0;
		$ret = 1;
				
		if ($number == 1)
		{
		while ($ret)
		{
			$tmp_ost = "";
			
			$q = "//div[@class='blog-articles']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-content'][1]//span[@class='article-title'][1]";							
			$ret = $xpath->query($q);
			if ($ret->length==0) {
				$ret = 0;
				$tmp_ost = '';
			}
			else
			{				
				$tmp_ost = $tmp_ost."<table><tr><td>";
				if (count($ret)) {
					foreach ($ret as $nd) {
						$tmp_ost = $tmp_ost."<font color='yellow' style='font-size:20px'>".$nd->nodeValue."</font><BR/>";
						break;
					}
				}								
								
				$q = "//div[@class='blog-articles']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-image'][1]/@style";			
				$ret = $xpath->query($q);
				if (count($ret)) {
					foreach ($ret as $nd) {
						$tmp_ost = $tmp_ost.'<img style="float:left; margin:6px" width="120px" height="63px" src="http://'.substr($nd->nodeValue, strpos($nd->nodeValue, "url")+6, $nd->nodeValue.length-1).'"/>';											
						break;
					}
				}				

				$q = '';
				$q = "//div[@class='blog-articles']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-content'][1]//div[@itemprop='description']//p";				
				if (count($ret)) {				
					$q = "//div[@class='blog-articles']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-content'][1]//div[@itemprop='description'][1]";
				}
				$ret = $xpath->query($q);
				if (count($ret)) {
					foreach ($ret as $nd) {
						$tmp_ch = strip_tags($nd->nodeValue, "<span>");
						$tmp_ch = strip_tags($nd->nodeValue, "<p>");
						$tmp_ost = $tmp_ost.$tmp_ch;
					}					
				}
					
				$q = "//div[@class='blog-articles']//div[@class='article-wrapper'][".($count+1)."]//a[@itemprop='url']/@href";
				$ret = $xpath->query($q);
				if (count($ret)) {
					foreach ($ret as $nd) {				
							if (substr($nd->nodeValue, 0, 4) == "blog")			
							{
								if (strpos($nd->nodeValue, 'eu.battle.net')!==false)
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{										
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}
								}
								else
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/pl/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/en/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}																	
								}
							}
							else
							{
								if (strpos($nd->nodeValue, 'eu.battle.net')!==false)
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}
								}
								else
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}									
								}
							}
					}
				}
									
				$tmp_ost = $tmp_ost."</td></tr></table>";		
				$count++;
				$tmp = $tmp.$tmp_ost;
			}
		}
		}
		else
		{
			while ($ret)
			{
				$tmp_ost = "";
			
				$q = "//div[@class='article-page']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-content'][1]//span[@class='article-title'][1]";
				$ret = $xpath->query($q);
				if ($ret->length==0) {
					$ret = 0;
					$tmp_ost = '';
				}
				else
				{
					$tmp_ost = $tmp_ost."<table><tr><td>";
					if (count($ret)) {
						foreach ($ret as $nd) {
							$tmp_ost = $tmp_ost."<font color='yellow' style='font-size:20px'>".$nd->nodeValue."</font><BR/>";
							break;
						}
					}
			
					$q = "//div[@class='article-page']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-image'][1]/@style";
					$ret = $xpath->query($q);
					if (count($ret)) {
						foreach ($ret as $nd) {
							$tmp_ost = $tmp_ost.'<img style="float:left; margin:6px" width="110px" height="63px" src="http://'.substr($nd->nodeValue, strpos($nd->nodeValue, "url")+6, $nd->nodeValue.length-1).'"/>';
							break;
						}
					}
			
					$q = '';
					$q = "//div[@class='article-page']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-content'][1]//div[@itemprop='description']//p";										
					$ret = $xpath->query($q);
					if (count($ret)) {
						$q = "//div[@class='article-page']//div[@class='article-wrapper'][".($count+1)."]//div[@class='article-content'][1]//div[@itemprop='description'][1]";
					}
					$ret = $xpath->query($q);
					if (count($ret)) {
						foreach ($ret as $nd) {
							$tmp_ch = strip_tags($nd->nodeValue, "<span>");
							$tmp_ch = strip_tags($nd->nodeValue, "<p>");
							$tmp_ost = $tmp_ost.$tmp_ch;
						}
					}
			
					$q = "//div[@class='article-page']//div[@class='article-wrapper'][".($count+1)."]//a[@itemprop='url']/@href";
					$ret = $xpath->query($q);
					if (count($ret)) {
						foreach ($ret as $nd) {
							if (substr($nd->nodeValue, 0, 4) == "blog")			
							{
								if (strpos($nd->nodeValue, 'eu.battle.net')!==false)
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{										
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}
								}
								else
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/pl/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/en/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}																	
								}
							}
							else
							{
								if (strpos($nd->nodeValue, 'eu.battle.net')!==false)
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}
								}
								else
								{
									if ($this->_lang == 1)
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
									}
									else
									{
										$tmp_ost = $tmp_ost.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
									}									
								}
							}
						}
					}
			
					$tmp_ost = $tmp_ost."</td></tr></table>";
					$count++;
					$tmp = $tmp.$tmp_ost;
				}
			}
		}	
		
		
		if ($tmp == "")
		{
			return null;
		}		
		return ($tmp);
	}
}

if (
		isset($_SESSION['DIABLO'])
)
{
	if (!isset($_POST["page"]))
	{
		echo '<HTML><HEAD><meta http-equiv="Content-Type" content="text/html" charset="utf-8"></HEAD><BODY>';
		echo '<HTML><BODY onLoad="setTimeout(\'toTop()\', 500);">';	
		/*PL*/echo '<center><font color="red">'."Jeżeli już tu zajrzałeś to pewnie się zainteresowałeś - POZOSTAJEMY DO TWOJEJ DYSPOZYCJI !!!".'</font><center><BR/>';
		/*EN*/echo '<center><font color="green"><b>'."If you are already here, it's probably to get interested - remains at your disposal".'</b></font><center><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/>';
		echo '<embed src="http://www.katmpbsoft.pl/BLOG/diablo/Living Darfur.mp3" autostart="true" hidden="true" showcontrols="0" showdisplay="0"/>';
		echo "<script>function toTop() {window.scrollTo(0,0);};</script>";
		echo '</BODY></HTML>';
	}
	else
	{
		if ($_POST["language"] == 1)		
		{
			$diablo = new Diablo(1);
		}
		else
		{
			$diablo = new Diablo(2);
		}
				
		$all_news = $all_news.$diablo->getPosts($_POST["page"]);	
		$all_news = utf8_decode($all_news); //$all_news = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $all_news);
		
		/*PL AND EN*/$all_news = preg_replace('/[^a-zA-Z0-9~!@#$%^&*()\-_+={[}\|\\:;"\'<,>.?\/ €‚ƒ„…‡ˆ‰‹ŒŽ‘’“”•–—˜™š›œ¡¢£¤¥¦§¨©«¬®¯°±²³´µ¶·¸¹º»¼½¾¿×÷ ąćęłńóśżźĄĆĘŁŃÓŚŻŹ]/',' ',$all_news);
		//ONLY EN//$all_news = preg_replace('/[^a-zA-Z0-9~!@#$%^&*()\-_+={[}\|\\:;"\'<,>.?\/ €‚ƒ„…‡ˆ‰‹ŒŽ‘’“”•–—˜™š›œ¡¢£¤¥¦§¨©«¬®¯°±²³´µ¶·¸¹º»¼½¾¿×÷]/',' ',$all_news);
		echo $all_news;
	}
}
else
{
	echo '<HTML><HEAD><meta http-equiv="Content-Type" content="text/html" charset="utf-8"></HEAD><BODY>';	
	echo '<HTML><BODY onLoad="setTimeout(\'toTop()\', 500);">';	
	/*PL*/echo '<center><font color="red">'."Jeżeli już tu zajrzałeś to pewnie się zainteresowałeś - POZOSTAJEMY DO TWOJEJ DYSPOZYCJI !!!".'</font><center><BR/>';
	/*EN*/echo '<center><font color="green"><b>'."If you are already here, it's probably to get interested - remains at your disposal".'</b></font><center><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/>';
	echo '<embed src="http://www.katmpbsoft.pl/BLOG/diablo/Living Darfur.mp3" autostart="true" hidden="true" showcontrols="0" showdisplay="0"/>';
	echo "<script>function toTop() {window.scrollTo(0,0);};</script>";	
	echo '</BODY></HTML>';
}