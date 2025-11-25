class CryptoToolkitUI {
    constructor() {
        this.initEventListeners();
    }
    
    initEventListeners() {
        // Gerar arrays aleatórios
        document.getElementById('generateRandom').addEventListener('click', () => this.generateRandom());
        
        // PBKDF2
        document.getElementById('generatePbkdf2').addEventListener('click', () => this.generatePBKDF2());
        
        // HMAC
        document.getElementById('generateHmac').addEventListener('click', () => this.generateHMAC());
        
        // WebAuthn
        document.getElementById('registerWebAuthn').addEventListener('click', () => this.registerWebAuthn());
    }
    
    // Gerar arrays aleatórios
    async generateRandom() {
        const length = parseInt(document.getElementById('randomLength').value) || 32;
        const type = document.getElementById('randomType').value;
        
        try {
            const response = await this.callBackend('generateRandom', {
                type: type,
                length: length
            });
            
            document.getElementById('randomResult').value = response.result;
        } catch (error) {
            this.showError(error);
        }
    }
    
    // Gerar PBKDF2[citation:8]
    async generatePBKDF2() {
        const password = document.getElementById('pbkdf2Password').value;
        const salt = document.getElementById('pbkdf2Salt').value;
        const iterations = parseInt(document.getElementById('pbkdf2Iterations').value) || 100000;
        
        if (!password) {
            this.showError("Senha é obrigatória");
            return;
        }
        
        try {
            const response = await this.callBackend('generatePBKDF2', {
                password: password,
                salt: salt,
                iterations: iterations
            });
            
            document.getElementById('pbkdf2Result').value = response.result;
        } catch (error) {
            this.showError(error);
        }
    }
    
    // Gerar HMAC[citation:4]
    async generateHMAC() {
        const data = document.getElementById('hmacData').value;
        const key = document.getElementById('hmacKey').value;
        const algorithm = document.getElementById('hmacAlgorithm').value;
        
        if (!data || !key) {
            this.showError("Dados e chave são obrigatórios");
            return;
        }
        
        try {
            const response = await this.callBackend('generateHMAC', {
                data: data,
                key: key,
                algorithm: algorithm
            });
            
            document.getElementById('hmacResult').value = response.result;
        } catch (error) {
            this.showError(error);
        }
    }
    
 // WebAuthn Registration - VERSÃO CORRIGIDA
async registerWebAuthn() {
    const username = document.getElementById('webauthnUsername').value;

    if (!window.PublicKeyCredential) {
        alert("Seu navegador não suporta WebAuthn. Tente usar Chrome, Firefox ou Edge.");
        return;
    }
    
    if (!username) {
        this.showError("Username é obrigatório");
        return;
    }
    
    try {
        console.log("Iniciando registro WebAuthn para:", username);
        
        // 1. Obter opções de registro do servidor
        const optionsResponse = await this.callBackend('getWebAuthnOptions', {
            username: username
        });
        
        console.log("Opções recebidas do servidor:", optionsResponse);
        
        // 2. Preparar as opções para a API WebAuthn
        const publicKey = {
            challenge: this.base64urlToArrayBuffer(optionsResponse.challenge),
            rp: optionsResponse.rp,
            user: {
                id: this.base64urlToArrayBuffer(optionsResponse.user.id),
                name: optionsResponse.user.name,
                displayName: optionsResponse.user.displayName
            },
            pubKeyCredParams: optionsResponse.pubKeyCredParams,
            //timeout: optionsResponse.timeout,
            //authenticatorSelection: optionsResponse.authenticatorSelection,
            //attestation: optionsResponse.attestation
        };        
        
        console.log("PublicKey options:", publicKey);
        
        // 3. Verificar se WebAuthn é suportado
        if (!window.PublicKeyCredential) {
            throw new Error("WebAuthn não é suportado neste navegador");
        }
        
        // 4. Criar credencial
        console.log("Solicitando credencial...");
        const credential = await navigator.credentials.create({
            publicKey: publicKey
        });
        
        console.log("Credencial criada:", credential);
        
        // 5. Preparar dados para verificação
        const verificationData = {
            credential: {
                id: credential.id,
                type: credential.type,
                response: {
                    clientDataJSON: this.arrayBufferToBase64url(credential.response.clientDataJSON),
                    attestationObject: this.arrayBufferToBase64url(credential.response.attestationObject)
                }
            }
        };
        
        // 6. Enviar para verificação no servidor
        console.log("Enviando para verificação...");
        const verificationResponse = await this.callBackend('verifyWebAuthn', verificationData);
        
        if (verificationResponse.verified) {
            this.showSuccess("WebAuthn registrado com sucesso! Credential ID: " + verificationResponse.credentialId);
        } else {
            this.showError("Falha na verificação do WebAuthn");
        }
        
    } catch (error) {
        console.error("Erro no WebAuthn:", error);
        if (error.name === 'NotAllowedError') {
            this.showError("Registro cancelado pelo usuário");
        } else if (error.name === 'InvalidStateError') {
            this.showError("Este authenticator já está registrado");
        } else {
            this.showError("Erro no WebAuthn: " + error.message);
        }
    }
}

// Helper functions atualizadas
base64urlToArrayBuffer(base64url) {
    // Converte base64url para base64 padrão
    const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
    const padding = base64.length % 4;
    const paddedBase64 = padding === 0 ? base64 : base64 + '='.repeat(4 - padding);
    
    const binary = atob(paddedBase64);
    const bytes = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
        bytes[i] = binary.charCodeAt(i);
    }
    return bytes.buffer;
}

arrayBufferToBase64url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    const base64 = btoa(binary);
    // Converte para base64url
    return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

arrayBufferToBase64(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
}          
    
    credentialToJSON(credential) {
        // Converter credential para objeto JSON
        return {
            id: credential.id,
            type: credential.type,
            response: {
                clientDataJSON: this.arrayBufferToBase64(credential.response.clientDataJSON),
                attestationObject: this.arrayBufferToBase64(credential.response.attestationObject)
            }
        };
    }
    
    async callBackend(action, data) {
        const response = await fetch('backend/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                data: data
            })
        });
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.error || 'Erro desconhecido');
        }
        
        return result;
    }
    
    showError(message) {
        alert("Erro: " + message);
    }
    
    showSuccess(message) {
        alert("Sucesso: " + message);
    }
}

// Inicializar a aplicação
document.addEventListener('DOMContentLoaded', () => {
    new CryptoToolkitUI();
});