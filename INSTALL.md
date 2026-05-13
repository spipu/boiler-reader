# Install

## Headless Configuration

Download and launch **Raspberry Pi Imager**: https://www.raspberrypi.com/software/

### Flash the SD card

1. **Raspberry Pi Device**: Raspberry Pi 3
2. **Operating System**: Raspberry Pi OS (other) → Raspberry Pi OS Lite (64-bit) — Trixie (Debian 13)
3. **Storage**: select the SD card
4. Click **Next** → **Edit Settings**

### Advanced Options

**General tab:**

| Field | Value |
|---|---|
| Set hostname | your hostname |
| Username | your username |
| Password | your password |
| Configure wireless LAN | ✅ SSID, password, Country code |
| Locale settings | Timezone, Keyboard layout |

**Services tab:**

| Field | Value |
|---|---|
| Enable SSH | ✅ Allow public key authentication |
| Authorized public key | paste your public key (`~/.ssh/id_*.pub`) |

→ **Save** → **Yes** → **Yes** (write to card)

### First boot

1. Insert the SD card in the Pi and power it on
2. Wait ~2 minutes
3. Connect via SSH: `ssh <username>@<hostname>.local`

## Boiler Network Connection

The Hargassner Touch Control / Nano-PK controller must be connected to your local network via
Ethernet (RJ45). Make sure the boiler is reachable at a fixed IP address (configure a DHCP
reservation on your router).

Test the connection from the Raspberry Pi:

```bash
telnet <boiler-ip> 23
```

You should see continuous lines starting with `pm` followed by space-separated values.
Press `Ctrl+]` then `quit` to exit telnet.

## Project

### Sudoers

Allow your user to run commands as `www-data` without a password (required for deployment scripts):

```bash
echo "<your-username> ALL=(ALL) NOPASSWD: ALL" | sudo tee /etc/sudoers.d/<your-username>-nopasswd
sudo chmod 440 /etc/sudoers.d/<your-username>-nopasswd
echo "<your-username> ALL=(www-data) NOPASSWD: ALL" | sudo tee /etc/sudoers.d/<your-username>-www-data
sudo chmod 440 /etc/sudoers.d/<your-username>-www-data
```

### Packages

```bash
sudo apt-get -y install sudo lsb-release inetutils-ping curl vim aptitude ca-certificates bash-completion
sudo apt-get -y install less lsof rsync net-tools screen ssl-cert strace tcpdump telnet
sudo apt-get -y install cron file unzip apt-transport-https tar wget zip
```

### Git

```bash
sudo apt-get -y install git
```

Clone the project:

```bash
cd /var/www
sudo chown <your-username>:root .
git clone git@github.com:spipu/boiler-reader.git ./boiler-reader
sudo rm -rf /var/www/html
```

### PHP

Install PHP 8.3 via sury.org:

```bash
curl -sSL https://packages.sury.org/php/README.txt | sudo bash -
sudo apt-get update

sudo apt-get -y install \
  apache2 libapache2-mod-php8.3 php8.3-cli \
  php8.3-bcmath php8.3-common php8.3-curl php8.3-gd \
  php8.3-iconv php8.3-intl php8.3-mbstring php8.3-mysql \
  php8.3-pdo php8.3-pdo-mysql php8.3-readline \
  php8.3-simplexml php8.3-xml php8.3-xsl php8.3-zip
```

Configure PHP (CLI and Apache mod):

```bash
sudo vim /etc/php/8.3/cli/conf.d/99-provision.ini
```

```ini
date.timezone = Europe/Paris
display_errors = True
error_log =
error_reporting = E_ALL
expose_php = False
log_errors = True
upload_max_filesize = 8M
session.auto_start = 0
```

Apply the same file to `/etc/php/8.3/apache2/conf.d/99-provision.ini`.

Configure Apache:

```bash
sudo a2enmod expires
sudo a2enmod headers
sudo a2enmod rewrite
sudo vim /etc/apache2/sites-available/website.conf
```

```apacheconf
<VirtualHost *:80>
    SetEnv APP_ENV prod

    AddDefaultCharset Off
    AddType 'text/html; charset=UTF-8' html

    DocumentRoot "/var/www/boiler-reader/website/public"
    DirectoryIndex index.php

    <Directory "/var/www/boiler-reader/website/public">
        Options -Indexes +FollowSymLinks
        AllowOverride None
        Allow from All

        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-l
        RewriteRule .* index.php [QSA,L]
    </Directory>

    <Directory ~ "/var/www/boiler-reader/website/public/(bundles|media)/">
        Options -Indexes +FollowSymLinks
        AllowOverride None
        Allow from All

        <FilesMatch .*\.(ico|jpg|jpeg|png|gif|svg|js|css|swf|eot|ttf|otf|woff|woff2)$>
            Header append Cache-Control public
        </FilesMatch>

        <FilesMatch .*\.(zip|gz|gzip|bz2|csv|xml)$>
            Header append Cache-Control no-store
        </FilesMatch>

        <FilesMatch "\.(ph(p[3457]?|t|tml)|[aj]sp|p[ly]|sh|cgi|shtml?|html?)$">
            SetHandler None
            ForceType text/plain
        </FilesMatch>
    </Directory>

    LogLevel warn
    ErrorLog /var/log/apache2/boiler-reader-error.log
    CustomLog /var/log/apache2/boiler-reader-access.log combined
</VirtualHost>
```

```bash
sudo rm /etc/apache2/sites-enabled/*
sudo ln -s /etc/apache2/sites-available/website.conf /etc/apache2/sites-enabled/website.conf
sudo apache2ctl -S
sudo systemctl restart apache2
sudo systemctl enable apache2
```

### MariaDB

Install MariaDB:

```bash
sudo apt-get install mariadb-server
```

Configure:

```bash
sudo mkdir -p /var/log/mysql
sudo vim /etc/mysql/mariadb.conf.d/provision.cnf
```

```ini
[mysqld]

# Charset
character-set-server = utf8
collation-server = utf8_general_ci

# InnoDB - Other settings
innodb_flush_log_at_trx_commit = 1
innodb_file_per_table = 1

# Network and DNS resolution settings
bind-address = 127.0.0.1
port = 3306
skip-name-resolve

# Slow query Log settings
slow_query_log = 1
slow_query_log_file = /var/log/mysql/mysql-slow.log
long_query_time = 10
```

```bash
sudo systemctl restart mysql
sudo systemctl enable mysql
```

Create the app database and user:

```bash
sudo mysql
```

```sql
CREATE DATABASE IF NOT EXISTS `boiler-reader`;
CREATE USER IF NOT EXISTS 'boiler-reader'@'localhost' IDENTIFIED BY '<your-password>';
GRANT USAGE ON *.* TO 'boiler-reader'@'localhost';
GRANT ALL PRIVILEGES ON `boiler-reader`.* TO 'boiler-reader'@'localhost' WITH GRANT OPTION;
```

### Composer

```bash
sudo -s
wget -q https://getcomposer.org/composer-stable.phar
mv ./composer-stable.phar /usr/local/bin/composer
chmod 775 /usr/local/bin/composer
exit
```

### Finalize project

Configure the `var/` folder permissions:

```bash
cd /var/www/boiler-reader/website
mkdir -p ./var
sudo chown <your-username>:www-data ./var
chmod 775 ./var
```

Create the `.env.local` file:

```bash
vim /var/www/boiler-reader/website/.env.local
```

```ini
DATABASE_URL=mysql://boiler-reader:<your-password>@localhost:3306/boiler-reader?serverVersion=mariadb-11.8.6
MAILER_DSN=native://default

APP_ENV=prod
APP_SECRET=<your-app-secret>
APP_ENCRYPTOR_KEY_PAIR=<your-encryptor-key>
```

Generate `APP_SECRET`:

```bash
sudo -u www-data php -r "echo bin2hex(random_bytes(16)) . PHP_EOL;"
```

Generate `APP_ENCRYPTOR_KEY_PAIR`:

```bash
cd /var/www/boiler-reader/website
sudo -u www-data php bin/console spipu:encryptor:generate-key-pair
```

Install the application:

```bash
cd /var/www/boiler-reader
./architecture/update-app.sh
```

The default username is `admin` and the default password is `password`.
**Change it on the first login.**

## Configure the boiler connection

In the admin panel (Admin → Configuration → Boiler Reader), set:

| Key | Description |
|---|---|
| `boiler.host` | IP address of your Hargassner boiler on the local network |
| `boiler.port` | TCP port (default: `23`) |
| `boiler.push.url` | URL of your remote HTTP API |
| `boiler.push.api_name` | API username |
| `boiler.push.api_key` | API secret key (stored encrypted) |

## Test

Run the command manually to verify the boiler connection and push:

```bash
sudo -u www-data /var/www/boiler-reader/website/bin/console app:boiler:run
```

If all is working fine, the cron is already installed and will run every minute automatically.

```bash
sudo -u www-data crontab -l
```

That's all!
