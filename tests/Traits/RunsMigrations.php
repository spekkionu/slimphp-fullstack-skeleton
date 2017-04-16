<?php

namespace Test\Traits;

use Illuminate\Database\Capsule\Manager;
use PDO;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Tester\CommandTester;

trait RunsMigrations
{
    /**
     * @var PDO
     */
    protected $dbh;

    /**
     * Connects to the database and runs migrations
     *
     * @before
     */
    public function createDatabase()
    {
        $this->dbh = app('PDO');
        $this->dropTables();
        $this->runMigrations();
    }

    /**
     * Drops tables and closes connection
     *
     * @after
     */
    public function closeDatabase()
    {
        $this->dropTables();
        $this->dbh = null;
    }

    /**
     * Drops database tables
     */
    public function dropTables()
    {
        if (getenv('DATABASE_DRIVER') === 'mysql') {
            $this->dropMysqlTables();
        }
        if (getenv('DATABASE_DRIVER') === 'sqlite' && getenv('DATABASE_DBNAME') !== ':memory:') {
            $this->dropSqliteTables();
        }
    }

    /**
     * Runs database migrations
     */
    public function runMigrations()
    {
        $phinx         = new PhinxApplication();
        $command       = $phinx->find('migrate');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command'       => $command->getName(),
            '--environment' => 'unit',
        ]);
    }

    /**
     * Drops all tables
     */
    protected function dropSqliteTables()
    {
        $sth    = $this->dbh->query("SELECT `name` FROM sqlite_master WHERE `type`='table'");
        $tables = $sth->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $this->dbh->exec("DELETE FROM `{$table}`");
        }
    }

    /**
     * Drops all tables
     */
    protected function dropMysqlTables()
    {
        $this->dbh->exec('SET foreign_key_checks = 0');
        $sth    = $this->dbh->query('SHOW TABLES');
        $tables = [];
        while ($table = $sth->fetchColumn()) {
            $tables[] = "`{$table}`";
        }
        if ($tables) {
            $tables = implode(',', $tables);
            $this->dbh->exec("DROP TABLE IF EXISTS {$tables}");
        }

        $this->dbh->exec('SET foreign_key_checks = 1');
    }
}
