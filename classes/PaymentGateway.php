<?php
class PaymentGateway {
    private $secretKey;
    private $publicKey;
    
    public function __construct() {
        $this->secretKey = 'sk_test_e20a91ff8223d64fe885e1490528fcca3f683f5b'; // Replace with your actual secret key
        $this->publicKey = 'pk_test_84b1eb7af7f6744358d006b372d6caf9975a77ab'; // Replace with your actual public key
    }
    
    public function initializePayment($email, $amount, $orderId, $callback_url) {
        $url = "https://api.paystack.co/transaction/initialize";
        
        $fields = [
            'email' => $email,
            'amount' => $amount * 100, // Convert to kobo
            'reference' => 'order_' . $orderId . '_' . time(),
            'callback_url' => $callback_url,
            'metadata' => json_encode(['order_id' => $orderId])
        ];
        
        $fields_string = http_build_query($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->secretKey,
            "Cache-Control: no-cache",
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result, true);
    }
    
    public function verifyPayment($reference) {
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->secretKey,
            "Cache-Control: no-cache",
        ]);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result, true);
    }
    
    public function getPublicKey() {
        return $this->publicKey;
    }
}
?>