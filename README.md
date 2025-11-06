# Financial Management

## Rodando projeto

### 1. Subindo container ubuntu com configs

Se for preciso gerar a imagem do zero:

```bash
docker build -t php-env:v1 .
docker run -d -p 8080:80 -p 3306:3306 --name php-env php-env:v1
```

Se já tiver a imagem contruída:

```bash
docker run --name php-env php-env:v1
```

### 2. Atachando vscode para rodar dentro do container

    1. Clique no ícone de acesso remoto no canto inferior esquerdo do vscode

    2. Escolha a opção 'Attach to runnning container'

    3. Escolha o container que você criou no passo 1

### 3. Abrindo diretório /var/www/html dentro do container

    1. Clique em 'open folder' no painel esquerdo do vscode

    2. Digite na barra de diretório que abrirá o caminho '/var/www/html'

    3. Clique em OK para abrir o projeto nesse diretório

### 4. Configurando mysql
    
    1. Acesse o mysql via terminal com o comando ```mysql -u root -p```

    2. Digite a senha de root para acessar o mysql

    3. Rode os dados presentes no arquivo MYSQL_COMMANDS.sql

## Acessando web

Para acessar a interface web, apenas digite no seu navegador o endereço ```http://127.0.0.1:8000/financial-management/```
que você será drecionado para tela de login

## Gerenciando versões do projeto dentro e fora do container

A cada versão fechada dentro do container, para que seja possível replicar as alterações no github para evitar perdas de configurações, é preciso rodar o comando abaixo dentro do diretório onde está o arquivo do projeto:

```bash
docker cp php-env:/var/www/html/financial-management .
```

O comando acima vai permitir copiar todos os arquivos do projeto para máquina local.

OBS: Poderia ser feito com git dentro do container mas para fins práticos, não o fiz