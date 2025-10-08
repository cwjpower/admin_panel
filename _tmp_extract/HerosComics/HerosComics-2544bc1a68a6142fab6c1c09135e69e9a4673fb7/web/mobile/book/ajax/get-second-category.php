<?php
/*
 * 2016.08.15		softsyw
 * Desc : 하위 디렉토리 조회
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-term.php';

$code = 0;
$msg = '';

if ( empty($_POST['id']) ) {
	$code = 410;
	$msg = '카테고리를 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$id = $_POST['id'];
$lists = '<option value="">-선택-</option>';

$category = lps_get_term_by_id($id);

if (!empty($category)) {
	foreach ($category as $key => $val) {
		$tid = $val['term_id'];
		$tname = $val['name'];
		$lists .= '<option value="' . $tid . '">' . $tname . '</option>';
	}
}

$json = compact('code', 'msg', 'lists');
echo json_encode( $json );

?>