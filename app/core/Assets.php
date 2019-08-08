<?php

namespace app\core;

use app\App;

/**
 * Class Assets
 *
 * @package app\core
 */
class Assets {

    /**
     * App params
     *
     * @var array
     */
    protected $params;

    /**
     * Path to web root
     *
     * @var string
     */
    protected $web;

    /**
     * Cache
     *
     * @var bool|string
     */
    protected $cache;

    /**
     * Assets constructor
     *
     * @param $params
     */
    public function __construct($params) {
        $this->params = $params;
        $this->web = __DIR__ . '/../../web';
        $this->cache = substr(md5(date($this->params['cache'])), 0, 8);
    }

    /**
     * App initialisation
     *
     * @return array
     */
    public function init() {
        $hash = $this->genHash();
        $css = [];
        $js = [];
        foreach (['css', 'js'] as $asset) {
            if ($hash) {
                if (!file_exists($this->web . '/assets/' . $this->cache . '/' . $asset)) {
                    mkdir($this->web . '/assets/' . $this->cache . '/' . $asset);
                }
            }
            foreach ($this->params[$asset] as $filename) {
                $path = $this->web . '/assets/' . $this->cache . '/' . $asset . '/' . basename($filename);
                ${$asset}[] = str_replace($this->web, '', $path);
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

    /**
     * Render CSS tag <link/>
     */
    static function getCss() {
        if ($css = App::$components['assets']['css']) {
            foreach ($css as $path) {
                echo '<link href="' . $path . '" rel="stylesheet" type="text/css" />' . "\n";
            }
        }
    }

    /**
     * Render JS tag <script/>
     */
    static function getJs() {
        if ($js = App::$components['assets']['js']) {
            foreach ($js as $path) {
                echo '<script src="' . $path . '" type="text/javascript"></script>' . "\n";
            }
        }
    }

    /**
     * Generate cache hash
     *
     * @return bool
     */
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

    /**
     * Clear cache
     */
    private function clearCache() {
        foreach (scandir($this->web . '/assets/') as $folder) {
            if (substr($folder, 0, 1) != '.' && $folder != $this->cache) {
                $this->delTree($this->web . '/assets/' . $folder);
            }
        }
    }

    /**
     * Remove cache folders and files
     *
     * @param $dir
     *
     * @return bool
     */
    private function delTree($dir) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

}
