<?php
namespace Framework\Csrf;

use Symfony\Component\HttpFoundation\Session\Session;

class CsrfManager
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var int
     */
    private $expires;

    /**
     * @var string
     */
    private $current;

    /**
     * CsrfManager constructor.
     *
     * @param Session $session
     * @param string  $prefix
     * @param int     $expires
     */
    public function __construct(Session $session, $prefix = '_csrf', $expires = 600)
    {
        $this->session = $session;
        $this->prefix = $prefix;
        $this->expires = $expires;
    }

    /**
     * Clear expired tokens
     */
    public function clearExpired()
    {
        if ($this->session->has($this->prefix)) {
            $tokens = $this->session->get($this->prefix);
            $tokens = array_filter(
                $tokens, function ($token) {
                return time() - $token['generated'] < $this->expires;
            }
            );
            $this->session->set($this->prefix, $tokens);
        }
    }

    /**
     * @return array
     */
    public function generateToken()
    {
        $token = [
            'name'  => bin2hex(random_bytes(16)),
            'value' => bin2hex(random_bytes(16)),
        ];

        $tokens = $this->session->get($this->prefix, []);
        $tokens[$token['name']] = ['generated' => time(), 'value' => $token['value']];

        $this->session->set($this->prefix, $tokens);
        $this->current = $token;

        return $token;
    }

    /**
     * @return string
     */
    public function getTokenName()
    {
        if (!$this->current) {
            $this->generateToken();
        }
        return $this->current['name'];
    }

    /**
     * @return string
     */
    public function getTokenValue()
    {
        if (!$this->current) {
            $this->generateToken();
        }
        return $this->current['value'];
    }

    /**
     * @param $name
     */
    public function setCurrent($name)
    {
        $tokens = $this->session->get($this->prefix);
        if (!array_key_exists($name, $tokens)) {
            throw new \RuntimeException('Token does not exist.');
        }
        $this->current = $tokens[$name];
    }

    /**
     * @param string $name
     */
    public function expireToken($name)
    {
        if ($this->session->has($this->prefix)) {
            $tokens = $this->session->get($this->prefix);
            unset($tokens[$name]);
            $this->session->set($this->prefix, $tokens);
        }
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function validateToken($name, $value)
    {
        if (!$this->session->has($this->prefix)) {
            return false;
        }
        $tokens = $this->session->get($this->prefix);
        if (!array_key_exists($name, $tokens)) {
            return false;
        }
        if ($value !== $tokens[$name]['value']) {
            return false;
        }
        return true;
    }
}
