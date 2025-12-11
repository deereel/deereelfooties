<?php
class WebhookManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function triggerEvent($eventType, $data) {
        // Get active webhooks for this event
        $stmt = $this->pdo->prepare("SELECT * FROM webhooks WHERE is_active = 1 AND JSON_CONTAINS(events, ?)");
        $stmt->execute([json_encode($eventType)]);
        $webhooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($webhooks as $webhook) {
            $this->sendWebhook($webhook, $eventType, $data);
        }
    }
    
    private function sendWebhook($webhook, $eventType, $data, $attempt = 1) {
        $payload = [
            'event' => $eventType,
            'data' => $data,
            'timestamp' => date('c'),
            'webhook_id' => $webhook['id']
        ];
        
        $signature = hash_hmac('sha256', json_encode($payload), $webhook['secret']);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $webhook['url'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-Webhook-Signature: sha256=' . $signature
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        
        $startTime = microtime(true);
        $response = curl_exec($ch);
        $responseTime = (microtime(true) - $startTime) * 1000;
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Log the attempt
        $this->logWebhook($webhook['id'], $eventType, $payload, $statusCode, $response, $responseTime, $attempt);
        
        // Retry logic for failed webhooks
        if ($statusCode >= 400 && $attempt < 3) {
            sleep(pow(2, $attempt)); // Exponential backoff
            $this->sendWebhook($webhook, $eventType, $data, $attempt + 1);
        }
    }
    
    private function logWebhook($webhookId, $eventType, $payload, $statusCode, $response, $responseTime, $attempt) {
        $stmt = $this->pdo->prepare("INSERT INTO webhook_logs (webhook_id, event_type, payload, status_code, response_body, response_time_ms, attempt_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$webhookId, $eventType, json_encode($payload), $statusCode, $response, $responseTime, $attempt]);
    }
}
?>