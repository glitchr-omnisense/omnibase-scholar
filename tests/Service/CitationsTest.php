<?php

namespace Base\Scholar\Tests\Service;

use Base\Scholar\Entity\Publication;
use Base\Scholar\Entity\Scholar;
use Base\Scholar\Service\Citations;
use Base\Scholar\Service\JsonLd;
use Base\Scholar\Tests\Fixtures\Works;
use PHPUnit\Framework\TestCase;

final class CitationsTest extends TestCase
{
    public function testTheReferenceLine(): void
    {
        $publication = new Publication(new Scholar('Josiah Carberry'), Works::acid());

        $this->assertSame('Exemple, J., Carberry, J. (2024). Acid-sensitive pot glazes. Journal of Psychoceramics, 15(41), 17060–17071. https://doi.org/10.5555/12345678', (new Citations())->reference($publication));
    }

    public function testTheExportsCarryTheSitesOverrides(): void
    {
        $publication = (new Publication(new Scholar('Josiah Carberry'), Works::acid()))->setPublisherUrl('https://journal.example/psychoceramics/12345678');
        $citations = new Citations();

        $bibtex = $citations->export('bibtex', $publication);
        $this->assertStringStartsWith('@article{exemple2024acid,', $bibtex);
        $this->assertStringContainsString('doi = {10.5555/12345678}', $bibtex);
        $this->assertStringContainsString('journal.example', $bibtex);
        $this->assertStringContainsString("TY  - JOUR\r\n", $citations->export('ris', [$publication]));
        $this->assertSame('bibtex', Citations::format('bib'));
        $this->assertNull(Citations::format('pdf'));
    }

    public function testTheJsonLd(): void
    {
        $scholar = (new Scholar('Josiah Carberry'))->setFamilyName('Carberry')->setOrcid('https://orcid.org/0000-0002-1825-0097')
            ->setProfilesText("openalex: A5000000001\nhal: josiah-carberry\nhal: Josiah Carberry")->setAffiliation('Brown University');
        $jsonLd = new JsonLd();

        $person = $jsonLd->person($scholar, 'https://example.org/cv');
        $this->assertSame('Person', $person['@type']);
        $this->assertSame(['https://orcid.org/0000-0002-1825-0097', 'https://openalex.org/A5000000001', 'https://cv.hal.science/josiah-carberry'], $person['sameAs']);
        $this->assertSame(['openalex' => ['A5000000001'], 'hal' => ['josiah-carberry', 'Josiah Carberry']], $scholar->getProfiles());

        $article = $jsonLd->publication(new Publication($scholar, Works::acid()), null, $scholar);
        $this->assertSame('ScholarlyArticle', $article['@type']);
        $this->assertSame('Periodical', $article['isPartOf']['@type']);
        $this->assertSame('https://orcid.org/0000-0002-1825-0097', $article['author'][1]['sameAs']);

        $book = $jsonLd->publication(new Publication($scholar, Works::seeds()));
        $this->assertSame('Book', $book['@type']);
        $this->assertSame(['9780000000002', '9780000000019'], $book['isbn']);
    }
}
