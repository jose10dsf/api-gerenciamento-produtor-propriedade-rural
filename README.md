## Tecnologias Utilizadas

Para criar a API foi utilizado o PHP 8.0, o framework Laravel/Lumen 8.0, banco de dados MySQL 8.0.24, Docker 20.10.16 e o Docker Compose v2.5.1.

## Teste local com Docker

Para instalar a API em sua máquina é necessário ter instalado o Docker e o Docker Compose.

Realize os passos abaixo:

- baixe o projeto abra o terminal ou CMD dentro da pasta do projeto.

- faça uma cópia na mesma pasta do arquivo '.env.example' e renomeie para '.env'.

- execute o comando 'docker-compose up -d' no terminal ou CMD, para iniciar os containers, ao final da execução do comando será criado um container com o banco de dados mysql, um container com phpmyadmin para gerenciar o mysql que estará disponível no endereço 'http://localhost:8086' e um container da API que estará disponível no endereço 'http://localhost'. O usuário do banco de dados é 'app' e a senha é '123456'.

- execute o comando 'docker-compose exec api composer install' para instalar as dependências do projeto.

- execute o comando 'docker-compose exec api php artisan migrate' para criar as tabelas no banco de dados.

- execute o comando 'docker-compose exec api php artisan db:seed' para criar o usuário super admin.

## Teste local sem Docker

Para instalar a API em sua máquina é necessário ter instalado e configurado na linha de comando o PHP na versão 7.4 ou acima e o gerenciador de pacotes PHP Composer.

Realize os passos abaixo:

- baixe o projeto abra o terminal ou CMD dentro da pasta do projeto.

- faça uma cópia na mesma pasta do arquivo '.env.example' e renomeie para '.env', em seguida, abra-o e edite as informações de conexão do banco de dados como: endereço do servidor, porta, nome do banco de dados, nome de usuário e senha.

- execute o comando 'composer install' para instalar as dependências do projeto.

- execute o comando 'php artisan migrate' para criar as tabelas no banco de dados.

- execute o comando 'php artisan db:seed' para criar o usuário super admin.

- execute o comando 'php -S 0.0.0.0:80 -t public' para iniciar o servidor, abra o navegador e acesse o endereço da aplicação 'http://localhost'.

## rotas
Login:
- para fazer login pela primeira fez com o usuário super admin, envie um json '{"name": "admin", "password": "123456"}' na rota  '/api/v1/auth/login' via post.