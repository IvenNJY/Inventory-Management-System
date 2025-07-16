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

### Session and Cache (Serverless Optimized)
```
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
VIEW_COMPILED_PATH=/tmp
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

## Important Notes for Serverless Deployment

1. **No Caching**: Artisan commands like `config:cache` don't work in serverless
2. **Database**: Use cloud database (PlanetScale, Railway, Supabase)
3. **File Storage**: Use external storage (AWS S3, Cloudinary) for uploads
4. **Sessions**: Must use 'cookie' driver
5. **Logs**: Use 'stderr' channel for Vercel
6. **Views**: Compiled to /tmp directory

## Deployment Steps

1. Install Vercel CLI: `npm i -g vercel`
2. Login: `vercel login`
3. Deploy: `vercel --prod`
4. Set environment variables in Vercel dashboard
5. Run migrations on your database manually

## Fixed Build Script

The build script now only runs Node.js commands:
```json
{
  "scripts": {
    "vercel-build": "npm run build"
  }
}
```

PHP optimization commands are skipped since they don't work in serverless environments.
