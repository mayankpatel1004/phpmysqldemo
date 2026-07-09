<?php
if(isset($_GET['action']) && $_GET['action'] == 'changepassword'){
    $strHeaders = get_request_headers();
    $arrTokenData = decodeToken($strHeaders,$secret_key);
    if($strHeaders && (isset($arrTokenData) && $arrTokenData['status'] == 1)){
        $password = md5($_POST['password']);
        $user_id = $_POST['user_id'];
        $user_email = $_POST['user_email'];
        $sql = "UPDATE users SET `user_password` = :password WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':password'   => $password,
            ':user_id' => $user_id
        ]);
        $arr = array(
            'success' => 1,
            'message' => "Data successfully updated"
        );

        $to = $user_email;
        $subject = 'Your password changed.';
        $current_date = date('Y-m-d H:i:s');
        $body = "<p style=\"margin:5px 10px 5px 10px; font-family: Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; color:#4A4A4A;\">Hello $user_email,<br /><br />
        This is a confirmation that the password for your account has been changed successfully.<br />
         <br />If you did not change your password, please verify your account and secure your account as soon as possible.<br /><br /></p>";

         $html = "";
         $html .= "<tr>";
         $html .= "<td style='padding: 10px; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.5; color: #333333;'>";
         $html .= $body;
         $html .= "</td>";
         $html .= "</tr>";

        $final_body = generateEmailConetent("Password Changed", $html);
        $arrData = sendMail($to, $subject, $final_body, $altBody = '');

        echo json_encode($arr);
    } else {
        $arr = fnInvalidToken();
        echo json_encode($arr);
    }
}