<?php
namespace Web\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrackControllerTest extends WebTestCase
{
    public function testTag()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/track/tag');
    }
}
