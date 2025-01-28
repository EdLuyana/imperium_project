<?php

namespace App\Services;

use PHPUnit\Framework\TestCase;

class FileNameUniqidTest extends TestCase
{
    public function testFileIsRenamedWithUniqid()
    {
        $extension = 'jpg';

        $newFileName1 = uniqid() . '.' . $extension;
        $newFileName2 = uniqid() . '.' . $extension;

        $this->assertNotEmpty($newFileName1);
        $this->assertNotEmpty($newFileName2);

        $this->assertNotEquals($newFileName1, $newFileName2);
        
        $this->assertMatchesRegularExpression('/^[a-z0-9]{13}\.jpg$/', $newFileName1);
        $this->assertMatchesRegularExpression('/^[a-z0-9]{13}\.jpg$/', $newFileName2);
    }
}