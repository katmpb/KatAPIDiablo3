<?php

session_start();
header('Content-Type: text/html; charset=utf-8');

class Diablo
{
	protected $_doc;
	protected $_lang;

	//JĘZYK STRONY BLIZZ
	//SIDE'S LANGUAGE BLIZZ
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

	//Inicjalizacja mechanizmu przeszukiwania stron BLIZZ
	//Inicialization BLIZZ
	public function getDoc($number)
	{		
		if (null === $this->_doc) {			
			if ($this->_lang == 1)
			{
				$ch = curl_init('http://eu.battle.net/d3/pl/?page='.$number);
			}
			else
			{
				$ch = curl_init('http://eu.battle.net/d3/en/?page='.$number);
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
	
	//Funkcja pomocnicza
	//Help method
	public function getLatestPost($number)
	{
		$tmp = "";
		$xpath = new DOMXPath($this->getDoc($number));
		
		$q = "//div[@class='news-article first-child '][1]//div[@class='news-article-inner'][1]//h3[1]";
		$ret = $xpath->query($q);
		if (count($ret)) {
			foreach ($ret as $nd) {
				$tmp = "<table><tr><td>";
				$tmp = $tmp."<font color='yellow' style='font-size:20px'>".$nd->nodeValue."</font><BR/>";
				break;
			}
		}
		
		$q = "//div[@class='news-article first-child '][1]//div[@class='news-article-inner'][1]//div[@class='article-left'][1]/@style";
		$ret = $xpath->query($q);
		if (count($ret)) {
			foreach ($ret as $nd) {
				$tmp = $tmp.'<img style="float:left; margin:6px" width="90px" height="80px" src="http://'.substr($nd->nodeValue, strpos($nd->nodeValue, "url")+7, $nd->nodeValue.length-3).'"/>';
				break;
			}
		}

		$q = "//div[@class='news-article first-child '][1]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//p";
		$ret = $xpath->query($q);
		if (count($ret)) {
			foreach ($ret as $nd) {
				$tmp = $tmp.$nd->nodeValue;
			}
		}
		
		
		
		$rr = 0;
		$q = "//div[@class='news-article first-child '][1]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a";
		$ret = $xpath->query($q);
		foreach ($ret as $nd) {
			$rr++;
		}
		$rr=1;
		if (count($ret)) {
			$q = "//div[@class='news-article first-child '][1]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a[@class='more'][".$rr."]/@href";
			$ret = $xpath->query($q);
			if (count($ret)) {
				foreach ($ret as $nd) {
					if (substr($nd->nodeValue, 0, 4) == "blog")
					{
						if ($this->_lang == 1)
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/pl/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
						}
						else
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/en/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
						}
					}
					else
					{
						if ($this->_lang == 1)
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
						}
						else
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
						}
					}
					break;
				}
			}
		}
		
		
		
		if ($tmp == "")
		{
			return null;
		}
		$tmp = $tmp."</td></tr></table>";
		return ($tmp);
	}

	//Funkcja pomocnicza
	//Help method
	public function getLastPost($number)
	{
		$tmp = "";
		$xpath = new DOMXPath($this->getDoc($number));
		
		$q = "//div[@class='news-article  last-child'][1]//div[@class='news-article-inner'][1]//h3[1]";
		$ret = $xpath->query($q);
		if (count($ret)) {
			foreach ($ret as $nd) {
				$tmp = "<table><tr><td>";
				$tmp = $tmp."<font color='yellow' style='font-size:20px'>".$nd->nodeValue."</font><BR/>";
				break;
			}
		}
		
		$q = "//div[@class='news-article  last-child'][1]//div[@class='news-article-inner'][1]//div[@class='article-left'][1]/@style";
		$ret = $xpath->query($q);
		if (count($ret)) {
			foreach ($ret as $nd) {
				$tmp = $tmp.'<img style="float:left; margin:6px" width="90px" height="80px" src="http://'.substr($nd->nodeValue, strpos($nd->nodeValue, "url")+7, $nd->nodeValue.length-3).'"/>';
				break;
			}
		}

		$q = "//div[@class='news-article  last-child'][1]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//p";
		$ret = $xpath->query($q);

		if (count($ret)) {
			foreach ($ret as $nd) {
				$tmp = $tmp.$nd->nodeValue;
			}
		}
		
		
		
		$rr = 0;
		$q = "//div[@class='news-article  last-child'][1]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a";
		$ret = $xpath->query($q);
		foreach ($ret as $nd) {
			$rr++;			
		}				
		$rr=1;
		if (count($ret)) {					
			$q = "//div[@class='news-article  last-child'][1]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a[@class='more'][".$rr."]/@href";
			$ret = $xpath->query($q);
			if (count($ret)) {
				foreach ($ret as $nd) {					
					if (substr($nd->nodeValue, 0, 4) == "blog")
					{	
						if ($this->_lang == 1)
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/pl/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
						}
						else
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net/d3/en/'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
						}
					}
					else
					{
						if ($this->_lang == 1)
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">wi&#281;cej</font></a>';
						}
						else
						{
							$tmp = $tmp.'&nbsp;&nbsp;<a href="http://eu.battle.net'.$nd->nodeValue.'" target="_blank"><font color="#99fF00">more</font></a>';
						}
					}
					break;							
				}
			}		
		}
		
		
		
		if ($tmp == "")
		{
			return null;
		}
		$tmp = $tmp."</td></tr></table>";
		return ($tmp);
	}

	public function getPosts($number)
	{
		$tmp = "";
		$tmp = $tmp.$this->getLatestPost($number);

		$xpath = new DOMXPath($this->getDoc($number));
		$count = 1;
		$ret = 1;
		
		if (strlen($tmp) > 0)
		{
			$if_next = 1;
			
		
		while ($ret)
		{
			$tmp_ost = "";

			$error_side_blizz = 0;
			//BŁĄD STRONY BLIZZ
			//ERROR SIDE BLIZZ
			$q = "//div[@class='news-article  '][".($count+1)."]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//p";
			$ret = $xpath->query($q);
			if ($ret->length==0) {
				$error_side_blizz=1;
			};
			//KONIEC
			//END

			$tmp_ost = $tmp_ost."<table><tr><td>";
			$q = "//div[@class='news-article  '][".$count."]//div[@class='news-article-inner'][1]//h3[1]";			
			$ret = $xpath->query($q);
			if (count($ret)) {
				foreach ($ret as $nd) {
					$tmp_ost = $tmp_ost."<font color='yellow' style='font-size:20px'>".$nd->nodeValue."</font><BR/>";
					break;
				}
			}
			
			//TEMP
			//$tmp_ost = $tmp_ost."<table><tr><td>";
			//END TEMP
			$q = "//div[@class='news-article  '][".$count."]//div[@class='news-article-inner'][1]//div[@class='article-left'][1]/@style";
			//TEMP
			//$q = "//div[@class='news-article-inner'][".$count."]//div[@class='article-left'][1]/@style";
			//END TEMP
			$ret = $xpath->query($q);
			if (count($ret)) {
				foreach ($ret as $nd) {
					$tmp_ost = $tmp_ost.'<img style="float:left; margin:6px" width="90px" height="80px" src="http://'.substr($nd->nodeValue, strpos($nd->nodeValue, "url")+7, $nd->nodeValue.length-3).'"/>';
					break;
				}
			}

			$q = "//div[@class='news-article  '][".$count."]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//p";
			//TEMP
			//$q = "//div[@class='news-article-inner'][".$count."]//div[@class='article-right'][1]//div[@class='article-summary'][1]//p";
			//END TEMP
			$ret = $xpath->query($q);
			if (count($ret)) {
				foreach ($ret as $nd) {
					$tmp_ch = strip_tags($nd->nodeValue, "<span>");
					$tmp_ost = $tmp_ost.$tmp_ch;
				}
				if ($ret->length>0) {$ret=1; $if_next=0;} else {$ret=0;};
			}
			
			
			//TEMP
			////$q = "//div[@class='news-article-inner'][".$count."]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a/@href";
			//END TEMP
			$rr = 0;
			$q = "//div[@class='news-article  '][".$count."]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a";
			$ret2 = $xpath->query($q);
			foreach ($ret2 as $nd) {
				$rr++;
			}					
			$rr=1;
			if (count($ret2)) {				
				$q = "//div[@class='news-article  '][".$count."]//div[@class='news-article-inner'][1]//div[@class='article-right'][1]//div[@class='article-summary'][1]//a[@class='more'][".$rr."]/@href";
				$ret3 = $xpath->query($q);
				if (count($ret3)) {										
					foreach ($ret3 as $nd) {										
						if (substr($nd->nodeValue, 0, 4) == "blog")
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
						break;							
					}
				}		
			}
			
			
			
			$tmp_ost = $tmp_ost."</td></tr></table>";

			if ((($error_side_blizz == 1) && ($ret==1)) || ($if_next==1))
			{
				$tmp = $tmp.$this->getLastPost($number);
			}

			$count++;

			$tmp = $tmp.$tmp_ost;
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
		echo '<div style="display:none;"><embed src="http://www.katmpbsoft.pl/BLOG/diablo/Living Darfur.mp3" hidden="true"/><div>';
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


		//TEMP
		//$all_news = preg_replace('/[Š]/','&nbsp;',$all_news);
		//$all_news = preg_replace('/[†]/','&nbsp;',$all_news);					
		//]
		//END TEMP
		
		
		$all_news = preg_replace('/[^a-zA-Z0-9~!@#$%^&*()\-_+={[}\|\\:;"\'<,>.?\/ €‚ƒ„…‡ˆ‰‹ŒŽ‘’“”•–—˜™š›œ¡¢£¤¥¦§¨©«¬®¯°±²³´µ¶·¸¹º»¼½¾¿×÷ ąćęłńóśżźĄĆĘŁŃÓŚŻŹ]/',' ',$all_news);			
		echo $all_news;
	}
}
else
{
	echo '<HTML><HEAD><meta http-equiv="Content-Type" content="text/html" charset="utf-8"></HEAD><BODY>';	
	echo '<HTML><BODY onLoad="setTimeout(\'toTop()\', 500);">';	
	/*PL*/echo '<center><font color="red">'."Jeżeli już tu zajrzałeś to pewnie się zainteresowałeś - POZOSTAJEMY DO TWOJEJ DYSPOZYCJI !!!".'</font><center><BR/>';
	/*EN*/echo '<center><font color="green"><b>'."If you are already here, it's probably to get interested - remains at your disposal".'</b></font><center><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/>';
	echo '<div style="display:none;"><embed src="http://www.katmpbsoft.pl/BLOG/diablo/Living Darfur.mp3" hidden="true" showcontrols="0" showdisplay="0"/><div>';
	echo "<script>function toTop() {window.scrollTo(0,0);};</script>";	
	echo '</BODY></HTML>';
}