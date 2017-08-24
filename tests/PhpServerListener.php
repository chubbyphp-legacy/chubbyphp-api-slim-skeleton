<?php

namespace Chubbyphp\Tests\ApiSkeleton;

use PHPUnit\Framework\BaseTestListener;
use PHPUnit\Framework\TestSuite;

class PhpServerListener extends BaseTestListener
{
    const PHP_SERVER_PORT = 8123;
    const PHP_SERVER_LOGFILE = 'php-test-server.log';
    const PHP_SERVER_PIDFILE = 'php-test-server.pid';

    /**
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite)
    {
        if (false === strpos($suite->getName(), 'Integration')) {
            return;
        }

        $this->killPhpServer();

        exec(sprintf(
            '%s > %s 2>&1 & echo $! >> %s',
            $this->getCommand(),
            $this->getLogFile(),
            $this->getPidFile()
        ));

        usleep(100000);
    }

    /**
     * @param TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite)
    {
        if (false === strpos($suite->getName(), 'Integration')) {
            return;
        }

        $this->killPhpServer();
    }

    /**
     * @return string
     */
    private function getCommand(): string
    {
        return sprintf(
            'php -S localhost:%d -t %s %s',
            self::PHP_SERVER_PORT,
            __DIR__.'/../public',
            __DIR__.'/../public/index_test.php'
        );
    }

    private function killPhpServer()
    {
        if (!is_file(self::PHP_SERVER_PIDFILE)) {
            return;
        }

        $pid = (int) file_get_contents($this->getPidFile());

        exec(sprintf('/bin/kill %d', $pid));

        unlink($this->getPidFile());
        unlink($this->getLogFile());
    }

    /**
     * @return string
     */
    private function getPidFile(): string
    {
        return sys_get_temp_dir().'/'.self::PHP_SERVER_PIDFILE;
    }

    /**
     * @return string
     */
    private function getLogFile(): string
    {
        return sys_get_temp_dir().'/'.self::PHP_SERVER_LOGFILE;
    }
}
