<?php

namespace Tests\AppBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;

/**
 * tests de l'entity User
 */
class TaskTest extends WebTestCase
{

    /**
     * vérifie l'instanciation de l'objet task
     */
    public function testInstanciationTask()
    {
        $task = new Task();

        $this->assertSame(null, $task->getContent()); // attendu que task crée soit vide
    }

    /**
     * vérifie que l'état task done / undone remonte correctement
     */
    public function testTaskUndone()
    {
        $task = new Task();
        $task->setIsDone(false);

        $this->assertSame(false, $task->getIsDone());
    }

    /**
     * vérifie que l'état task done / undone remonte correctement
     */
    public function testTaskDone()
    {
        $task = new Task();
        $task->setIsDone(true);

        $this->assertSame(true, $task->getIsDone());
    }
}
