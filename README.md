# PROJETO

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



## Instalando dependências

comando: ``` composer require <nome-dependencia>```

Depedências:

```
firebase/php-jwt
```