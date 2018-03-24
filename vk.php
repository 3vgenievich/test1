<?php

class Utils {
    public static function redirect($uri = '') {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$uri, TRUE, 302);
        exit;
    }
}

class OAuthVK {

    const APP_ID = 6379963; //ID приложения
    const APP_SECRET = 'jW97RHOk4MQcFh14f2mC'; //Защищенный ключ
    const URL_CALLBACK = 'http://localhost/laterpost/oauth/vk.php'; //URL сайта до этого скрипта-обработчика 
    const URL_ACCESS_TOKEN = 'https://oauth.vk.com/access_token';
    const URL_AUTHORIZE = 'https://oauth.vk.com/authorize';
    const URL_GET_PROFILES = 'https://api.vk.com/method/getProfiles';

    private static $token;
    public static $userId;
    public static $userData;

    private static function printError($error) {
        echo '#' . $error->error_code . ' - ' . $error->error_msg;
    }

    /**
     * @url https://vk.com/dev/auth_sites
     */
    public static function goToAuth()
    {
        Utils::redirect(self::URL_AUTHORIZE .
            '?client_id=' . self::APP_ID .
            '&scope=offline' .
            '&redirect_uri=' . urlencode(self::URL_CALLBACK) .
            '&response_type=code&v=5.73');
    }

    public static function getToken($code) {
        $url = self::URL_ACCESS_TOKEN .
            '?client_id=' . self::APP_ID .
            '&client_secret=' . self::APP_SECRET .
            '&code=' . $_GET['code'] .
            '&redirect_uri=' . urlencode(self::URL_CALLBACK).
            '&v=5.73';

        if (!($res = @file_get_contents($url))) {
            return false;
        }

        $res = json_decode($res);
        if (empty($res->access_token) || empty($res->user_id)) {
            return false;
        }

        self::$token = $res->access_token;
        self::$userId = $res->user_id;

        return true;
    }

    /**
     * Если данных недостаточно, то посмотрите что можно ещё запросить по этой ссылке
     * @url https://vk.com/pages.php?o=-1&p=getProfiles
     */
    public static function getUser() {

        if (!self::$userId) {
            return false;
        }

        $url = self::URL_GET_PROFILES.
            '?v=5.73'.
            '?uid=' . self::$userId .
            '&access_token=' . self::$token;

        if (!($res = @file_get_contents($url))) {
            return false;
        }

        $user = json_decode($res);

        if (!empty($user->error)) {
            self::printError($user->error);
            return false;
        }

        if (empty($user->response[0])) {
            return false;
        }

        $user = $user->response[0];
        if (empty($user->uid) || empty($user->first_name) || empty($user->last_name)) {
            return false;
        }

        return self::$userData = $user;
    }
}

// Пример использования класса:
if (!empty($_GET['error'])) {
    // Пришёл ответ с ошибкой. Например, юзер отменил авторизацию.
    die($_GET['error']);
} elseif (empty($_GET['code'])) {
    // Самый первый запрос
    OAuthVK::goToAuth();
} else {
    // Пришёл ответ без ошибок после запроса авторизации
    if (!OAuthVK::getToken($_GET['code'])) {
        die('Error - no token by code');
    }
    /*
     * На данном этапе можно проверить зарегистрирован ли у вас ВК-юзер с id = OAuthVK::$userId
     * Если да, то можно просто авторизовать его и не запрашивать его данные.
     */

    $user = OAuthVK::getUser();
    print_r($user);
    echo ($user);
    /*
     * Вот и всё - мы узнали основные данные авторизованного юзера.
     * $user в этом примере состоит из трёх полей: uid, first_name, last_name.
     * Делайте с ними что угодно - регистрируйте, авторизуйте, ругайте...
     */
}

