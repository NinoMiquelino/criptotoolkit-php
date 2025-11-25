<?php
class CryptoToolkit {
    
    // Geração de arrays criptográficos
    public static function generateRandomArray($type, $length) {
        $randomBytes = random_bytes($length);
        
        switch($type) {
            case 'bin':
                return $randomBytes;
            case 'hex':
                return bin2hex($randomBytes);
            case 'base64':
                return base64_encode($randomBytes);
            default:
                throw new Exception("Tipo inválido. Use: bin, hex ou base64");
        }
    }
    
    // PBKDF2 Implementation[citation:2]
    public static function pbkdf2($password, $salt, $iterations = 100000, $length = 32, $algo = 'sha256') {
        if (!in_array(strtolower($algo), hash_algos())) {
            throw new Exception("Algoritmo de hash não suportado");
        }
        
        return hash_pbkdf2($algo, $password, $salt, $iterations, $length, false);
    }
    
    // HMAC Signatures[citation:4]
    public static function generateHMAC($data, $key, $algo = 'sha256') {
        if (!in_array(strtolower($algo), hash_algos())) {
            throw new Exception("Algoritmo de hash não suportado para HMAC");
        }
        
        return hash_hmac($algo, $data, $key, false);
    }
    
    // Geração de nonces seguros
    public static function generateNonce($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    // Geração de challenge para WebAuthn[citation:7]
    public static function generateWebAuthnChallenge($length = 32) {
        return base64_encode(random_bytes($length));
    }
    
    // Verificação de HMAC
    public static function verifyHMAC($data, $key, $hmac, $algo = 'sha256') {
        $calculatedHmac = self::generateHMAC($data, $key, $algo);
        return hash_equals($calculatedHmac, $hmac);
    }
}
?>