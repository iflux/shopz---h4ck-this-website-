FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php7.4 \
    php7.4-mysql \
    php7.4-xml \
    php7.4-curl \
    php7.4-gd \
    libapache2-mod-php7.4 \
    mysql-server \
    vsftpd \
    openssh-server \
    sudo \
    curl \
    git \
    nano \
    net-tools \
    iputils-ping \
    dnsutils \
    && rm -rf /var/lib/apt/lists/*

RUN useradd -m -s /bin/bash admin && echo "admin:admin123" | chpasswd
RUN useradd -m -s /bin/bash dev && echo "dev:dev" | chpasswd
RUN useradd -m -s /bin/bash backupmgr && echo "backupmgr:backup2024" | chpasswd
RUN echo "admin ALL=(ALL) NOPASSWD: /usr/bin/vim" >> /etc/sudoers
RUN echo "dev ALL=(ALL) NOPASSWD: /usr/bin/find" >> /etc/sudoers

RUN mkdir -p /var/run/sshd
RUN sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN sed -i 's/#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config
RUN echo "root:toor" | chpasswd

RUN echo "anonymous_enable=YES" >> /etc/vsftpd.conf
RUN echo "local_enable=YES" >> /etc/vsftpd.conf
RUN echo "write_enable=YES" >> /etc/vsftpd.conf
RUN echo "anon_root=/var/ftp" >> /etc/vsftpd.conf
RUN mkdir -p /var/ftp/pub
RUN echo "FLAG{ftp_anon_access_granted}" > /var/ftp/pub/secret_backup.txt
RUN echo "db_password=shopz_pass123" >> /var/ftp/pub/config_old.txt
RUN chmod -R 755 /var/ftp

RUN a2enmod rewrite
RUN echo "ServerTokens Full" >> /etc/apache2/apache2.conf
RUN echo "ServerSignature On" >> /etc/apache2/apache2.conf
RUN sed -i 's/expose_php = Off/expose_php = On/' /etc/php/7.4/apache2/php.ini
RUN sed -i 's/display_errors = Off/display_errors = On/' /etc/php/7.4/apache2/php.ini
RUN sed -i 's/allow_url_include = Off/allow_url_include = On/' /etc/php/7.4/apache2/php.ini

RUN echo "<Directory /var/www/html/uploads>\n    Options +Indexes\n</Directory>" >> /etc/apache2/apache2.conf
RUN echo "<Directory /var/www/html/backup>\n    Options +Indexes\n</Directory>" >> /etc/apache2/apache2.conf

COPY apache-admin.conf /etc/apache2/sites-available/admin.conf
RUN a2ensite admin.conf

RUN echo "FLAG{sudo_privesc}" > /root/root_flag.txt
RUN chmod 600 /root/root_flag.txt

RUN echo "FLAG{suid_exploit}" > /opt/.secret_flag
RUN chmod 4755 /usr/bin/find

COPY start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /var/www/html

EXPOSE 80 21 22 3306 8080

CMD ["/start.sh"]
