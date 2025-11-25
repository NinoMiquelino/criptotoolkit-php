<?php
require_once 'crypto_functions.php';

class WebAuthnHandler {
    private $rpId;
    private $rpName;
    
    public function __construct($rpId = null, $rpName = "CriptoToolkit") {
        $this->rpId = $rpId ?? $_SERVER['HTTP_HOST'];
        $this->rpName = $rpName;
    }
    
    // Gerar opções de registro
    public function generateRegistrationOptions($userId, $username) {
        // Gera um challenge seguro
        $challenge = random_bytes(32);
        $base64Challenge = base64_encode($challenge);
        
        $options = [
            'challenge' => $this->base64url_encode($challenge),
            'rp' => [
                'name' => $this->rpName,
                'id' => $this->rpId
            ],
            'user' => [
                'id' => $this->base64url_encode($userId),
                'name' => $username,
                'displayName' => $username
            ],
            'pubKeyCredParams' => [
                [
                    'type' => "public-key",
                    'alg' => -7 // ES256
                ],
                [
                    'type' => "public-key", 
                    'alg' => -257 // RS256
                ]
            ],
            'timeout' => 60000,
            'authenticatorSelection' => [
                'authenticatorAttachment' => "cross-platform",
                'requireResidentKey' => false,
                'userVerification' => "preferred"
            ],
            'attestation' => "none"
        ];
        
        // Armazena o challenge original (não encoded) na sessão
        $_SESSION['webauthn_challenge'] = $challenge;
        $_SESSION['webauthn_challenge_base64'] = $base64Challenge;
        $_SESSION['webauthn_userId'] = $userId;
        $_SESSION['webauthn_username'] = $username;
        
        error_log("Challenge gerado e armazenado: " . $base64Challenge);
        
        return $options;
    }
    
    // Verificar registro - versão simplificada para desenvolvimento
    public function verifyRegistration($clientDataJSON, $attestationObject, $credentialId) {
        error_log("Iniciando verificação do WebAuthn");
        
        // Recupera o challenge da sessão
        if (!isset($_SESSION['webauthn_challenge'])) {
            error_log("Erro: Challenge não encontrado na sessão");
            throw new Exception("Challenge não encontrado na sessão");
        }
        
        $storedChallenge = $_SESSION['webauthn_challenge'];
        $expectedChallengeBase64 = $this->base64url_encode($storedChallenge);
        
        error_log("Challenge esperado: " . $expectedChallengeBase64);
        
        // Decodifica o clientDataJSON
        $clientData = json_decode(base64_decode($clientDataJSON), true);
        
        if (!$clientData) {
            error_log("Erro: clientDataJSON inválido");
            throw new Exception("clientDataJSON inválido");
        }
        
        error_log("Challenge recebido: " . ($clientData['challenge'] ?? 'N/A'));
        error_log("Origin: " . ($clientData['origin'] ?? 'N/A'));
        error_log("Type: " . ($clientData['type'] ?? 'N/A'));
        
        // Verifica se o tipo está correto
        if (($clientData['type'] ?? '') !== 'webauthn.create') {
            error_log("Erro: Tipo de clientData incorreto: " . ($clientData['type'] ?? 'vazio'));
            throw new Exception("Tipo de clientData incorreto");
        }
        
        // Verifica o challenge - compara os valores em base64url
        $receivedChallenge = $clientData['challenge'] ?? '';
        if (!hash_equals($expectedChallengeBase64, $receivedChallenge)) {
            error_log("Erro: Challenge não coincide");
            error_log("Esperado: " . $expectedChallengeBase64);
            error_log("Recebido: " . $receivedChallenge);
            throw new Exception("Challenge inválido - não coincide");
        }
        
        // Limpa o challenge da sessão após uso
        unset($_SESSION['webauthn_challenge']);
        unset($_SESSION['webauthn_challenge_base64']);
        
        error_log("WebAuthn verificado com sucesso!");
        
        return [
            'verified' => true,
            'credentialId' => $credentialId,
            'userId' => $_SESSION['webauthn_userId'] ?? '',
            'username' => $_SESSION['webauthn_username'] ?? ''
        ];
    }
    
    // Helper function para base64url encoding
    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    private function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
?>