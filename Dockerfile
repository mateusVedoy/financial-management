FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# --- Correção do Locale (Passos 1 e 2) ---
RUN apt-get update && apt-get install -y locales && \
    locale-gen pt_BR.UTF-8

# --- Define o locale como padrão do ambiente (Passo 3) ---
ENV LANG pt_BR.UTF-8
ENV LANGUAGE pt_BR:pt
ENV LC_ALL pt_BR.UTF-8
# -----------------------------------------------

# [NOVO] 1. Instala curl (necessário para baixar o script de instalação do Node)
RUN apt-get update && apt-get install -y curl gnupg

# [NOVO] 2. Adiciona o repositório do Node.js (Versão 20 LTS - Long Term Support)
# Se quiser a versão mais recente, mude setup_20.x para setup_22.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

# Atualiza pacotes e instala Apache, MySQL, PHP e NODEJS
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
        nodejs \
        apache2 \
        mysql-server \
        php \
        libapache2-mod-php \
        php-mysql \
        php-cli \
        php-curl \
        php-zip \
        php-mbstring \
        php-xml \
        php-bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# [OPCIONAL] Verifica as versões instaladas (útil para logs de build)
RUN node -v && npm -v

# Configura senha do root do MySQL
RUN service mysql start && \
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root'; FLUSH PRIVILEGES;"

# Habilita o módulo PHP no Apache
RUN a2enmod php*

# Expõe as portas
EXPOSE 80 3306

# Comando padrão para manter o Apache rodando em foreground
CMD service mysql start && apache2ctl -D FOREGROUND