<?php

class User
{
    private $name;
    private $age;

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        if ($this->checkAge($age)) {
            $this->age = $age;
        } else {
            echo 'rfk';
        }

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    private function checkAge($age)
    {
        if ($age > 17) {
            return true;
        } else {
            return false;
        }
    }
}

$stas = new User();
$stas->setAge(66);
$stas->setName('Stas');
echo $stas->getName();