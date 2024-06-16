<?php

namespace App\Model;

class Teacher
{
    public function __construct(
        private int $id,
        private string $name,
        private string $class,
        private TeacherStatusEnum $status,
    ) {
    }

    // ... lines 7 - 39

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of class.
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the value of class.
     *
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the value of status.
     */
    public function getStatus(): TeacherStatusEnum
    {
        return $this->status;
    }

    /**
     * Get the value of status.
     */
    public function getStatusString(): string
    {
        return $this->status->value;
    }

    /**
     * Set the value of status.
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
