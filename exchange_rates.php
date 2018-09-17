<?php

function getbasename($filename)
{
	$url_arr  = explode('/', $filename);
	$ct       = count($url_arr);
	$name     = $url_arr[$ct - 1];
	$name_div = explode('.', $name);
	$ct_dot   = count($name_div);
	$img_type = $name_div[$ct_dot - 2];
	return strtoupper($img_type);
}

function html2txt($document)
{
	$search = array(
		'@<script[^>]*?>.*?</script>@si', // Strip out javascript 
		'@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags 
		'@<style[^>]*?>.*?</style>@siU', // Strip style tags properly 
		'@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments including CDATA 

	);
	$text = preg_replace($search, '', $document);
	$text = str_replace('  ', '', $text);
	$text = str_replace("\n", "", $text);
	$text = str_replace("\r", "", $text);
	$text = str_replace(" &nbsp;", "", $text);
	$text = str_replace("&nbsp;", "", $text);
	return $text;
}

function get_code($str)
{
	switch ($str) {
		case '3':
			return 'USD';
			break;
		case '4':
			return 'EUR';
			break;
		case '5':
			return 'CNY';
			break;
		case '6':
			return 'RUB';
			break;
		case '7':
			return 'JPY';
			break;
		case '8':
			return 'GBP';
			break;
		case '9':
			return 'CHF';
			break;
		// case '10':
		//   return 'USD';
		//   break;
		case '11':
			return 'KRW';
			break;
		case '12':
			return 'HKD';
			break;
		case '13':
			return 'AUD';
			break;
		case '14':
			return 'CAD';
			break;
		case '15':
			return 'SGD';
			break;
		case '16':
			return 'SEK';
			break;
		case '17':
			return 'AUG';
			break;
		default:
			break;
	}
}

$ratenames['USD'] = "АНУ доллар";
$ratenames['EUR'] = "Евро";
$ratenames['JPY'] = "Японы иен";
$ratenames['CHF'] = "Швейцар франк";
$ratenames['SEK'] = "Шведийн крон";
$ratenames['GBP'] = "Английн фунт";
$ratenames['BGN'] = "Болгарын лев";
$ratenames['HUF'] = "Унгарын форинт";
$ratenames['EGP'] = "Египетийн фунт";
$ratenames['INR'] = "Энэтхэгийн рупи";
$ratenames['HKD'] = "Хонгконг доллар";
$ratenames['RUB'] = "ОХУ-ын рубль";
$ratenames['KZT'] = "Казахстан тэнгэ";
$ratenames['CNY'] = "БНХАУ-ын юань";
$ratenames['KRW'] = "БНСУ-ын вон";
$ratenames['KPW'] = "БНАСАУ-ын вон";
$ratenames['CAD'] = "Канадын доллар";
$ratenames['AUD'] = "Австралийн доллар";
$ratenames['CZK'] = "Чех крон";
$ratenames['TWD'] = "Тайван доллар";
$ratenames['THB'] = "Тайланд бат";
$ratenames['IDR'] = "Индонезийн рупи";
$ratenames['MYR'] = "Малайзын ринггит";
$ratenames['SGD'] = "Сингапур доллар";
$ratenames['AED'] = "АНЭУ-ын дирхам";
$ratenames['KWD'] = "Кувейт динар";
$ratenames['NZD'] = "Шинэ Зеланд доллар";
$ratenames['DKK'] = "Данийн крон";
$ratenames['PLN'] = "Польшийн злот";
$ratenames['UAH'] = "Украйны гривн";
$ratenames['NOK'] = "Норвегийн крон";
$ratenames['NPR'] = "Непалын рупи";
$ratenames['ZAR'] = "Өмнөд Африкийн ранд";
$ratenames['TRY'] = "Туркийн лира";
$ratenames['XAU'] = "Алт /унцаар/";
$ratenames['XAG'] = "Мөнгө /унцаар/";
$ratenames['SDR'] = "Зээлжих тусгай эрх";
$ratenames['AUG'] = "Алт /унцаар/";
$ratenames['AGG'] = "Мөнгө /унцаар/";
$ratenames['POS'] = "";

//MONGOL BANK
$html = file_get_contents("http://monxansh.appspot.com/xansh.json");
$mongol_rates = json_decode($html);
foreach ($mongol_rates as $key => $value) {
	$code                                  = $value->code;
	$data['mongol'][$key]['name']          = $ratenames[$code];
	$data['mongol'][$key]['code']          = $code;
	$data['mongol'][$key]['alban']    = $value->rate_float;
}
$data['mongol'] = array_values($data['mongol']);
  
//GOLOMT BANK
$html = file_get_contents("https://www.golomtbank.com/mn/home/ratesForSites/rate.json");
$golomt_rates = json_decode($html)->rates;
foreach ($golomt_rates as $key => $value) {
$code                                  = get_code($value->rate_id9_37_);
	$data['golomt'][$key]['name']          = $ratenames[$code];
	$data['golomt'][$key]['code']          = $code;
	$data['golomt'][$key]['alban']    = $value->mongol_b5_37_;
	$data['golomt'][$key]['belenavah']     = $value->cash_buy2_37_;
	$data['golomt'][$key]['belenzarah']    = $value->cash_sel3_37_;
	$data['golomt'][$key]['belenbusavah']  = $value->non_cash6_37_;
	$data['golomt'][$key]['belenbuszarah'] = $value->non_cash7_37_;
}
$data['golomt'] = array_values($data['golomt']);

//KHAAN BANK
$html = file_get_contents("https://kbknew.khanbank.com/api/site/home?lang=mn&site=personal");
$khaan_rates = json_decode($html)->data->currencies->today;
foreach ($khaan_rates as $key => $value) {
	$code                                 = $value->code;
	$data['khaan'][$key]['name']          = $ratenames[$code];
	$data['khaan'][$key]['code']          = $code;
	$data['khaan'][$key]['alban']    = $value->alban;
	$data['khaan'][$key]['belenavah']     = $value->buy_cash;
	$data['khaan'][$key]['belenzarah']    = $value->sell_cash;
	$data['khaan'][$key]['belenbusavah']  = $value->buy;
	$data['khaan'][$key]['belenbuszarah'] = $value->sell;
}
$data['khaan'] = array_values($data['khaan']);

//TDB BANK
$html = file_get_contents("http://www.tdbm.mn/script.php?mod=rate&ln=mn");
preg_match_all('/<table.*?>(.*?)<\/table>/si', $html, $matches);
$tdmatch = $matches[0][0];
preg_match_all('/<tr.*?>(.*?)<\/tr>/si', $tdmatch, $matches);

foreach ($matches[0] as $key => $item) {
	if ($key > 2) {
		preg_match_all('/<td.*?>(.*?)<\/td>/si', $item, $matchestd);
		preg_match('/src="(.*)"/iS', $matchestd[0][0], $src);
		$code                                = getbasename($src[1]);
		$data['tdbm'][$key]['name']          = $ratenames[$code];
		$data['tdbm'][$key]['code']          = $code;
		$data['tdbm'][$key]['alban']    = html2txt($matchestd[0][1]);
		$data['tdbm'][$key]['belenavah']     = html2txt($matchestd[0][2]);
		$data['tdbm'][$key]['belenzarah']    = html2txt($matchestd[0][3]);
		$data['tdbm'][$key]['belenbusavah']  = html2txt($matchestd[0][4]);
		$data['tdbm'][$key]['belenbuszarah'] = html2txt($matchestd[0][5]);
	}
}
$data['tdbm'] = array_values($data['tdbm']);
  
//XAC BANK
$html = file_get_contents("https://www.xacbank.mn/calculator/rates");
preg_match_all('#<script(.*?)</script>#is', $html, $matches);
preg_match("/var weekRates = (.*)/", $matches[1][10], $reg);

$xac_rates = json_decode(rtrim($reg[1],';'));
foreach ($xac_rates as $key => $value) {
	$numItems = count($value);
	$i = 0;
	foreach($value as $v) {
		if(++$i === $numItems) {
			$code                                = $v->code;
			$data['xac'][$key]['name']          = $ratenames[$code];
			$data['xac'][$key]['code']          = $code;
			$data['xac'][$key]['alban']    = $v->alban;
			$data['xac'][$key]['belenavah']     = $v->buy_cash;
			$data['xac'][$key]['belenzarah']    = $v->sell_cash;
			$data['xac'][$key]['belenbusavah']  = $v->buy;
			$data['xac'][$key]['belenbuszarah'] = $v->sell;
		}
	}
}
$data['xac'] = array_values($data['xac']);

echo json_encode($data, JSON_NUMERIC_CHECK);
