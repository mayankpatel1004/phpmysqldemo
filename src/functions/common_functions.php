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

function emailHeader($title = 'Notification')
{
    global $site_url,$pdo;
    $website_name = "";
    $sqlQuery = "SELECT config_value FROM `site_config` WHERE `config_name` IN ('FRONT_APPLICATION_TITLE')";
    $arrConfigDetails = sqlSelect($sqlQuery, PDO::FETCH_ASSOC);
    if (!empty($arrConfigDetails)) {
        $website_name = $arrConfigDetails[0]['config_value'];
    }

    return '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>'.$title.'</title>
    </head>
    <body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="margin-top:20px;">
                        
                        <!-- Header -->
                        <tr>
                            <td align="center" bgcolor="#007bff" style="padding:20px;">
                                ' . $website_name . '
                            </td>
                        </tr>

                        <!-- Content -->
                        <tr>
                            <td style="padding:30px;">
    ';
}

function emailFooter()
{
    global $site_url,$pdo;
    $website_name = "";
    $company_address = "";
    $company_contact = "";
    $company_email = "";
    $company_city = "";
    $company_state = "";
    $company_country = "";
    $company_zipcode = "";
    $sqlQuery = "SELECT config_value FROM `site_config` WHERE `config_name` IN ('FRONT_APPLICATION_TITLE','COMPANY_NAME','COMPANY_ADDRESS1','COMPANY_ADDRESS2','COMPANY_CITY','COMPANY_STATE','COMPANY_COUNTRY','COMPANY_ZIPCODE','COMPANY_CONTACT_NUMBER','COMPANY_EMAIL')";
    $arrConfigDetails = sqlSelect($sqlQuery, PDO::FETCH_ASSOC);
    if (!empty($arrConfigDetails)) {
        $website_name = $arrConfigDetails[0]['config_value'];
        $company_address = $arrConfigDetails[2]['config_value']." ".$arrConfigDetails[3]['config_value'];
        $company_city = $arrConfigDetails[4]['config_value'];
        $company_state = $arrConfigDetails[5]['config_value'];
        $company_country = $arrConfigDetails[6]['config_value'];
        $company_zipcode = $arrConfigDetails[7]['config_value'];
        $company_contact = $arrConfigDetails[8]['config_value'];
        $company_email = $arrConfigDetails[9]['config_value'];
    }
    return '
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f8f9fa"
                                style="padding:20px;text-align:center;color:#666;font-size:12px;">
                                
                                <strong>'.$website_name.'</strong><br>
                                '.$company_address.'<br>
                                Email: '.$company_email.'<br>
                                Phone: '.$company_contact.'<br><br>

                                &copy; '.date('Y').' '.$website_name.' . All Rights Reserved.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
}

function generateEmailConetent($title, $content)
{
    return emailHeader($title) . $content . emailFooter();
}
?>