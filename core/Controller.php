<?php
namespace core;

use \src\Config;

class Controller {

    protected function redirect($url) {
        header("Location: ".$this->getBaseUrl().$url);
        exit;
    }

    protected function getBaseUrl() {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':'.$_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;
        
        return $base;
    }

    private function _render($folder, $viewName, $viewData = []) {
        if(file_exists('../src/views/'.$folder.'/'.$viewName.'.php')) {
            extract($viewData);
            $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $baseUrl = $this->getBaseUrl();
            require '../src/views/'.$folder.'/'.$viewName.'.php';
        }
    }

    private function renderPartial($viewName, $viewData = []) {
        $this->_render('partials', $viewName, $viewData);
    }

    public function render($viewName, $viewData = []) {
        $this->_render('pages', $viewName, $viewData);
    }

    protected static $info = [
        "statusCode" => 200,
        "success" => false,
        "data" => [],
        "message" => ""
    ];

    static public function response($info = []){
        $info = array_merge(self::$info, $info);

        http_response_code($info["statusCode"]);

        $response = [
            "success" => $info["success"] ? "true" : "false",
        ];

        if(!$info["success"] && $info["message"]){
            $response["message"] = $info["message"];
        }

        if($info["data"] && $info["success"]){
            $response["data"] = $info["data"];
        }

        echo json_encode($response);
        exit;
    }
}