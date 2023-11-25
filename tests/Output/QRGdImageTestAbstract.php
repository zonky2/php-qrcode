<?php
/**
 * Class QRGdImageTestAbstract
 *
 * @created      24.12.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

namespace chillerlan\QRCodeTest\Output;

use chillerlan\QRCode\Data\QRMatrix;
use GdImage;

/**
 * Tests the QRGdImage output module
 */
abstract class QRGdImageTestAbstract extends QROutputTestAbstract{

	/**
	 * @inheritDoc
	 */
	protected function setUp():void{

		if(!extension_loaded('gd')){
			$this::markTestSkipped('ext-gd not loaded');
		}

		parent::setUp();
	}

	public static function moduleValueProvider():array{
		return [
			'valid: int'                     => [[123, 123, 123], true],
			'valid: w/invalid extra element' => [[123, 123, 123, 'abc'], true],
			'valid: numeric string'          => [['123', '123', '123'], true],
			'invalid: wrong type'            => ['foo', false],
			'invalid: array too short'       => [[1, 2], false],
			'invalid: contains non-number'   => [[1, 'b', 3], false],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function testSetModuleValues():void{

		$this->options->moduleValues = [
			// data
			QRMatrix::M_DATA_DARK => [0, 0, 0],
			QRMatrix::M_DATA      => [255, 255, 255],
		];

		$this->outputInterface = new $this->FQN($this->options, $this->matrix);
		$this->outputInterface->dump();

		$this::assertTrue(true); // tricking the code coverage
	}

	public function testOutputGetResource():void{
		$this->options->returnResource = true;
		$this->outputInterface         = new $this->FQN($this->options, $this->matrix);

		$this::assertInstanceOf(GdImage::class, $this->outputInterface->dump());
	}

	public function testBase64MimeType():void{
		$this->options->outputBase64 = true;
		$this->outputInterface       = new $this->FQN($this->options, $this->matrix);

		$this::assertStringContainsString($this->outputInterface::MIME_TYPE, $this->outputInterface->dump());
	}

}
