<?php
    /**
     * File: configuration.php
     * Created: 12/9/12  5:52 AM
     * Upgrade, LLC: Upgrade Your Everything
     * www.upgradeyour.com       903-3CODERS
     */

    DEFINE( 'WEBSITE_DOMAIN', 'http://www.upgradeyourdeveloper.com' );
    DEFINE( 'URL_PREFIX', '/trinketlily/' );
    DEFINE( 'WEBSITE_URL', WEBSITE_DOMAIN.URL_PREFIX );
    DEFINE( 'FONTS_CAT_ID', 56 );
    DEFINE( 'SERVER_HOST', '127.0.0.1' );
    DEFINE( 'MYSQL_USER', 'root' );
    DEFINE( 'MYSQL_PW', '' );
    DEFINE( 'MYSQL_DB', 'trinketlily' );

    DEFINE( 'ADMIN_EMAIL', 'admin@upgradeyour.com' );
    DEFINE( 'PAYPAL_EMAIL', 'trnkt_1351209859_biz@upgradeyour.com' ); //must also be set in pp/sdk_config.ini !!!
    DEFINE( 'PAYPAL_COST_1YEAR', '30.00' );

    /***  LOGIN DEFINES  ***/
    DEFINE( 'LOGOUT_URL', 'logout.php' );

    //TODO: CHANGE THIS TO TRINKETLILY APP CREDENTIALS
    DEFINE( 'FACEBOOK_APPID', '226296247479734' );
    DEFINE( 'FACEBOOK_APPSECRET', '43eee92137eb3e14c952568da9faacbe' );
    DEFINE( 'FLICKR_KEY', 'edcd0bf8abb152a6d5bf7958eabf9b9c' );
    DEFINE( 'FLICKR_SECRET', '56b89a5496127a4f' );



    /***  SECURITY AND HASHES  ***/
    DEFINE( 'UPLOADIFY_SALT', '!?ASdf!82?!?salt-32-WYHDh&s' );
    DEFINE( 'SCRAPBOOK_SHARE_SALT', '3LEFzsQ8ki!$)^&dj3vpkQ' ); //DO NOT CHANGE OR ALL EXISTING SCRAPSHARE LINKS WILL BE INVALIDATED!
    DEFINE( 'SCRAPBOOK_SHARE_KEY', 'cTmLhg4hl@#_HED5KLBpt' ); //DO NOT CHANGE OR ALL EXISTING SCRAPSHARE LINKS WILL BE INVALIDATED!