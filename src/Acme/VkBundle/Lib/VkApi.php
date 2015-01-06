<?php

namespace Acme\VkBundle\Lib;

use AppBundle\Lib\ConfigHelper;
use AppBundle\Patterns\Singleton;
use AppBundle\Lib\CurlHelper;

class VkApi extends Singleton
{
    private $token = '';

    private $lock;

    private $startTime;

    private $timeForRequest = 0;

    private $counter = 0;

    private $needNull = false;

    const URL_API = "https://api.vk.com/method/";

    const URL_OAUTH = "https://oauth.vk.com/authorize?client_id=%d&scope=%s&redirect_uri=%s&response_type=code&v=5.27";

    const URL_ACCESS_TOKEN = "https://oauth.vk.com/access_token?client_id=%d&client_secret=%s&code=%s&redirect_uri=%s";

    const PERMISSIONS = 4194304;

//    const PERMISSIONS = 2; //Друзья

    const PARAM_CODE = 'code';

    const REQUESTS_LIMIT = 2.7;

    /** сколько ждать при ошибки по лимиту запросов */
    const ERROR_WAIT_MS = 2000000;

    const ERROR_WAIT_PROGRESS_KOEF = 4;

    const EXECUTE_LIMIT = 25;


    /**
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    public static function getAuthUrl() {
        $appId = ConfigHelper::get('vk', 'app_id');
        $domain = ConfigHelper::get('app', 'domain');
        $callback = $domain['full'] . $domain['vk']['callback'];
        return sprintf(self::URL_OAUTH, $appId, self::PERMISSIONS, $callback);
    }

    /**
     * Получим от Контакта access_token
     *
     * @param $code
     * @return bool|mixed
     */
    public function getNewAccessToken($code) {
        $vkConf = ConfigHelper::get('vk');
        $appId = $vkConf['app_id'];
        $secret = $vkConf['secret'];
        $domain = ConfigHelper::get('app', 'domain');
        $callback = $domain['full'] . $domain['vk']['callback'];
        $url = sprintf(self::URL_ACCESS_TOKEN, $appId, $secret, $code, $callback);

        $result = $this->requestHttp($url);
        $json = json_decode($result, true);
        if (isset($json['error'])) {
            varlog("Vk Error:", $result);

            return false;
        }

        return $json;
    }

    /**
     * @param $id
     * @param Vk_FriendsCollection|null $clean
     * @return Vk_FriendsCollection
     */
    public function friendsGet($id, $clean = null) {
        if (!$this->startTime) {
            $this->startTime = time();
        }
        return new Vk_FriendsCollection(
            $this->requestApi(
                'friends.get',
                [
                    'user_id' => $id,
                    'access_token' => $this->token,
                ]
            ),
            $clean
        );
    }

    /**
     * @param $id
     * @param $limit
     * @return Vk_PostsCollection
     */
    public function wallGet($id, $limit){
        return new Vk_PostsCollection(
            $this->requestApi(
                'wall.get',
                [
                    'owner_id' => $id,
                    'count' => $limit,
                    'filter' => 'all',
                    'access_token' => $this->token,
                ]
            )
        );
    }

    public function executeWallGet(array $ids, $limit) {
        if (count($ids) > self::EXECUTE_LIMIT) {
            return false;
        }
        $codeAr = [];
        foreach ($ids as $id) {
            $codeAr[] =
                'API.wall.get({"owner_id":' . $id . ',"count":' . $limit . ',"filter":"all"})';
            //,"access_token":"'. $this->token . '"
        }
        $code = 'return [' . implode(",", $codeAr) . '];';

        $requestRes =  $this->requestApi(
            'execute',
            [
                'code' => $code,
                'access_token' => $this->token,
            ]
        );
        $result = [];
        $i = -1;
        //Соедимним результаты в единый массив
        foreach($requestRes as $wall) {
            $i++;
            if (!is_array($wall)) {
                //echo "-----------------------------------> ERROR! {$ids[$i]} \n";
                //var_dump($wall);
                //echo "\n\n";
                //die();
                continue;
            }
//            echo "Success --- {$wall[1]['to_id']} \n";
            $result = array_merge($result, $wall);
        }

        return new Vk_PostsCollection(
            $result
        );
    }

    private function requestApi($method, $params){
        $url = self::URL_API . $method . "?" . http_build_query($params);
        $repeat = false;
        $koef = 1;
        do {
            if ($this->needNull) {
                $this->counter = 1;
            } else {
                $this->counter++;
            }
            $result = $this->requestHttp($url);
            $json = json_decode($result, true);
            if (isset($json['error']['error_code']) && $json['error']['error_code'] == 6) {
                $repeat = true;
                $sec = time() - $this->startTime;
                echo "<br>Error in {$sec}  seconds! Count: {$this->counter} \n {$result} \n";

                usleep(self::ERROR_WAIT_MS * $koef);
                //Время должно расти прогрессивно
                $koef *= self::ERROR_WAIT_PROGRESS_KOEF;
            }
        } while ($repeat);
        if (isset($json['error']['error_code']) && isset($json['error']['error_msg'])) {
            echo $json['error']['error_msg'] . "\n";
        }

        return isset($json['response']) ? $json['response'] : false;
    }

    /**
     * Ждем пока снова можно будет совершать запрос!
     *
     * @return bool
     */
    private function waitLock() {
        $time = microtime(true);
        if(!$this->lock) {
            $this->timeForRequest = 1 / self::REQUESTS_LIMIT;
        } elseif ($time <= $this->lock + $this->timeForRequest) {
            $sleepTime = ceil(($this->lock + $this->timeForRequest - $time) * 1000000);
            usleep($sleepTime);
        }

        $this->lock = microtime(true);

        return true;
    }

    private function requestHttp($url){
        if (!$this->waitLock()) {
            return false;
        }

        return CurlHelper::get($url);
        //return file_get_contents($url);
    }
}