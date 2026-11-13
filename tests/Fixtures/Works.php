<?php

namespace Base\Scholar\Tests\Fixtures;

use Omnischolar\Model\Contributor;
use Omnischolar\Model\Identifier;
use Omnischolar\Model\Identifiers;
use Omnischolar\Model\Venue;
use Omnischolar\Model\Work;
use Omnischolar\Model\WorkType;

/** Works as the sources give them: a 2024 article (OpenAlex + HAL), a book with two editions. */
final class Works
{
    public static function acid(?int $citations = 12, array $sources = ['openalex', 'hal']): Work
    {
        return new Work(
            title: 'Acid-sensitive pot glazes',
            type: WorkType::ARTICLE,
            authors: [
                new Contributor('Jeanne Exemple', 'Jeanne', 'Exemple'),
                new Contributor('Josiah Carberry', 'Josiah', 'Carberry', Identifiers::of(Identifier::of('orcid', '0000-0002-1825-0097'))),
            ],
            year: 2024,
            date: '2024-09-25',
            venue: new Venue('Journal of Psychoceramics', Venue::JOURNAL, ['0000-0000'], 'Exemple Press'),
            volume: '15',
            issue: '41',
            pages: '17060-17071',
            abstract: 'Glazes that answer to acid.',
            openAccess: true,
            pdfUrl: 'https://hal.science/hal-00000001/document',
            citations: $citations,
            identifiers: Identifiers::of(Identifier::parse('10.5555/12345678'), Identifier::of('hal', 'hal-00000001')),
            source: 'openalex',
            sources: $sources,
        );
    }

    public static function seeds(): Work
    {
        $edition2016 = new Work(title: 'Growing Algebra, PreK-2', type: WorkType::BOOK, authors: [Contributor::fromName('Jeanne Exemple')], year: 2016, publisher: 'Exemple Press', identifiers: Identifiers::of(Identifier::parse('9780000000002')), source: 'openlibrary', sources: ['openlibrary']);
        $edition2012 = new Work(title: 'Growing Algebra, PreK-2', type: WorkType::BOOK, authors: [Contributor::fromName('Jeanne Exemple')], year: 2012, publisher: 'Exemple Press', identifiers: Identifiers::of(Identifier::parse('9780000000019')), source: 'openlibrary', sources: ['openlibrary']);

        return $edition2016->with(['editions' => [$edition2016, $edition2012], 'cover' => 'https://covers.openlibrary.org/b/id/1-L.jpg']);
    }
}
