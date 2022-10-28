<?php


namespace Myro\NameStream\Tests;


use Myro\NameStream\NameGenerator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testGeneratesWord() {
        $g = new NameGenerator();
        $word = $g->g();
        $this->assertNotEmpty($word);
    }

    public function testExamples() {
        $g = new NameGenerator();
        for ($i = 0; $i < 1000; $i++) {
            printf("\n%s %s", $g->g(), $g->g());
        }
        $this->assertTrue(true);
    }

}