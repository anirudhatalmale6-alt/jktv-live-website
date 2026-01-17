# JKTV Live - Website & Admin Panel

A modern, mobile-friendly streaming website with unified admin panel.

## File Structure

```
/var/www/html/
├── index.php           # Home page with live player
├── about.php           # About Us page
├── support.php         # Support/Donation page
├── vod.php             # Video On Demand page
├── contact.php         # Contact form page
├── stream_info.json    # Stream title/description data
│
├── includes/
│   ├── config.php      # ⚙️ MAIN CONFIGURATION FILE
│   ├── header.php      # Header template
│   └── footer.php      # Footer template
│
├── admin/
│   ├── index.php       # Admin dashboard
│   ├── login.php       # Admin login
│   ├── logout.php      # Admin logout
│   ├── stream.php      # Stream info control
│   ├── viewers.php     # Viewer count control
│   ├── push.php        # Push notifications
│   ├── vod.php         # VOD manager
│   ├── contacts.php    # Contact messages
│   ├── settings.php    # Settings view
│   ├── quick-links.php # Quick links
│   ├── sidebar.php     # Admin sidebar
│   └── admin.css       # Admin styles
│
├── api/
│   ├── viewer_count.php # Viewer count API
│   └── status.php       # Status API
│
├── assets/
│   ├── css/
│   │   └── style.css   # Main stylesheet
│   ├── js/             # JavaScript files
│   └── images/
│       ├── logo.png    # Site logo
│       ├── favicon.png # Favicon
│       └── poster.jpg  # Video poster image
│
└── data/
    ├── contacts.json   # Contact form submissions
    ├── vod.json        # VOD items
    └── push_log.json   # Push notification log
```

## Configuration

All settings are in **`includes/config.php`**. Edit this single file to configure:

### Site Settings
```php
define('SITE_NAME', 'JKTV Live');
define('SITE_URL', 'https://jktv.live');
```

### Contact Email
```php
define('CONTACT_EMAIL', 'contact@jktv.live');
```

### Admin Credentials
```php
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'JKTVAdmin2024');  // Change this!
```

### Stream URLs
```php
define('STREAM_URL', 'https://jktv.live/hls/live/index.m3u8');
define('CDN_STREAM_URL', 'https://jktv.b-cdn.net/hls/live/index.m3u8');
define('RTMP_SERVER', 'rtmp://jktv.live:1935/live');
define('RTMPS_SERVER', 'rtmps://jktv.live:1936/live');
```

### Push Notifications (Firebase)
```php
define('FIREBASE_SERVER_KEY', '');  // Add your Firebase key
```

### Donation URL
```php
define('DONATION_URL', '');  // e.g., 'https://paypal.me/username'
```

### About Page Content
```php
define('ABOUT_TITLE', 'About JKTV Live');
define('ABOUT_TEXT', 'Your about text here...');
```

## How to Update Content

### Change Stream Title
1. Go to `/admin/` and login
2. Click "Stream Control"
3. Update title and description
4. Click Save

### Change Viewer Count Range
1. Go to Admin > Viewer Count
2. Set minimum and maximum values
3. Click Update

### Add VOD Videos
1. Go to Admin > VOD Manager
2. Fill in video details
3. Click Add Video

### View Contact Messages
1. Go to Admin > Contact Messages
2. View and reply to messages

### Send Push Notification
1. Go to Admin > Push Notifications
2. Enter title and message
3. Click Send (requires Firebase setup)

## Adding/Changing Logo

1. Upload your logo to `/assets/images/logo.png`
2. Recommended size: 200px height, PNG with transparency

## Adding Favicon

1. Upload to `/assets/images/favicon.png`
2. Recommended size: 32x32px or 64x64px

## Adding Video Poster

1. Upload to `/assets/images/poster.jpg`
2. Recommended size: 1920x1080px (16:9 ratio)

## Deployment

1. Upload all files to `/var/www/html/`
2. Set permissions:
   ```bash
   chown -R www-data:www-data /var/www/html/
   chmod -R 755 /var/www/html/
   chmod -R 777 /var/www/html/data/
   chmod 666 /var/www/html/stream_info.json
   ```
3. Edit `includes/config.php` with your settings
4. Access admin at `https://jktv.live/admin/`

## Admin Panel Features

- **Dashboard**: Overview of stream status, viewers, messages
- **Stream Control**: Update stream title and description
- **Viewer Count**: Set fake viewer count range
- **Push Notifications**: Send notifications to app users
- **VOD Manager**: Add/remove video on demand content
- **Contact Messages**: View and manage contact form submissions
- **Quick Links**: Fast access to all tools and URLs
- **Settings**: View current configuration

## Security Notes

1. **Change default admin password** in config.php
2. **Protect data directory** - add `.htaccess` to deny direct access:
   ```apache
   <Files "*">
       Order Deny,Allow
       Deny from all
   </Files>
   ```
3. **Use HTTPS** for all connections
4. **Keep PHP updated** for security patches

## Requirements

- PHP 7.4+ (8.x recommended)
- MySQL/MariaDB
- Apache or Nginx with PHP-FPM
- cURL extension (for push notifications)
- PDO MySQL extension

## Support

For questions or issues, contact the developer.
