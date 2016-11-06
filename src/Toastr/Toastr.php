<?php

namespace Igorwanbarros\BaseLaravel\Toastr;

use Illuminate\Session\SessionManager as Session;
use Illuminate\Config\Repository as Config;

class Toastr
{
    /**
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var array
     */
    protected $messages = [];


    function __construct(Session $session, Config $config)
    {
        $this->session = $session;
        $this->config  = $config;
    }


    public function message()
    {
        $messages = $this->session->get('toastr::messages');

        if (! $messages) $messages = [];

        $script = '<script type="text/javascript">';

        foreach ($messages as $message) {
           $config = (array) $this->config->get('toastr.options');

           if (count($message['options'])) {
               $config = array_merge($config, $message['options']);
           }

           if ($config) {
               $script .= 'toastr.options = ' . json_encode($config) . ';';
           }

           $title = $message['title'] ?: null;

            $script .= 'toastr.' . $message['type'] .
                '(\'' . $message['message'] .
                "','$title" .
                '\');';
        }

        $script .= '</script>';
        $this->session->set('toastr::messages', []);

        return $script;
    }


    /**
     * @param string $type
     * @param string $message
     * @param string $title
     * @param array  $options
     *
     * @throws \Exception
     * @return $this
     */
    public function add($type, $message, $title = null, $options = [])
    {
        $types = [
            'error',
            'info',
            'success',
            'warning',
        ];

        if (! in_array($type, $types)) {
            throw new \Exception("The $type remind message is not valid.");
        }

        $this->messages[] = [
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'options' => $options,
        ];

        $this->session->flash('toastr::messages', $this->messages);

        return $this;
    }


    /**
     * @param string $message
     * @param string $title
     * @param array  $options
     *
     * @return $this
     */
    public function info($message, $title = null, $options = [])
    {
        return $this->add('info', $message, $title, $options);
    }


    /**
     * Add a success flash message to session.
     *
     * @param string $message
     * @param string $title
     * @param array  $options
     *
     * @return $this
     */
    public function success($message, $title = null, $options = [])
    {
        return $this->add('success', $message, $title, $options);
    }


    /**
     * @param string $message
     * @param string $title
     * @param array  $options
     *
     * @return $this
     */
    public function warning($message, $title = null, $options = [])
    {
        return $this->add('warning', $message, $title, $options);
    }


    /**
     * @param string $message
     * @param string $title
     * @param array  $options
     *
     * @return $this
     */
    public function error($message, $title = null, $options = [])
    {
        return $this->add('error', $message, $title, $options);
    }


    /**
     * @return $this
     */
    public function clear()
    {
        $this->messages = [];

        return $this;
    }
}
