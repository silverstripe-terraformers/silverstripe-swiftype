<?php

namespace Ichaber\SSSwiftype\Tests\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Ichaber\SSSwiftype\Extensions\SwiftypeSiteConfigFieldsExtension;
use Ichaber\SSSwiftype\Service\SwiftypeCrawler;
use SilverStripe\Dev\SapphireTest;
use GuzzleHttp\Psr7\Response;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Class SwiftypeCrawlerTest
 *
 * @package Ichaber\SSSwiftype\Tests\Service
 */
class SwiftypeCrawlerTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'SwiftypeCrawlerTest.yml';

    /**
     * @var array
     */
    protected static $required_extensions = [
        SiteConfig::class => [
            SwiftypeSiteConfigFieldsExtension::class,
        ],
    ];

    /**
     * Test that a crawl will succeed
     */
    public function testCrawlSuccess(): void
    {
        $responseCode = 201;
        $mock = new MockHandler([
            new Response($responseCode),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $crawler = SwiftypeCrawler::create($client);

        // True represents a successful crawl request
        $this->assertTrue($crawler->send('https://www.someurl.com'));
    }

    /**
     * Test that a crawl will fail on invalid response code
     */
    public function testCrawlFailResponseCode(): void
    {
        $responseCode = 301;
        $mock = new MockHandler([
            new Response($responseCode),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $crawler = SwiftypeCrawler::create($client);

        $expectedMessage = sprintf(
            "Swiftype Crawl request failed - invalid response code \n%s\n%s\n%s",
            $responseCode,
            json_encode([]),
            ''
        );

        $this->assertFalse($crawler->send('https://www.someurl.com'));
        $this->assertEquals($expectedMessage, $crawler->getMessages()[0]);
    }

    /**
     * Test that a crawl will fail in invalid response data
     */
    public function testCrawlFailResponseData(): void
    {
        $responseCode = 200;
        $mockBody = json_encode([
            'error' => 'test error message',
        ]);
        $mock = new MockHandler([
            new Response($responseCode, [], $mockBody),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $crawler = SwiftypeCrawler::create($client);

        $expectedMessage = sprintf(
            "Swiftype Crawl request failed - invalid response data \n%s\n%s\n%s",
            $responseCode,
            json_encode([]),
            $mockBody
        );

        $this->assertFalse($crawler->send('https://www.someurl.com'));
        $this->assertEquals($expectedMessage, $crawler->getMessages()[0]);
    }

    /**
     * Test that a crawl will fail
     */
    public function testCrawlError(): void
    {
        $mock = new MockHandler([
            new RequestException("Error Communicating with Server", new Request('GET', 'test')),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $crawler = SwiftypeCrawler::create($client);

        $url = 'https://www.someurl.com';
        // Lets run it and get a not good response
        $expectedMessage = sprintf(
            'Exception %s for url: %s message: Error Communicating with Server',
            RequestException::class,
            $url
        );

        $this->assertFalse($crawler->send($url));
        $this->assertEquals($expectedMessage, $crawler->getMessages()[0]);
    }
}
