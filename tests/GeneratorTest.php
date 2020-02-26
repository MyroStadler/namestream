<?php


namespace Myro\NameStream\Tests;


use Myro\NameStream\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testGeneratesWord() {
        $g = new Generator();
        $word = $g->generate();
        $this->assertNotEmpty($word);
    }

    public function testExamples() {
        $g = new Generator();
        for ($i = 0; $i < 100; $i++) {
            printf("\n%s %s", $g->generate(), $g->generate());
        }
        $this->assertTrue(true);
    }

}