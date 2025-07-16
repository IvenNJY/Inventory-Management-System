# Vercel Environment Variables Setup

## Required Environment Variables

Set these in your Vercel dashboard under Settings > Environment Variables:

### Basic Laravel Configuration
```
APP_NAME=Asset Management System
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://your-app.vercel.app
```

### Database Configuration (if using external DB)
```
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

### Session and Cache
```
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

### Mail Configuration (if needed)
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Asset Management System"
```

## Important Notes

1. Generate APP_KEY locally: `php artisan key:generate --show`
2. Use a cloud database service like PlanetScale, Railway, or Supabase
3. File uploads will need external storage like AWS S3 or Cloudinary
4. Session storage should use 'cookie' driver for serverless
5. Logs should use 'stderr' channel for Vercel

## Deployment Steps

1. Install Vercel CLI: `npm i -g vercel`
2. Login: `vercel login`
3. Deploy: `vercel --prod`
4. Set environment variables in Vercel dashboard
5. Run migrations on your database manually or via database client
