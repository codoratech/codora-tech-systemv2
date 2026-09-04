-- =====================================================================
-- Banco de Dados: codora_db
-- Sistema Administrativo — Codora Tech
-- =====================================================================

CREATE DATABASE IF NOT EXISTS codora_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE codora_db;

-- 1. Tabela: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  cargo VARCHAR(50) DEFAULT 'Administrador',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabela: clientes
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empresa VARCHAR(150) NOT NULL,
  responsavel VARCHAR(100) NOT NULL,
  documento VARCHAR(20) NOT NULL, -- CPF ou CNPJ
  telefone VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  cidade VARCHAR(100) NOT NULL,
  estado VARCHAR(2) NOT NULL,
  cep VARCHAR(10) NOT NULL,
  endereco VARCHAR(150) NOT NULL,
  numero VARCHAR(20) NOT NULL,
  bairro VARCHAR(100) NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Ativo', -- Ativo, Inativo, Pendente
  observacoes TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_cliente_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabela: servicos
CREATE TABLE IF NOT EXISTS servicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  categoria VARCHAR(50) NOT NULL, -- Desenvolvimento, Design, Consultoria, Manutenção
  descricao TEXT DEFAULT NULL,
  valor_referencia DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(20) NOT NULL DEFAULT 'Ativo', -- Ativo, Inativo
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_servico_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabela: projetos
CREATE TABLE IF NOT EXISTS projetos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT DEFAULT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Planejamento', -- Planejamento, Em andamento, Concluído, Pausado
  progresso INT NOT NULL DEFAULT 0, -- 0 a 100
  responsavel VARCHAR(100) NOT NULL,
  data_inicio DATE DEFAULT NULL,
  data_entrega DATE DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_projeto_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_projeto_cliente (cliente_id),
  INDEX idx_projeto_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabela: ordens_servico
CREATE TABLE IF NOT EXISTS ordens_servico (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero_ordem VARCHAR(20) NOT NULL UNIQUE, -- ex: #0110
  cliente_id INT NOT NULL,
  servico_id INT NOT NULL,
  projeto_id INT DEFAULT NULL,
  responsavel VARCHAR(100) NOT NULL,
  valor DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(30) NOT NULL DEFAULT 'Aberta', -- Aberta, Em andamento, Concluída, Cancelada
  descricao TEXT DEFAULT NULL,
  previsao_conclusao DATE DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ordem_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_ordem_servico FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_ordem_projeto FOREIGN KEY (projeto_id) REFERENCES projetos(id) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX idx_ordem_cliente (cliente_id),
  INDEX idx_ordem_servico (servico_id),
  INDEX idx_ordem_projeto (projeto_id),
  INDEX idx_ordem_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabela: agenda
CREATE TABLE IF NOT EXISTS agenda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  tipo VARCHAR(50) NOT NULL, -- Reunião com cliente, Reunião interna, Prazo de projeto, Atendimento
  cliente_id INT DEFAULT NULL,
  projeto_id INT DEFAULT NULL,
  responsavel VARCHAR(100) NOT NULL,
  data_compromisso DATE NOT NULL,
  hora_compromisso TIME NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Agendado', -- Agendado, Em andamento, Concluído, Cancelado
  descricao TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_agenda_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_agenda_projeto FOREIGN KEY (projeto_id) REFERENCES projetos(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_agenda_cliente (cliente_id),
  INDEX idx_agenda_projeto (projeto_id),
  INDEX idx_agenda_data (data_compromisso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- Dados Iniciais Mínimos para Demonstração (Seed Data)
-- =====================================================================

-- Inserção de Serviços de Exemplo
INSERT INTO servicos (nome, categoria, descricao, valor_referencia, status) VALUES
('Desenvolvimento Web', 'Desenvolvimento', 'Criação de portais e sistemas web sob medida.', 8500.00, 'Ativo'),
('Aplicativo Mobile', 'Desenvolvimento', 'Aplicativos iOS e Android nativos ou híbridos.', 15000.00, 'Ativo'),
('Design System & UI/UX', 'Design', 'Criação de identidade visual e protótipos de alta fidelidade.', 4500.00, 'Ativo'),
('Consultoria DevOps', 'Consultoria', 'Arquitetura de servidores e automação de deploys.', 3500.00, 'Ativo');

-- Inserção de Clientes de Exemplo
INSERT INTO clientes (empresa, responsavel, documento, telefone, email, cidade, estado, cep, endereco, numero, bairro, status, observacoes) VALUES
('Aurea Capital', 'Juliana Prado', '12.345.678/0001-90', '(11) 4002-8922', 'contato@aureacapital.com.br', 'São Paulo', 'SP', '01310-100', 'Av. Paulista', '1000', 'Bela Vista', 'Ativo', 'Cliente desde 2025, contrato de manutenção mensal.'),
('Construtora Vega Ltda.', 'Carlos Alberto', '98.765.432/0001-12', '(21) 3222-4455', 'contato@construtoravega.com.br', 'Rio de Janeiro', 'RJ', '20040-020', 'Av. Rio Branco', '156', 'Centro', 'Ativo', 'Novo cliente cadastrado.'),
('Nortis Alimentos', 'Mariana Costa', '45.123.789/0001-55', '(41) 3011-9988', 'contato@nortis.com.br', 'Curitiba', 'PR', '80010-000', 'Rua XV de Novembro', '450', 'Centro', 'Pendente', 'Em fase de proposta.');

-- Inserção de Projetos de Exemplo
INSERT INTO projetos (cliente_id, nome, descricao, status, progresso, responsavel, data_inicio, data_entrega) VALUES
(1, 'Portal Financeiro Aurea', 'Desenvolvimento do portal corporativo para investimentos.', 'Em andamento', 75, 'Ana Souza', '2025-02-01', '2025-05-30'),
(2, 'App Construtora Vega', 'Aplicativo mobile para acompanhamento de obras.', 'Planejamento', 30, 'Rafael Mendes', '2025-03-01', '2025-07-15');

-- Inserção de Ordens de Serviço de Exemplo
INSERT INTO ordens_servico (numero_ordem, cliente_id, servico_id, projeto_id, responsavel, valor, status, descricao, previsao_conclusao) VALUES
('#0110', 1, 1, 1, 'Ana Souza', 8500.00, 'Em andamento', 'Execução da Sprint 4 do Portal Financeiro.', '2025-03-15'),
('#0111', 2, 2, 2, 'Rafael Mendes', 15000.00, 'Aberta', 'Kickoff e levantamento de requisitos do app mobile.', '2025-04-10');

-- Inserção de Agenda de Exemplo
INSERT INTO agenda (titulo, tipo, cliente_id, projeto_id, responsavel, data_compromisso, hora_compromisso, status, descricao) VALUES
('Reunião de kickoff', 'Reunião com cliente', 2, 2, 'Rafael Mendes', '2025-03-02', '14:30:00', 'Agendado', 'Alinhamento inicial de escopo com a Construtora Vega.'),
('Entrega — Sprint 4', 'Prazo de projeto', 1, 1, 'Ana Souza', '2025-03-15', '18:00:00', 'Agendado', 'Validação das telas do portal financeiro.');

INSERT INTO usuarios (nome, email, senha, cargo) 
VALUES ('Adm', 'admin@codoratech.com.br', '123', 'Administrador');