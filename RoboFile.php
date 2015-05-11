<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    /**
     * Runs a php server
     */
    public function server($opt = ['port' => 8000])
    {
        $this->taskServer($opt['port'])
            ->dir('public/ phpserver.php')
            ->run();
    }
}
