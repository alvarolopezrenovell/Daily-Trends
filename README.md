# Daily-Trends

### Run project

    # Download symfony
    wget https://get.symfony.com/cli/installer -O - | bash

    # Export path
    export PATH="$HOME/.symfony/bin:$PATH"

    # Start server
    symfony server:start
    
### Create DB

Set DB configuration in .env

    DATABASE_URL="mysql://<user>:<pass>@<host>:<port>/daily_trends"
    
Create DB

    mysql -h<host> -u<user> -p<pass> -e 'CREATE SCHEMA `daily_trends` DEFAULT CHARACTER SET utf8;';
    
Update schema
    
    php bin/console doctrine:schema:update --force

### Execute scraping

This command should be configured in the cron every X time 

    php bin/console dailytrends:update-feed-reader
    
### Execute app

    http://127.0.0.1:8000/