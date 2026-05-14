$strHeaders = get_request_headers();
$arrTokenData = decodeToken($strHeaders,$secret_key);
if($strHeaders){

} else {
    $arr = fnInvalidToken();
    echo json_encode($arr);
}