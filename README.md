## 🙋‍♂️ Autor

<div align="center">
  <img src="https://avatars.githubusercontent.com/ninomiquelino" width="100" height="100" style="border-radius: 50%">
  <br>
  <strong>Onivaldo Miquelino</strong>
  <br>
  <a href="https://github.com/ninomiquelino">@ninomiquelino</a>
</div>

---

# 🔐 CriptoToolkit - Biblioteca de Segurança Completa

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![WebAuthn](https://img.shields.io/badge/WebAuthn-FIDO2-FF6C37?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

Uma suite completa de ferramentas criptográficas implementada em PHP e JavaScript, com interface responsiva e suporte a WebAuthn para autenticação moderna sem senhas.

## ✨ Características Principais

### 🔒 Criptografia & Segurança
- **Gerador de Arrays Criptográficos** - Bin, Hex, Base64
- **PBKDF2** - Derivação de chaves com múltiplas iterações
- **Assinaturas HMAC** - SHA-256, SHA-1, SHA-512
- **Nonces Seguros** - Geração de valores únicos criptograficamente seguros

### 🔐 Autenticação Moderna
- **WebAuthn/FIDO2** - Autenticação sem senhas
- **Suporte a Biometria** - Touch ID, Face ID, Windows Hello
- **Challenges Seguros** - Geração e validação de desafios
- **Cross-Platform** - Funciona em dispositivos móveis e desktop

### 🎨 Interface
- **Design Responsivo** - Otimizado para mobile e desktop
- **UX Intuitiva** - Interface amigável para desenvolvedores
- **Feedback em Tempo Real** - Resultados instantâneos
- **Dark/Light Ready** - Pronto para temas

## 🚀 Instalação Rápida

### Pré-requisitos
- PHP 7.4 ou superior
- Servidor web (Apache/Nginx)
- Navegador moderno com suporte a WebAuthn

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/NinoMiquelino/criptotoolkit-php.git
cd criptotoolkit-php
```

1. Configure o servidor web

```bash
# Para desenvolvimento local com PHP built-in server
php -S localhost:8000 -t frontend
```

1. Acesse a aplicação

```
http://localhost:8000
```

Configuração para Produção

1. Habilite HTTPS (obrigatório para WebAuthn em produção)
2. Configure as permissões de sessão
3. Ajuste o RP ID no arquivo backend/webauthn_handler.php

📖 Como Usar

🎲 Gerador de Arrays Criptográficos

1. Selecione o tipo (Hex, Base64, Binário)
2. Defina o tamanho em bytes
3. Clique em "Gerar" para obter valores aleatórios seguros

🔑 PBKDF2

1. Insira a senha
2. Adicione um salt (ou deixe gerar automaticamente)
3. Defina o número de iterações
4. Gere o hash derivado

📝 Assinaturas HMAC

1. Insira os dados para assinar
2. Forneça a chave secreta
3. Selecione o algoritmo (SHA-256, SHA-1, SHA-512)
4. Obtenha a assinatura HMAC

🔒 WebAuthn

1. Digite um username
2. Clique em "Registrar WebAuthn"
3. Siga as instruções do seu navegador/dispositivo
4. Use biometria ou PIN para completar o registro

🛠️ Estrutura do Projeto

```
criptotoolkit-php/
├── index.html               # Interface principal
│   ├── css/
│   │   └── style.css        # Estilos responsivos
│   └── js/
│       └── app.js           # Aplicação principal
├── backend/
│   ├── api.php              # Endpoint principal da API
│   ├── crypto_functions.php # Funções criptográficas
│   └── webauthn_handler.php # Gerenciador WebAuthn
├── README.md               # Documentação
└── .gitignore             # Arquivos ignorados pelo Git
```

🔧 API Reference

Endpoints Disponíveis

POST /backend/api.php

Ações suportadas:

· generateRandom - Gera arrays aleatórios<br>
· generatePBKDF2 - Deriva chaves PBKDF2<br>
· generateHMAC - Cria assinaturas HMAC<br>
· getWebAuthnOptions - Obtém opções de registro WebAuthn<br>
· verifyWebAuthn - Verifica registro WebAuthn

Exemplo de requisição:

```javascript
{
  "action": "generateRandom",
  "data": {
    "type": "hex",
    "length": 32
  }
}
```

🧪 Testes

Testando Localmente

1. Inicie o servidor de desenvolvimento:

```bash
php -S localhost:8000 -t frontend
```

1. Acesse http://localhost:8000
2. Teste cada funcionalidade:
   · Gere valores aleatórios<br>
   · Crie hashes PBKDF2<br>
   · Gere assinaturas HMAC<br>
   · Registre uma credencial WebAuthn

Verificando WebAuthn

1. Use um navegador compatível (Chrome, Firefox, Edge, Safari)
2. Habilite HTTPS para teste local ou use localhost
3. Teste com diferentes authenticators:
   · Touch ID (macOS)<br>
   · Face ID (iOS)<br>
   · Windows Hello (Windows)<br>
   · Chaves de segurança USB

🔒 Considerações de Segurança

✅ Implementado

· Geração criptograficamente segura de números aleatórios<br>
· Validação de challenges WebAuthn<br>
· Proteção contra timing attacks com hash_equals<br>
· Sanitização de entrada do usuário<br>
· Sessões seguras

⚠️ Recomendações para Produção

· Use HTTPS em produção<br>
· Implemente rate limiting<br>
· Adicione logging de auditoria<br>
· Use armazenamento seguro para chaves<br>
· Mantenha as dependências atualizadas

🌐 Compatibilidade

Navegadores Suportados

· Chrome 67+<br>
· Firefox 60+<br>
· Edge 79+<br>
· Safari 13+

Dispositivos Compatíveis com WebAuthn

· ✅ Windows Hello (Windows 10+)<br>
· ✅ Touch ID (macOS, iOS)<br>
· ✅ Face ID (iPhone, iPad)<br>
· ✅ Android Biometric<br>
· ✅ Security Keys (YubiKey, etc.)

🐛 Reportando Problemas

Encontrou um bug? Abra uma issue descrevendo:

1. Passos para reproduzir
2. Comportamento esperado
3. Comportamento atual
4. Screenshots (se aplicável)

---

Desenvolvido com ❤️ para a comunidade de segurança

Se este projeto te ajudou, considere dar uma ⭐ no repositório!

---

## 🤝 Contribuições
Contribuições são sempre bem-vindas!  
Sinta-se à vontade para abrir uma [*issue*](https://github.com/NinoMiquelino/criptotoolkit-php/issues) com sugestões ou enviar um [*pull request*](https://github.com/NinoMiquelino/criptotoolkit-php/pulls) com melhorias.

---

## 💬 Contato
📧 [Entre em contato pelo LinkedIn](https://www.linkedin.com/in/onivaldomiquelino/)  
💻 Desenvolvido por **Onivaldo Miquelino**

---
