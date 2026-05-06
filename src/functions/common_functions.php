<?php
function getSidebarMenu($userRoleId){
    $sidebarModule = "SELECT meta_id AS module_id, end_points, sidebar_title, sidebar_icon, parent_id, params FROM meta_details WHERE is_module = 1 AND deleted_status = 'N' ORDER BY sidebar_order ASC";
    return sqlSelect($sidebarModule);
}
function displayStatus(){
    return [
        ["id" => "Y", "label" => "Active"],
        ["id" => "N", "label" => "Inactive"],
    ];
}

function itemSectionTypes(){
    return [
        ["id" => "default", "label" => "Default"],
        ["id" => "blog", "label" => "Blog"]
    ];
}
function getBlogCategory($item_type){
    $sqlQuery = "SELECT item_section_id as id, section_title as label FROM item_section WHERE item_type = '$item_type' AND deleted_status = 'N' ORDER BY item_section_id ASC";
    $arrResults = sqlSelect($sqlQuery);
    return $arrResults;
}

function getRoleById($role_id){
    $sqlQuery = "SELECT * FROM role WHERE role_id = $role_id AND deleted_status = 'N'";
    $arrResults = sqlSelect($sqlQuery);
    return $arrResults;
}

function getRoleMetaDetails() {
    $sqlQuery = "SELECT * FROM meta_details WHERE is_module = 1 ORDER BY meta_id ASC";
    $arrResults = sqlSelect($sqlQuery);
    return $arrResults;
}

function getRoleAccess($role_id) {
    $sqlQuery = "SELECT * FROM role_access WHERE role_id = $role_id ORDER BY role_access_id ASC";
    $arrResults = sqlSelect($sqlQuery);
    return $arrResults;
}
function getAllRoles() {
    $sqlQuery = "SELECT role_id as id, role_title as label FROM role WHERE deleted_status = 'N' ORDER BY role_id ASC";
    $arrResults = sqlSelect($sqlQuery);
    return $arrResults;
}

function sendMail($to, $subject, $body, $altBody = '')
{
    global $email_host, $smtp_auth, $email_username, $email_password,$smtp_secure, $email_port, $mail_from, $mail_from_name;
    require_once 'src/vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $email_host;
        $mail->SMTPAuth   = $smtp_auth;
        $mail->Username   = $email_username;
        $mail->Password   = $email_password;
        $mail->SMTPSecure = $smtp_secure;
        $mail->Port       = $email_port;
        $mail->setFrom($mail_from, $mail_from_name);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);
        $mail->send();
        return [
            "success" => true,
            "message" => "Email sent successfully"
        ];
    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => $mail->ErrorInfo
        ];
    }
}
function getMetaDetails(){
    global $site_url,$pdo;
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $currentUrl .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = parse_url($site_url, PHP_URL_PATH);
    $baseUrl = str_replace($site_url,"",$currentUrl);
    $final_string = "/".$baseUrl;

    $metaTitle = "Default Meta Title";
    $metaDescription = "Default Meta Description";
    $pageTitle = "Default Page Title";

    $sqlQuery = "SELECT * FROM `meta_details` WHERE `end_points` = '$final_string'";
    $arrMetaDetails = sqlSelect($sqlQuery, PDO::FETCH_ASSOC);
    if($arrMetaDetails) {
        $siteTitle = $arrMetaDetails[0]['meta_title'];
        $metaDescription = $arrMetaDetails[0]['meta_description'];
        $pageTitle = $arrMetaDetails[0]['page_title'];
    } else {
        $sqlInsert = "INSERT INTO `meta_details` (`end_points`, `meta_title`, `meta_description`, `page_title`) VALUES ('$final_string', '$metaTitle', '$metaDescription', '$pageTitle')";
        $stmt = $pdo->prepare($sqlInsert);
        $stmt->execute();
        $itemId = $pdo->lastInsertId();
    }
    return [
        "metaTitle" => $siteTitle,
        "metaDescription" => $metaDescription,
        "pageTitle" => $pageTitle
    ];
}
?>