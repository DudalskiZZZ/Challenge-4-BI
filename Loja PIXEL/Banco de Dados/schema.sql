CREATE DATABASE IF NOT EXISTS loja_pixel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loja_pixel;
CREATE TABLE IF NOT EXISTS usuarios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 senha VARCHAR(255) NOT NULL,
 perfil ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS categorias (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS produtos (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 categoria_id INT UNSIGNED NOT NULL,
 nome VARCHAR(160) NOT NULL,
 descricao TEXT NOT NULL,
 preco DECIMAL(10,2) NOT NULL,
 estoque INT UNSIGNED NOT NULL DEFAULT 0,
 imagem VARCHAR(500) DEFAULT NULL,
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pedidos (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT UNSIGNED NOT NULL,
 total DECIMAL(10,2) NOT NULL,
 pagamento ENUM('PIX','Boleto','Cartão') NOT NULL,
 status VARCHAR(40) NOT NULL DEFAULT 'Confirmado',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_pedido_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pedido_itens (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 pedido_id INT UNSIGNED NOT NULL,
 produto_id INT UNSIGNED NOT NULL,
 quantidade INT UNSIGNED NOT NULL,
 preco_unitario DECIMAL(10,2) NOT NULL,
 CONSTRAINT fk_item_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
 CONSTRAINT fk_item_produto FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB;
INSERT IGNORE INTO categorias (id,nome) VALUES (1,'Consoles'),(2,'Jogos'),(3,'Acessórios'),(4,'Hardware');
INSERT INTO produtos (categoria_id,nome,descricao,preco,estoque,imagem) VALUES
(2,'Aventura Pixel','Jogo de aventura para explorar mundos e resolver desafios.',149.90,12,'assets/img/placeholder.svg'),
(3,'Controle sem fio','Controle ergonômico para jogar no computador ou console.',219.90,8,'assets/img/placeholder.svg'),
(4,'Headset Gamer','Headset com microfone para comunicação durante partidas.',189.90,15,'assets/img/placeholder.svg'),
(1,'Console Retro','Console compacto com visual clássico (produto demonstrativo).',499.90,5,'assets/img/placeholder.svg')
ON DUPLICATE KEY UPDATE nome=VALUES(nome);
-- Após criar sua conta pelo site, torne-a administradora executando:
-- UPDATE usuarios SET perfil='admin' WHERE email='seu-email@exemplo.com';
