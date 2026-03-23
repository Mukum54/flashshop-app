# AWS Deployment Guide for FlashShop

This guide fulfills the assignment requirements to deploy the application on an Amazon EC2 instance using PM2.

## Requirement 1 & 2: Create a server online & Create an instance on Amazon

1. Log in to your [AWS Management Console](https://aws.amazon.com/console/).
2. Navigate to **EC2** and click **Launch Instance**.
3. **Name**: Enter a name for your instance (e.g., `FlashShop-Server`).
4. **AMI**: Select **Ubuntu Server 22.04 LTS** (or 24.04).
5. **Instance Type**: Select `t2.micro` (Free tier eligible).
6. **Key Pair**: Create a new `.pem` key pair (e.g., `flashshop-key.pem`) and download it securely.
7. **Network Settings**:
   - Check **Allow SSH traffic from Anywhere**
   - Check **Allow HTTP traffic from the internet**
   - Check **Allow HTTPS traffic from the internet**
   - *Important*: Edit the security group to add a **Custom TCP Rule** for Port `8000` (Anywhere `0.0.0.0/0`), since our PM2 app runs on 8000.
8. Click **Launch Instance**.

## Requirement 3: Access the instance and install dependencies

Open your terminal (or Git Bash on Windows) where your `.pem` key is located and run:

```bash
# Set secure permissions for the key
chmod 400 flashshop-key.pem

# SSH into your instance (replace with your instance's Public IPv4 address)
ssh -i "flashshop-key.pem" ubuntu@YOUR_INSTANCE_PUBLIC_IP
```

Once connected, run the following commands to install MariaDB, PHP, MySQL drivers, Node.js, PM2, and Git:

```bash
sudo apt update && sudo apt upgrade -y

# 1. Install Git
sudo apt install -y git

# 2. Install MariaDB & secure it
sudo apt install -y mariadb-server
sudo mysql_secure_installation
# (Press Enter for current password, 'n' for unix_socket, 'Y' to set root password, then 'Y' to the rest)

# 3. Install PHP and MySQL drivers
sudo apt install -y php php-cli php-mysql php-mbstring php-xml php-curl

# 4. Install Node.js (for PM2)
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs

# 5. Install PM2 globally
sudo npm install -g pm2
```

### Setup the Database

```bash
sudo mysql -u root -p
```
*Enter the password you created during `mysql_secure_installation`.*

Inside MySQL:
```sql
CREATE DATABASE phppro;
-- Create an admin user or just use root with the password you set.
-- If using root:
ALTER USER 'root'@'localhost' IDENTIFIED BY 'your_password_here';
FLUSH PRIVILEGES;
EXIT;
```

## Requirement 4: Push your work on GitHub and clone on your server

### On your Local Machine:
1. Navigate to your project directory.
2. Initialize and push to GitHub:
```bash
git init
git add .
git commit -m "Ready for deployment"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git push -u origin main
```

### On your AWS Server:
```bash
# Clone the repository
cd ~
git clone https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
cd YOUR_REPO_NAME

# Import your SQL database
# Make sure to upload or create the SQL file if the user hasn't
mysql -u root -p phppro < sql/database.sql
```

## Requirement 5: Launch your application with PM2

We have created an `ecosystem.config.js` file specifically for PM2 to run the PHP app.

1. Edit the ecosystem file to include your production database password:
```bash
nano ecosystem.config.js
# Change the DB_PASS in the env_production block to your MariaDB password
```

2. Start the application using PM2 and the production environment variables:
```bash
pm2 start ecosystem.config.js --env production
```

3. Configure PM2 to restart on server reboot:
```bash
pm2 startup
# Run the command PM2 prints out
pm2 save
```

### Visit Your Site!

You can now access your application in your browser:
`http://YOUR_INSTANCE_PUBLIC_IP:8000`
