<?php declare(strict_types = 1);

namespace App\UI\Modules\Pdf\Home;

use App\UI\Modules\Base\BasePresenter;
use Contributte\PdfResponse\PdfResponse;
use Nette\Bridges\ApplicationLatte\Template;

class HomePresenter extends BasePresenter
{

	/** @inject */
	public PdfResponse $pdfResponse;

	public function actionViewPdf(): void
	{
		$pdf = $this->createPdf();
		$pdf->setSaveMode(PdfResponse::INLINE);

		$this->sendResponse($pdf);
	}

	public function actionDownloadPdf(): void
	{
		$pdf = $this->createPdf();
		$pdf->setSaveMode(PdfResponse::DOWNLOAD);

		$this->sendResponse($pdf);
	}

	private function createPdf(): PdfResponse
	{
		/** @var Template $template */
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/../../../../../resources/pdf/example.latte');
		$template->title = 'Contributte PDF example';

		$this->pdfResponse->setTemplate($template->renderToString());
		$this->pdfResponse->documentTitle = 'Contributte PDF example'; // creates filename 2012-06-30-my-super-title.pdf
		$this->pdfResponse->pageFormat = 'A4-L'; // wide format
		$this->pdfResponse->getMPDF()->SetFooter('|Contributte PDF|'); // footer

		return $this->pdfResponse;
	}

}
