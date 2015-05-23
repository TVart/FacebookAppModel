<?php
namespace Tvart\Facebook;
/**
 * This class provides static methods that return pieces of data specific to
 * your app
 */
class Config {

    /*****************************************************************************
     *
     * These functions provide the unique identifiers that your app users.  These
     * have been pre-populated for you, but you may need to change them at some
     * point.  They are currently being stored in 'Environment Variables'.  To
     * learn more about these, visit
     *   'http://php.net/manual/en/function.getenv.php'
     *
     ****************************************************************************/

    /**
     * @return the appID for this app
     */
    public static function appID() {
        return "1576733452586376";
    }

    /**
     * @return the appSecret for this app
     */
    public static function appSecret() {
        return "4d373ee524a8eeb295783912c1f010a3";
    }

    /**
     * @return the url
     */
    public static function redirectUrl() {
        return "https://fbapp.lh/";
    }

}
