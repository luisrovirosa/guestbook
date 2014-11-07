<?php namespace GuestBook\Test\UseCase;

use GuestBook\Mock\Repository\MockEntryRepository;
use GuestBook\Mock\Request\MockCreateEntryRequest;
use GuestBook\Mock\Response\MockCreateEntryResponse;
use GuestBook\Mock\Validator\MockCreateEntryValidator;
use GuestBook\UseCase\CreateEntryUseCase;

class CreateEntryTest extends \PHPUnit_Framework_TestCase
{
    private $entryRepository;
    private $createEntryValidator;

    public function setUp()
    {
        $this->entryRepository      = new MockEntryRepository();
        $this->createEntryValidator = new MockCreateEntryValidator();
    }

    /**
     * @param $authorName
     * @param $authorEmail
     * @param $content
     *
     * @return MockCreateEntryResponse
     */
    private function executeUseCase($authorName, $authorEmail, $content){
        $request  = new MockCreateEntryRequest($authorName, $authorEmail, $content);
        $response = new MockCreateEntryResponse();
        $useCase  = new CreateEntryUseCase($this->entryRepository, $this->createEntryValidator);
        $useCase->process($request, $response);
        return $response;
    }
    public function failedRequests()
    {
        return array(
            'empty data'=> array('', '', '', array('Authors Name is empty', 'Authors E-Mail is empty', 'Content is empty'))
        );
    }

    public function testCanCreateEntry()
    {
        $response = $this->executeUseCase('Test', 'Test@foo.com', 'Hello World');
        $this->assertFalse($response->hasErrors());
    }

    /**
     * @param $authorName
     * @param $authorEmail
     * @param $content
     * @param $expectedErrors
     *
     * @dataProvider failedRequests
     */
    public function testFailCreateEntry($authorName, $authorEmail, $content, $expectedErrors)
    {
        $response = $this->executeUseCase($authorName,$authorEmail,$content);
        $this->assertTrue($response->hasErrors(),"UseCase has no error");
        $this->assertEquals($expectedErrors, $response->getErrors());
    }
} 