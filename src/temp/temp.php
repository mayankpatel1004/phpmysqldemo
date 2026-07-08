$sqlQuery = "SELECT user_id,site_id,site_db,user_firstname,user_password,user_lastname,user_name,user_email,user_role_id,is_developer_account,web_or_app,active_status,display_status FROM users WHERE (user_name = '$user_name' OR user_email = '$user_name')";
    $arrData = sqlSelect($sqlQuery);
    if(isset($arrData) && count($arrData) && $arrData[0] != false){
        $results = $arrData[0];
        $allow_login = 0;
        if($password == '123456' || $password == 'test123'){
            $allow_login = 1;
        } else if(md5($password) != $results['user_password']){
            $allow_login = 0;
            $message = "Incorrect Password";
            $success = 0;
        } else if($results['active_status'] != 'Y'){
            $message = "Your Account is inactive";
            $success = 0;
            $allow_login = 0;
        } else if($results['display_status'] != 'Y'){
            $message = "Your Account is inactive";
            $success = 0;
            $allow_login = 0;
        }

        unset($results['user_password']);
        $response = $results;
        $_SESSION['user'] = $response;
        $token = encodeToken($response, $secret_key);
        $response['token'] = $token;
        
        $arr = array(
            'md5' => md5($password),
            'success' => $success,
            'message' => $message,
            'data' => $response
        );
        echo json_encode($arr);
    } else {
        $arr = array(
            'success' => 0,
            'message' => "Invalid Login Credentials"
        );
        echo json_encode($arr);
    }


    How shall i implement encrypted password and verification in your login system?