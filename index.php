<?php

include_once("simple_html_dom.php");

	header("Content-type: text/html; charset=utf-8");
	echo "我的第一个爬虫";

	$url_arr = array('http://movie.douban.com');

/**
 * 从给定的URL获取html内容
 * @param  string $url url
 * @return string  
 */
function _getHtmlFromUrl($url)
{
	$handle = fopen($url, "r");
	if ($handle) {
		$content = stream_get_contents($handle,1024*1024);
		return $content;
	}else {
		return false;
	}
}

/**
 * 从html中筛选出链接
 * @param  string $web_content 
 * @return array   
 */
function _filterUrl($web_content)
{
	$reg_tag_a = '/<[a|A].*?href=[\'\"]{0,1}([^>\'\"\ ]*).*?>/';
	$result = preg_match_all($reg_tag_a, $web_content, $match_ruseult);
	if ($result) {
		return array_unique($match_ruseult[1]);
	}
}

/**
 * 修正相对路径
 * @param  string $base_url 
 * @param  array $url_list 
 * @return array           
 */
function _reviseUrl($base_url,$url_list)
{
	$url_info = parse_url($base_url);
	$base_url = $url_info["scheme"].'://';
	$base_url .= $url_info["host"];
	if (is_array($url_list)) {
		foreach ($url_list as $url_item) {
			if (preg_match('/^http/', $url_item)) {
				$result[] = $url_item;
			}else {
				$real_url = $base_url.'/'.$url_item;
				$result[] = $real_url;
			}
		}
		return $result;
	}else {
		return;
	}
}

/**
 * 爬虫
 * @param  string $url 
 * @return array  
 */
function spider($url)
{
	$html = _getHtmlFromUrl($url);
	if ($html) {
		$url_list = _reviseUrl($url,_filterUrl($html));
		if ($url_list) {
			return $url_list;
		}else {
			return;
		}
	}else {
		return;
	}
}

/**
 * 测试用主程序
 * @return  
 */
function main()
{
	var_dump(spider('http://movie.douban.com'));

	// $fp_puts = fopen('url.txt','ab'); // 记录URL列表
	// $fp_gets = fopen('url.txt','r'); // 保存URL列表
	// do {
	// 	$result_url_arr = spider($current_url);
	// 	if ($result_url_arr) {
	// 		foreach ($$result_url_arr as $url) {
	// 			fputs($fp_puts,$url.'\r\n');
	// 		}
	// 	}
	// }while ($current_url = fgets($fp_gets,1024));
}

main();
?>