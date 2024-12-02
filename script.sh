#!/bin/bash

# Actualizar la instancia
yum update -y

# Instalar Nginx, PHP, PHP-FPM y Git
yum install -y nginx git php php-fpm

# Iniciar y habilitar Nginx y PHP-FPM
systemctl start nginx
systemctl enable nginx
systemctl start php-fpm
systemctl enable php-fpm

# Descargar el repositorio de GitHub
cd /usr/share/nginx/html
git clone https://github.com/Alejandro-Polo-Barranco/ProyectoAseguradora.git

# Copiar los archivos HTML al directorio raíz de Nginx
cp -r ProyectoAseguradora/aseguradora/* /usr/share/nginx/html/

# Copiar la carpeta PHP al subdirectorio `/usr/share/nginx/html/php_app`
mkdir /usr/share/nginx/html/php_app
cp -r ProyectoAseguradora/php_app/* /usr/share/nginx/html/php_app/

# Ajustar permisos para que Nginx pueda servir los archivos
chown -R nginx:nginx /usr/share/nginx/html
chmod -R 755 /usr/share/nginx/html

# Configurar Nginx para soportar PHP y características avanzadas
cat <<EOL > /etc/nginx/conf.d/default.conf
server {
    listen       80;
    server_name  localhost;

    root   /usr/share/nginx/html;
    index  index.html index.php;

    # Redirección de HTTP a HTTPS (opcional)
    # return 301 https://\$host\$request_uri;

    location / {
        try_files \$uri \$uri/ =404;
    }

    # Configuración para PHP
    location ~ \.php\$ {
        fastcgi_pass   unix:/run/php-fpm/www.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  \$document_root\$fastcgi_script_name;
        include        fastcgi_params;
    }

    # Autenticación básica para el directorio /admin
    location /admin {
        auth_basic "Restricted Access";
        auth_basic_user_file /etc/nginx/.htpasswd;
        try_files \$uri \$uri/ =404;
    }

    # Configuración de redirección de errores
    error_page 404 /custom_404.html;
    location = /custom_404.html {
        root /usr/share/nginx/html;
        internal;
    }

    # Páginas de error personalizadas
    error_page 500 502 503 504 /custom_50x.html;
    location = /custom_50x.html {
        root /usr/share/nginx/html;
        internal;
    }

    # Negociación de contenido para diferentes idiomas
    location / {
        try_files \$uri \$uri/ =404;
        add_header Content-Language en-US;
    }

    # Configuración de logs
    error_log /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
}
EOL

# Reiniciar Nginx para aplicar los cambios
systemctl restart nginx