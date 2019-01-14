<?php

namespace app\core;

use app\App;

class Assets {

    protected $params;
    protected $web;
    protected $cache;

    public function __construct($params) {
        $this->params = $params;
        $this->web = __DIR__ . '/../../web';
        $this->cache = substr(md5(date($this->params['cache'])), 0, 8);
    }

    public function init() {
        $hash = $this->genHash();
        foreach (['css', 'js'] as $asset) {
            if ($hash) {
                if (!file_exists($this->web . '/assets/' . $this->cache . '/' . $asset)) {
                    mkdir($this->web . '/assets/' . $this->cache . '/' . $asset);
                }
            }
            foreach ($this->params[$asset] as $filename) {
                $path = $this->web . '/assets/' . $this->cache . '/' . $asset . '/' . basename($filename);
                $$asset[] = str_replace($this->web, '', $path);
                if ($hash) {
                    $data = file_get_contents('..' . $filename);
                    file_put_contents($path, $data);
                }
            }
        }
        
        return [
            'cache' => $this->cache,
            'css' => $css,
            'js' => $js,
        ];
    }

    static function getCss() {
        if ($css = App::$components['assets']['css']) {
            foreach ($css as $path) {
                echo '<link href="' . $path . '" rel="stylesheet" type="text/css" />' . "\n";
            }
        }
    }

    static function getJs() {
        if ($js = App::$components['assets']['js']) {
            foreach ($js as $path) {
                echo '<script src="' . $path . '" type="text/javascript"></script>' . "\n";
            }
        }
    }

    private function genHash() {
        if (!file_exists($this->web . '/assets')) {
            mkdir($this->web . '/assets');
        }
        if (!file_exists($this->web . '/assets/' . $this->cache)) {
            $this->clearCache();
            mkdir($this->web . '/assets/' . $this->cache);
            return true;
        } else {
            return false;
        }
    }

    private function clearCache() {
        foreach (scandir($this->web . '/assets/') as $folder) {
            if (substr($folder, 0, 1) != '.' && $folder != $this->cache) {
                $this->delTree($this->web . '/assets/' . $folder);
            }
        }
    }

    private function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

}
