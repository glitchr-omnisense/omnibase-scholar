<?php

namespace Base\Scholar\Tests\Service;

use Base\Scholar\Service\WorkData;
use Base\Scholar\Tests\Fixtures\Works;
use PHPUnit\Framework\TestCase;

final class WorkDataTest extends TestCase
{
    public function testAWorkComesBackWhole(): void
    {
        $work = Works::acid();
        $back = WorkData::fromArray(json_decode(json_encode(WorkData::toArray($work)), true));

        $this->assertEquals($work, $back);
        $this->assertSame('0000-0002-1825-0097', $back->authors[1]->orcid());
        $this->assertSame('Journal of Psychoceramics', $back->venue->name);
    }

    public function testABooksEditionsComeBackAndNameIt(): void
    {
        $book = WorkData::fromArray(WorkData::toArray(Works::seeds()));

        $this->assertCount(2, $book->editions);
        $this->assertSame(['isbn:9780000000002', 'isbn:9780000000019'], WorkData::keys($book));
        $this->assertSame('growing algebra prek 2|2016', WorkData::fingerprint($book));
    }

    public function testTheAuthorsLine(): void
    {
        $this->assertSame('Jeanne Exemple, Josiah Carberry', WorkData::authorsLine(Works::acid()));
        $this->assertSame('Jeanne Exemple et al.', WorkData::authorsLine(Works::acid(), 1));
    }
}
