# Loja Pixel — E-commerce acadêmico

Projeto de comércio eletrônico desenvolvido com PHP, MySQL/MariaDB, PDO, Bootstrap 5 e JavaScript, compatível com Apache/XAMPP.

## Requisitos
- XAMPP com Apache, PHP 8+ e MySQL/MariaDB
- Extensões PHP `pdo_mysql` e `mbstring`
- Navegador atualizado

## Instalação no XAMPP
1. Copie a pasta `loja_pixel` para `C:\xampp\htdocs\`.
2. Inicie Apache e MySQL no painel do XAMPP.
3. Abra `http://localhost/phpmyadmin` e importe `database/schema.sql`.
4. Copie `.env.example` para `.env` e ajuste host, banco, usuário e senha.
5. Acesse `http://localhost/loja_pixel/`.
6. Crie uma conta pela tela Cadastro.
7. Para habilitar o painel administrativo, execute no phpMyAdmin (aba SQL), substituindo pelo e-mail cadastrado:
   `UPDATE usuarios SET perfil='admin' WHERE email='seu-email@exemplo.com';`
8. Entre novamente; o link Admin aparecerá no menu.

## Funcionalidades
- Catálogo dinâmico, busca e filtro por categoria
- Página individual com preço, descrição e estoque
- Carrinho por sessão com atualização e remoção
- Cadastro e login com `password_hash()` / `password_verify()`
- Checkout simulado (PIX, boleto ou cartão)
- Pedido e itens persistidos no banco; baixa de estoque transacional
- Painel administrativo com CRUD de produtos e consulta de pedidos
- PDO, prepared statements, token CSRF, escaping HTML e autorização no servidor

## Estrutura
- `includes/`: inicialização, funções, cabeçalho e rodapé
- `admin/`: painel, cadastro/edição e listagem de produtos e pedidos
- `database/schema.sql`: banco, tabelas e carga inicial
- `assets/`: CSS, JavaScript e imagem padrão

## Git
O arquivo `.env` é ignorado. Não envie credenciais reais ao repositório. Inclua `.env.example`.
Faça commits ao longo do desenvolvimento, por exemplo:
- `chore: cria estrutura inicial`
- `feat: adiciona catálogo e banco de dados`
- `feat: implementa autenticação e carrinho`
- `feat: adiciona checkout e painel admin`
- `docs: adiciona instruções de instalação`

## Observações
- O pagamento é apenas uma simulação: não há integração com banco ou operadora.
- Para uso real, seriam necessários HTTPS, política de privacidade, validação adicional, logs, proteção contra abuso e integração segura com gateway de pagamento.
