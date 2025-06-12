<?php

class EmailHelper {
    private static $apiKey = 're_Y6Dk3sdy_FsSdW6Vsv334mhCNm1MkG1fG';
    private static $apiUrl = 'https://api.resend.com/emails';
    
    /**
     * Gửi email reset password
     */    public static function sendPasswordResetEmail($email, $token) {
        // Log token being sent
        error_log("Sending reset email with token: " . $token);
        
        $emailData = [
            'from' => 'Webbanhang <no-reply@honeysocial.click>',
            'to' => [$email],
            'subject' => 'Mã xác nhận đặt lại mật khẩu - Webbanhang',
            'html' => self::getPasswordResetEmailTemplate($token)
        ];
        
        return self::sendEmail($emailData);
    }
    
    /**
     * Gửi email qua Resend API
     */
    private static function sendEmail($emailData) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => self::$apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($emailData, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . self::$apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
          $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Detailed logging
        error_log("Resend API Request Data: " . json_encode($emailData));
        error_log("Resend API Response Code: " . $httpCode);
        error_log("Resend API Response: " . $response);
        
        if ($error) {
            error_log("Resend API Error: " . $error);
            return false;
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            error_log("Email sent successfully to: " . implode(", ", $emailData['to']));
            return true;
        }
        
        error_log("Email sending failed with code: " . $httpCode);
        return false;
    }
    
    /**
     * Template email reset password
     */    private static function getPasswordResetEmailTemplate($token) {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mã xác nhận đặt lại mật khẩu</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; border: 1px solid #ddd;">
        <h2 style="color: #333; margin-bottom: 20px;">Mã xác nhận đặt lại mật khẩu</h2>
        
        <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
        
        <p>Mã xác nhận của bạn là:</p>
        
        <div style="margin: 30px 0; background-color: #e9ecef; padding: 15px; border-radius: 5px; text-align: center;">
            <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px;">' . $token . '</span>
        </div>
        
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
        
        <p>Liên kết này sẽ hết hạn sau 30 phút.</p>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
        
        <p style="color: #666; font-size: 12px;">
            Email này được gửi tự động, vui lòng không trả lời.
        </p>
    </div>
</body>
</html>';
    }
}
