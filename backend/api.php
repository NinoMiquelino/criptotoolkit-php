<?php
// Debug detalhado
error_log("=== NOVA REQUISIÇÃO ===");
error_log("POST data: " . print_r($_POST, true));
error_log("INPUT data: " . file_get_contents('php://input'));
error_log("SESSION data: " . print_r($_SESSION, true));

session_start();
require_once 'crypto_functions.php';
require_once 'webauthn_handler.php';

header('Content-Type: application/json');

// Habilitar logging para debug
function log_message($message) {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    $data = $input['data'] ?? [];

    log_message("Ação recebida: " . $action);

    $response = [];

    switch($action) {
        case 'generateRandom':
            $response['result'] = CryptoToolkit::generateRandomArray(
                $data['type'] ?? 'hex', 
                $data['length'] ?? 32
            );
            break;
            
        case 'generatePBKDF2':
            $response['result'] = CryptoToolkit::pbkdf2(
                $data['password'],
                $data['salt'] ?? random_bytes(16),
                $data['iterations'] ?? 100000
            );
            break;
            
        case 'generateHMAC':
            $response['result'] = CryptoToolkit::generateHMAC(
                $data['data'],
                $data['key'],
                $data['algorithm'] ?? 'sha256'
            );
            break;
            
        case 'getWebAuthnOptions':
            log_message("Gerando opções WebAuthn para: " . ($data['username'] ?? ''));
            $webauthn = new WebAuthnHandler();
            $options = $webauthn->generateRegistrationOptions(
                uniqid(),
                $data['username']
            );
            $response = $options;
            $response['success'] = true;
            break;
            
        case 'verifyWebAuthn':
            log_message("Verificando WebAuthn");
            $credential = $data['credential'];
            
            $webauthn = new WebAuthnHandler();
            $verificationResult = $webauthn->verifyRegistration(
                $credential['response']['clientDataJSON'],
                $credential['response']['attestationObject'],
                $credential['id']
            );
            
            $response = $verificationResult;
            $response['success'] = true;
            break;
            
        default:
            log_message("Ação não reconhecida: " . $action);
            throw new Exception("Ação não reconhecida: " . $action);
    }

    echo json_encode($response);
    
} catch (Exception $e) {
    log_message("Erro na API: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>