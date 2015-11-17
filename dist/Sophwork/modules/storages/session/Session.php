<?php

namespace Sophwork\modules\storages\session;

use Sophwork\app\app\SophworkApp;
use Sophwork\modules\ServiceProviders\ServiceProviderInterface\ServiceProviderInterface;

class Session implements ServiceProviderInterface
{
	/**
	 * Determine if the session is started
	 * @var boolean
	 */
	protected $started 	= false;

	/**
	 * Determine if the session is closed
	 * @var boolean
	 */
	protected $closed 	= true;

	/**
	 * Default PHP session options
	 * 
	 * session.save_path				"/tmp"													PHP_INI_ALL
	 * session.name						"PHPSESSID"												PHP_INI_ALL
	 * session.save_handler				"files"													PHP_INI_ALL
	 * session.auto_start				"0"														PHP_INI_ALL
	 * session.gc_probability			"1"														PHP_INI_ALL
	 * session.gc_divisor				"100"													PHP_INI_ALL
	 * session.gc_maxlifetime			"1440"													PHP_INI_ALL
	 * session.serialize_handler		"php"													PHP_INI_ALL
	 * session.cookie_lifetime			"0"														PHP_INI_ALL
	 * session.cookie_path				"/"														PHP_INI_ALL
	 * session.cookie_domain			""														PHP_INI_ALL
	 * session.cookie_secure			""														PHP_INI_ALL
	 * session.cookie_httponly			""														PHP_INI_ALL
	 * session.use_cookies				"1"														PHP_INI_ALL
	 * session.use_only_cookies			"0"														PHP_INI_ALL
	 * session.referer_check			""														PHP_INI_ALL
	 * session.entropy_file				""														PHP_INI_ALL
	 * session.entropy_length			"0"														PHP_INI_ALL
	 * session.cache_limiter			"nocache"												PHP_INI_ALL
	 * session.cache_expire				"180"													PHP_INI_ALL
	 * session.use_trans_sid			"0"														PHP_INI_SYSTEM/PHP_INI_PERDIR
	 * session.bug_compat_42			"1"														PHP_INI_ALL
	 * session.bug_compat_warn			"1"														PHP_INI_ALL
	 * session.hash_function			"0"														PHP_INI_ALL
	 * session.hash_bits_per_character	"4"														PHP_INI_ALL
	 * url_rewriter.tags				"a=href,area=href,frame=src,input=src,form=fakeentry"	PHP_INI_ALL
	 *
	 * session.upload_progress.enabled	"1"														PHP_INI_PERDIR	Available since PHP 5.4.0.
	 * session.upload_progress.cleanup	"1"														PHP_INI_PERDIR	Available since PHP 5.4.0.
	 * session.upload_progress.prefix	"upload_progress_"										PHP_INI_PERDIR	Available since PHP 5.4.0.
	 * session.upload_progress.name		"PHP_SESSION_UPLOAD_PROGRESS"							PHP_INI_PERDIR	Available since PHP 5.4.0.
	 * session.upload_progress.freq		"1%"													PHP_INI_PERDIR	Available since PHP 5.4.0.
	 * session.upload_progress.min_freq	"1"														PHP_INI_PERDIR
	 *
	 * @param array $options Session configuration options.
	 * 
	 */
	public function init (SophworkApp $app, Array $parameters = [])
	{
		session_cache_limiter('nocache'); // Default behaviour (No cache)
		session_name("SessionID");
		$this->setOptions($parameters);
		return 'session';
	}

	/**
	 * Session getter
	 * @param  string $param
	 * @return mixed
	 */
	public function get($param)
	{
		if ($this->isStarted()) {
			return $_SESSION[$param];
		}
	}

	public function getAll()
	{
		return $_SESSION;
	}

	/**
	 * Session setter
	 * @param string $param
	 * @param mixed $value
	 */
	public function set($param, $value)
	{
		if ($this->isStarted()) {
			$_SESSION[$param] = $value;
		}
	}

    public function setOptions(array $options)
    {
        $validOptions = array_flip(array(
			'save_path','name','save_handler',
			'auto_start','gc_probability','gc_divisor',
			'gc_maxlifetime','serialize_handler','cookie_lifetime',
			'cookie_path','cookie_domain','cookie_secure',
			'cookie_httponly','use_cookies','use_only_cookies',
			'referer_check','entropy_file','entropy_length',
			'cache_limiter','cache_expire','use_trans_sid',
			'bug_compat_42','bug_compat_warn','hash_function',
			'hash_bits_per_character',
			'url_rewriter.tags',
			'upload_progress.enabled','upload_progress.cleanup','upload_progress.prefix',
			'upload_progress.name','upload_progress.freq','upload_progress.min_freq',
        ));

        foreach ($options as $key => $value) {
            if (isset($validOptions[$key])) {
                ini_set('session.'.$key, $value);
            }
        }
    }

    public function getId()
    {
    	return session_id();
    }

    public function setId($value)
    {
    	session_id($value);
    }

    public function getName()
    {
    	return session_name();
    }

    public function setName($value)
    {
    	session_name($value);
    }

    private function start()
    {
        if ($this->started) {
            return true;
        }
        if (PHP_VERSION_ID >= 50400 && \PHP_SESSION_ACTIVE === session_status()) {
            throw new \RuntimeException('Failed to start the session: already started by PHP.');
        }
        if (PHP_VERSION_ID < 50400 && !$this->closed && isset($_SESSION) && session_id()) {
            // not 100% fool-proof, but is the most reliable way to determine if a session is active in PHP 5.3
            throw new \RuntimeException('Failed to start the session: already started by PHP ($_SESSION is set).');
        }
        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
        }
        // Actualy start the session
        if (!session_start()) {
            throw new \RuntimeException('Failed to start the session');
        }
        return true;
    }

    public function save()
    {
        session_write_close();
        $this->closed = true;
        $this->started = false;

    }

    public function startSession()
    {
		try {
			$this->started = $this->start();
		} catch (\Exception $e) {
			echo '<pre>', $e, '</pre>';
		}
    }

	public function isStarted()
    {
        return $this->started;
    }

	public function isClosed()
    {
        return $this->closed;
    }

	public function regenerate($destroy = false, $lifetime = null)
    {
        // Cannot regenerate the session ID for non-active sessions.
        if (PHP_VERSION_ID >= 50400 && \PHP_SESSION_ACTIVE !== session_status()) {
            return false;
        }
        // Check if session ID exists in PHP 5.3
        if (PHP_VERSION_ID < 50400 && '' === session_id()) {
            return false;
        }
        if (null !== $lifetime) {
            ini_set('session.cookie_lifetime', $lifetime);
        }
        $isRegenerated = session_regenerate_id($destroy);
        $this->startSession();
        return $isRegenerated;
    }

    public function clear()
    {
        $_SESSION = array();
    }
}