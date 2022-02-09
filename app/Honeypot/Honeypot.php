<?php

/**
 * File doc comment
 * 
 * @category Spam
 * @package  Honeypot
 * @author   Name <email@email.com>
 * @license  MIT krsp.co
 * @link     http://url.com
 */

namespace App\Honeypot;

use Illuminate\Http\Request;

/**
 * Honeypot
 * 
 * @category Spam
 * @package  Honeypot
 * @author   KRSP <agency@krsp.co>
 * @license  MIT http://url.com
 * @link     http://url.com
 */
class Honeypot
{
    protected $config;
    protected $request;
    protected static $response;

    /**
     * Constructor
     * 
     * @param Request $request The request object.
     * @param array   $config  Cofigurations.
     */
    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * Detect Spam
     * 
     * @return boolean
     */
    public function detect()
    {
        if (!$this->config['enabled']) {
            return false;
        }

        if ($this->submissionTime() <= $this->config['honeypot_limit']) {
            return $this->abort();
        }


        return false;
    }

    /**
     * Abort
     * 
     * @return void
     */
    public function abort()
    {
        if (static::$response) {
            return call_user_func(static::$response);
        }
        abort(422, 'SPAM');
    }

    /**
     * Submission time in microsecs
     * 
     * @return microtime
     */
    protected function submissionTime()
    {
        return microtime(true) - $this->request->input($this->config['honeypot_time']);
    }

    /**
     * Abort using method
     * 
     * @param callable $response The response callable function
     * 
     * @return void
     */
    public static function abortUsing(callable $response)
    {
        static::$response = $response;
    }
}
