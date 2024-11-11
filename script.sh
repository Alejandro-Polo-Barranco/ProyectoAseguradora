#!/bin/bash
# Se actualizan e instalan los paquetes de apache y git
yum update -y
yum install -y httpd git

# Se inicia y habilita apache
systemctl start httpd
systemctl enable httpd

# Nos aseguramos de estar donde queremos que esten los archivos
cd /var/www/html

# Clono mi repositorio
git clone https://github.com/Alejandro-Polo-Barranco/ProyectoAseguradora.git

# Copiar la carpeta 'aseguradora' (HTML) al directorio raíz de Apache
cp -r ProyectoAseguradora/aseguradora/* .

# Copiar la carpeta 'php_app' al directorio `/var/www/html/php_app`
mkdir /var/www/html/php_app
cp -r ProyectoAseguradora/php_app/* /var/www/html/php_app

# Permisos para que php pueda leer los archivos
sudo chown -R apache:apache /var/www/html
sudo chmod -R 755 /var/www/html

# Reiniciar Apache para aplicar los cambios
systemctl restart httpd