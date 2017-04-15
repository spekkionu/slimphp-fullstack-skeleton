<?php

class ApplicationExtension extends \Codeception\Extension
{

    protected $verbose = false;

    // list events to listen to
    public static $events
        = [
            'suite.before' => 'beforeSuite',
            'test.before'  => 'beforeTest',
            'test.after'   => 'afterTest',
        ];

    public function beforeSuite(\Codeception\Event\SuiteEvent $e)
    {
        $settings = $e->getSettings();
        if (isset($settings['extensions']['config']['ApplicationExtension']['verbose']) && $settings['extensions']['config']['ApplicationExtension']['verbose']) {
            $this->verbose = true;
        }
    }

    public function beforeTest(\Codeception\Event\TestEvent $e)
    {
        $this->clearCache();
        $this->clearSession();
    }

    public function afterTest(\Codeception\Event\TestEvent $e)
    {
        $this->clearCache();
        $this->clearSession();
    }

    protected function clearCache()
    {
        if ($this->verbose) {
            $this->writeln('Clearing cache');
        }

        app('Doctrine\Common\Cache\Cache')->deleteAll();
    }

    protected function clearSession()
    {
        if ($this->verbose) {
            $this->writeln('Clearing session');
        }
        session()->clear();
    }
}
