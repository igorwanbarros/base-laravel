<?php

namespace Igorwanbarros\BaseLaravel\Widgets;

use Igorwanbarros\Php2Html\TagHtml;

/**
 * Class Assets
 *
 * @package Igorwanbarros\BaseLaravel\Widgets
 *
 * @method bool addScript(mixed $assets)
 * @method bool addStyle(mixed $assets)
 * @method bool addStyleInline(mixed $assets)
 * @method bool addScriptInline(mixed $assets)
 */
class Assets
{
    const TYPE_SCRIPT = 'script';

    const TYPE_STYLE = 'style';

    protected $lists = [];

    protected $history = [
        self::TYPE_SCRIPT => [],
        self::TYPE_STYLE => [],
    ];


    public function __construct()
    {
        $this->_initAssets();
    }


    public function getLists()
    {
        return $this->lists;
    }


    public function addInline($type, $asset)
    {
        if (!is_array($asset)) {
            $asset = ['inline' => "\n" . $asset];
        }

        return $this->add($type, $asset);
    }


    public function add($type, $asset)
    {
        if (!array_key_exists($type = strtolower($type), $this->lists)) {
            return false;
        }

        if ($type == static::TYPE_SCRIPT && !is_array($asset)) {
            $asset = ['src' => $asset];
            $all = array_column($this->lists[$type], 'src');

            if (array_search($asset['src'], $all) !== false) {
                return true;
            }
        }

        if ($type == static::TYPE_STYLE && !is_array($asset)) {
            $asset = ['href' => $asset];
            $all = array_column($this->lists[$type], 'href');

            if (array_search($asset['href'], $all) !== false) {
                return true;
            }
        }

        $this->lists[$type][] = $asset;

        return true;
    }


    public function __call($name, $arguments)
    {
        if (strrpos($name, 'Inline') !== false && array_key_exists('0', $arguments)) {
            return $this->addInline(
                substr($name, 3, -6),
                $arguments[0]
            );
        }

        if (strpos($name, 'add') !== false && array_key_exists('0', $arguments)) {
            return $this->add(
                substr($name, 3),
                $arguments[0]
            );
        }
    }


    public function renderStyles($clearHistory = true)
    {
        return $this->_renderStyles($clearHistory);
    }


    public function renderScripts($clearHistory = true)
    {
        return $this->_renderScripts($clearHistory);
    }


    public function __toString()
    {
        $assets = '';

        $assets .= $this->_renderStyles();
        $assets .= $this->_renderScripts();

        $this->_initAssets();

        return $assets;
    }


    protected function _initAssets()
    {
        return $this->lists = [
            static::TYPE_SCRIPT => [],
            static::TYPE_STYLE => [],
        ];
    }


    /**
     * @return string
     */
    protected function _renderStyles()
    {
        $assets = '';

        foreach ($this->lists[static::TYPE_STYLE] as $type => $attributes) {
            $inline = '';
            $tag = 'link';

            if (array_key_exists('inline', $attributes)) {
                $inline = $attributes['inline'];
                $tag = 'style';
                unset($attributes['inline']);
            }

            if ($tag == 'link' && !array_key_exists('rel', $attributes)) {
                $attributes['rel'] = 'stylesheet';
            }

            if ($tag == 'link' && !array_key_exists('media', $attributes)) {
                $attributes['media'] = 'all';
            }

            $assets .= TagHtml::source($tag, $inline, $attributes);
        }

        return $assets;
    }


    /**
     * @return string
     */
    protected function _renderScripts()
    {
        $assets = '';

        foreach ($this->lists[static::TYPE_SCRIPT] as $type => $attributes) {
            if (!array_key_exists('type', $attributes)) {
                $attributes['type'] = 'text/javascript';
            }

            $inline = '';

            if (array_key_exists('inline', $attributes)) {
                $inline = $attributes['inline'];
                unset($attributes['inline']);
            }

            $assets .= TagHtml::source('script', $inline, $attributes);
        }

        return $assets;
    }

}
