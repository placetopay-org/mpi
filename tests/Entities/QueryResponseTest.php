<?php


class QueryResponseTest extends BaseTestCase
{

    public function testItParsesASuccessfulRequest()
    {
        $data = $this->unserialize('YTo1OntzOjIxOiJhdXRoZW50aWNhdGlvbl9zdGF0dXMiO3M6MToiWSI7czoxOToidmFsaWRhdGVkX3NpZ25hdHVyZSI7YjoxO3M6MzoiZWNpIjtzOjI6IjA1IjtzOjQ6ImNhdnYiO3M6Mjg6IkFBQUNDWkppVUdWbEY0VTVBbUpRRXdBQUFBQT0iO3M6MzoieGlkIjtzOjI4OiJaOFV1SFlGOEVwejQ2TThWL01rR0pEbDJZNUU9Ijt9');
        $response = \PlacetoPay\MPI\Messages\QueryResponse::loadFromResult($data);

        $this->assertTrue($response->isAuthenticated());
        $this->assertTrue($response->validSignature());
        $this->assertEquals('05', $response->eci());
        $this->assertEquals('AAACCZJiUGVlF4U5AmJQEwAAAAA=', $response->cavv());
        $this->assertEquals('Z8UuHYF8Epz46M8V/MkGJDl2Y5E=', $response->xid());
    }

    public function testItParsesANonValidatedSignature()
    {
        $data = $this->unserialize('YTo1OntzOjIxOiJhdXRoZW50aWNhdGlvbl9zdGF0dXMiO3M6MToiWSI7czoxOToidmFsaWRhdGVkX3NpZ25hdHVyZSI7YjowO3M6MzoiZWNpIjtzOjI6IjA1IjtzOjQ6ImNhdnYiO3M6Mjg6IkFBQUNBMWFUV1VoWWN4ZUdnNU5aRUFBQUVBQT0iO3M6MzoieGlkIjtzOjI4OiJVYlJybERBUlRYRlQ4R1ZBTGlnRjRNRHloa2s9Ijt9');
        $response = \PlacetoPay\MPI\Messages\QueryResponse::loadFromResult($data);

        $this->assertFalse($response->isAuthenticated());
        $this->assertFalse($response->validSignature());
        $this->assertEquals('05', $response->eci());
        $this->assertEquals('AAACA1aTWUhYcxeGg5NZEAAAEAA=', $response->cavv());
        $this->assertEquals('UbRrlDARTXFT8GVALigF4MDyhkk=', $response->xid());
    }
}