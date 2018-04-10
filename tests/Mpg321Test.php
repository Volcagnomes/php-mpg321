<?php

use Volcagnomes\PHPMpg321\Mpg321;
use Volcagnomes\PHPMpg321\Exceptions\InvalidParameterException;
use PHPUnit\Framework\TestCase;

require_once 'src/Mpg321.php';

/**
 * Mpg321 test case.
 */
class Mpg321Test extends TestCase
{
    /**
     * @var Mpg321
     */
    private $mpg321;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated Mpg321Test::setUp()

        $this->mpg321 = new Mpg321(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated Mpg321Test::tearDown()
        $this->mpg321 = null;

        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Tests Mpg321->setPlayMode().
     *
     * @dataProvider playModesProvider
     */
    public function testSetPlayMode($mode, $expected)
    {
        $test = $this->mpg321->setPlayMode($mode);
        $this->assertInstanceOf(Mpg321::class, $test);
    }

    /**
     * Tests Mpg321->setPlayMode() for exception.
     */
    public function testSetPlayModeException()
    {
        $this->expectException(InvalidParameterException::class);
        $test = $this->mpg321->setPlayMode('not valid');
    }

    /**
     * Tests Mpg321->setFiles().
     */
    public function testSetFilesSingleFileWithoutArray()
    {
        $this->mpg321->setFiles('tests/mp3s/A.mp3');
        $this->assertInstanceOf(Mpg321::class, $this->mpg321);
    }

    /**
     * Tests Mpg321->getPlayModeOption().
     *
     * @dataProvider playModesProvider
     */
    public function testGetPlayModeOption($mode, $expected)
    {
        $this->mpg321->setPlayMode($mode);
        $this->assertEquals($expected, $this->mpg321->getPlayModeOption());
    }

    /**
     * Tests Mpg321->getsetFilesOption().
     *
     * @dataProvider filesProvider
     */
    public function testGetFilesOption($mode, $expected)
    {
        $this->mpg321->setFiles($mode);
        $this->assertEquals($expected, $this->mpg321->getFilesOption());
    }

    /**
     * Data provider for different playmodes.
     *
     * @return string[][]|NULL[][]
     */
    public function playModesProvider()
    {
        return [
            'normal mode' => ['normal', null],
            'shuffle mode' => ['shuffle', '--shuffle'],
            'random mode' => ['random', '--random'],
        ];
    }

    /**
     * Data provider for file loading.
     *
     * @return string[][]|string[][][]
     */
    public function filesProvider()
    {
        return [
            'single valid file without array' => ['tests/mp3s/A.mp3', '"tests/mp3s/A.mp3"'],
            'single valid file in an array' => [['tests/mp3s/A.mp3'], '"tests/mp3s/A.mp3"'],
            'three valid files' => [['tests/mp3s/A.mp3', 'tests/mp3s/B.mp3', 'tests/mp3s/C.mp3'], '"tests/mp3s/A.mp3" "tests/mp3s/B.mp3" "tests/mp3s/C.mp3"'],
            'folder' => [['tests/mp3s'], '"tests/mp3s/A.mp3" "tests/mp3s/B.mp3" "tests/mp3s/C.mp3" "tests/mp3s/D.mp3" "tests/mp3s/E.mp3" "tests/mp3s/F.mp3" "tests/mp3s/G.mp3"'],
        ];
    }
}
