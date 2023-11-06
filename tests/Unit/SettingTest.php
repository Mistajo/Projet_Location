<?php

namespace App\Tests\Unit;


use App\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SettingTest extends KernelTestCase
{
    public function getEntity(): Setting
    {

        return (new Setting())
            ->setEmail('test@test.com')
            ->setPhone('0123456789')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function assertHasErrors(Setting $setting, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($setting);
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity(): void
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidEmail(): void
    {
        $this->assertHasErrors($this->getEntity()->setEmail('test'), 1);
    }

    public function testInvalidPhone(): void
    {

        $this->assertHasErrors($this->getEntity()->setPhone('**'), 1);
    }
}