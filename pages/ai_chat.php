<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = trim($input['message'] ?? '');

if (empty($userMessage)) {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

// Set your Anthropic API key here or via environment variable
$apiKey = getenv('ANTHROPIC_API_KEY') ?: 'YOUR_API_KEY_HERE';

$systemPrompt = <<<SYSTEM
You are a friendly and helpful customer support assistant for FastNetUG, a WiFi internet service provider in Uganda. Help customers with information about our services, packages, coverage, and support.

## Our WiFi Packages

| Package | Price | Duration | Best For |
|---------|-------|----------|----------|
| Daily | 1,000 UGX | 1 day | Quick access |
| Weekly | 6,000 UGX | 7 days | Short stays |
| Monthly | 20,000 UGX | 30 days | Regular users |
| Semester | 50,000 UGX | ~4 months | Students |
| Family Bundle | 50,000 UGX/month | 30 days | Families (multiple devices) |
| Business Pro | Custom pricing | Monthly | Businesses & offices |

## Coverage Areas
We currently provide coverage in:
- Nabisunsa Close, Jinja Road, Kampala (main office area)
- Kiwanga
- Ntinda
- Kibuli
We are expanding — customers can contact us to check coverage at their specific location.

## Contact & Support
- Phone/WhatsApp: 0756585769 or 0780393671
- Email: fastnetuganda@gmail.com
- Office: Nabisunsa Close, Jinja Rd, Kampala
- WhatsApp chat is often the fastest way to get support

## Installation Process
1. Customer contacts us via phone/WhatsApp/email
2. We check coverage at their location (free)
3. Schedule installation appointment
4. Our technician visits and installs the equipment
5. Customer is connected and gets their login credentials
Installation is quick, usually same-day or next day after confirmation.

## How to Connect / Activate
- After purchase, customers receive login credentials
- Connect to the FastNetUG WiFi network
- Open a browser — a login page appears automatically
- Enter your credentials to activate your package

## Payment
- Payments via Mobile Money (MTN/Airtel)
- Pay to 0756585769 or 0780393671
- Send payment confirmation via WhatsApp for quick activation

## About FastNetUG
FastNetUG is a growing WiFi ISP dedicated to providing fast, affordable internet to students, hostels, families, and businesses across Uganda. We pride ourselves on reliable connectivity and excellent customer service.

## Guidelines
- Be warm, concise, and helpful
- If asked about something you don't know (like very specific technical details), suggest the customer contact us directly
- Always provide the contact number 0756585769 for urgent issues
- Respond in the same language the customer uses (English or Luganda if needed)
- Keep answers short and to the point — most users are on mobile phones
SYSTEM;

$requestBody = json_encode([
    'model' => 'claude-haiku-4-5',
    'max_tokens' => 512,
    'system' => $systemPrompt,
    'messages' => [
        ['role' => 'user', 'content' => $userMessage]
    ]
]);

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $requestBody,
    CURLOPT_HTTPHEADER => [
        'x-api-key: ' . $apiKey,
        'anthropic-version: 2023-06-01',
        'content-type: application/json'
    ],
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(['reply' => 'Sorry, I could not connect right now. Please call us on 0756585769 for immediate help.']);
    exit;
}

$data = json_decode($response, true);

if ($httpCode !== 200 || empty($data['content'][0]['text'])) {
    echo json_encode(['reply' => 'Sorry, I am temporarily unavailable. Please WhatsApp or call 0756585769 for support.']);
    exit;
}

echo json_encode(['reply' => $data['content'][0]['text']]);
