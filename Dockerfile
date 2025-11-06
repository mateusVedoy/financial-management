FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Atualiza pacotes e instala Apache, MySQL e PHP com extensões
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
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

# Configura senha do root do MySQL
RUN service mysql start && \
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root'; FLUSH PRIVILEGES;"

# Habilita o módulo PHP no Apache
RUN a2enmod php*

# Expõe as portas
EXPOSE 80 3306

# Comando padrão para manter o Apache rodando em foreground
CMD service mysql start && apache2ctl -D FOREGROUND