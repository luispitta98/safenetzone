CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- usuario padrão admin/admin
INSERT INTO usuario (nome, email, senha)
VALUES ('admin', 'admin', '$2y$10$5swnsewC96pqrMU.dW8zwuxtuc28USNGPk./5jKpyt3DGPk1B4pGu');

CREATE TABLE site_proibido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dominio VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE configuracao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_rede VARCHAR(100) NOT NULL DEFAULT '',
    senha_rede VARCHAR(100) NOT NULL DEFAULT ''
);
INSERT INTO configuracao (nome_rede, senha_rede)
VALUES ('SafeNetZone','');

CREATE TABLE servico_bloqueado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    whatsapp_bloqueado BOOLEAN NOT NULL DEFAULT 0,
    discord_bloqueado BOOLEAN NOT NULL DEFAULT 0,
    tiktok_bloqueado BOOLEAN NOT NULL DEFAULT 0
);
INSERT INTO servico_bloqueado (whatsapp_bloqueado, discord_bloqueado, tiktok_bloqueado)
VALUES (0,0,0,0);

CREATE TABLE logs_squid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp INT,
    tempo INT,
    ip VARCHAR(45),
    codigo VARCHAR(50),
    tamanho INT,
    metodo VARCHAR(10),
    url TEXT,
    usuario VARCHAR(100),
    hierarquia VARCHAR(50),
    conteudo_tipo VARCHAR(100),
    mac VARCHAR(45),
    bloqueio_tipo VARCHAR(20) DEFAULT NULL
);
