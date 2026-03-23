module.exports = {
  apps: [
    {
      name: 'flashshop-app',
      script: 'php',
      args: '-S 0.0.0.0:8000 -t public',
      watch: true,
      ignore_watch: ['node_modules', 'logs', 'public/uploads'],
      env: {
        APP_ENV: 'development',
        DB_HOST: 'localhost',
        DB_NAME: 'phppro',
        DB_USER: 'root',
        DB_PASS: ''
      },
      env_production: {
        APP_ENV: 'production',
        DB_HOST: 'localhost',
        DB_NAME: 'phppro',
        DB_USER: 'root',
        DB_PASS: 'your_production_password' // Update this on the server
      }
    }
  ]
};
