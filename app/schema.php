<?php
declare(strict_types=1);

function create_schema(PDO $pdo): void
{
    $queries = [
        'CREATE TABLE IF NOT EXISTS admin_users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(120) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE, senha_hash VARCHAR(255) NOT NULL, ativo TINYINT(1) NOT NULL DEFAULT 1, criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ultimo_login DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        'CREATE TABLE IF NOT EXISTS login_tentativas (chave CHAR(64) PRIMARY KEY, ip VARCHAR(45) NOT NULL, email VARCHAR(190) NOT NULL, falhas INT UNSIGNED NOT NULL DEFAULT 0, bloqueado_ate DATETIME NULL, atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX (atualizado_em)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        'CREATE TABLE IF NOT EXISTS site_conteudo (chave VARCHAR(100) PRIMARY KEY, texto_pt TEXT NOT NULL, texto_en TEXT NOT NULL, secao VARCHAR(50) NOT NULL, atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX (secao)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        'CREATE TABLE IF NOT EXISTS equipe (id INT UNSIGNED PRIMARY KEY, foto VARCHAR(255) NOT NULL, instagram VARCHAR(1000) NOT NULL DEFAULT \'\', linkedin VARCHAR(1000) NOT NULL DEFAULT \'\', atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        'CREATE TABLE IF NOT EXISTS portfolio (id INT UNSIGNED PRIMARY KEY, categoria VARCHAR(20) NOT NULL, ativo TINYINT(1) NOT NULL DEFAULT 1, imagem VARCHAR(255) NOT NULL DEFAULT \'\', imagem_destaque VARCHAR(255) NOT NULL DEFAULT \'\', atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        'CREATE TABLE IF NOT EXISTS alteracoes_log (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, usuario_id INT UNSIGNED NOT NULL, secao VARCHAR(80) NOT NULL, detalhes TEXT NOT NULL, criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (usuario_id) REFERENCES admin_users(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        'CREATE TABLE IF NOT EXISTS setup_estado (id TINYINT UNSIGNED PRIMARY KEY, instalado_em DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    ];
    foreach ($queries as $sql) $pdo->prepare($sql)->execute();
    $pdo->prepare('INSERT IGNORE INTO setup_estado (id) VALUES (1)')->execute();
}

function migrate_portfolio_status(PDO $pdo): void
{
    $stmt = $pdo->query("SHOW COLUMNS FROM portfolio LIKE 'ativo'");
    if (!$stmt->fetch()) {
        try { $pdo->exec('ALTER TABLE portfolio ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1'); }
        catch (PDOException $error) {
            if (($error->errorInfo[1] ?? 0) !== 1060) throw $error;
        }
    }
}
