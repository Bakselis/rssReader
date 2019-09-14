<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../models/feeds/rssFeed.php');

// simple test just to check if correct data is equeal to test data
final class rssFeedTest extends TestCase
{
    public function testOutputOfRssFeedIsCorrect(): void
    {
        $correct_result = [ "title" => "Test article",
                            "link" => "http://test.com",
                            "description" => "Test",
                            "image" => "",
                            "date" => "2019-09-13 20:20:24"
                          ];

        $result = new RssFeed(__DIR__ ."/test.xml");
        $result = $result->getAllArray()[0];

        $this->assertEquals(
            $correct_result,
            $result
        );
    }
}
