<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/6/29
 * Time: 0:02
 */

namespace experuse_interface\use_interface;


class CommandOutputDocument implements Documentable
{
    protected $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->command;
    }

    public function getContent()
    {
        // TODO: Implement getContent() method.
        return shell_exec($this->command);
    }
}