<?php
    function coin_curl($coin, $type, $price, $number){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "coinCode=".$coin."&tokenId=&status=0&source=2&type=".$type."&entrustWay=1&entrustSum=&entrustPrice=".$price."&entrustCount=".$number,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Cookie: UM_distinctid=162529e102e3-04d3f9b59cf0e6-b34356b-1fa400-162529e102f144; JSESSIONID=371EC943B4F8B60AE9E86BF3BFB91AE5; CNZZDATA1264472947=1214694773-1521801289-http%253A%252F%252Fwww.vitascoin.com%252F%7C1524563159; tokenId=64383213-7cd2-4087-be33-34c8d00f2b36"
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    function sctonum($num, $double = 8){
        if(false !== stripos($num, "e")){
            $a = explode("e",strtolower($num));
            return bcmul($a[0], bcpow(10, $a[1], $double), $double);
        }
    }


    /**
     * 发送邮件方法
     * @param string $objectmail 目标邮箱地址
     * @param string $title 标题
     * @param string $content 内容
     */
    function send_email($objectmail,$title,$content) {

        //Create a new PHPMailer instance
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        //Set the hostname of the mail server
        $mail->Host = '';
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = '';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication
        $mail->Username = '';
        //Password to use for SMTP authentication
        $mail->Password = '';
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式</span><span style="color:#333333;">
        //Set who the message is to be sent from
        $mail->setFrom('', 'SendFrom');
        //Set an alternative reply-to address
        $mail->addReplyTo('', 'ReplyTo');
        //Set who the message is to be sent to
        $mail->addAddress($objectmail);
        //Set the subject line
        $mail->Subject = $title;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //        $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->Body = $content;
        //Attach an image file
        //        $mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
    //            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
    //            echo 'Message sent!';
            return true;
        }
    }