<?php declare(strict_types = 1);

namespace App\UI\Modules\Mailing\Home;

use Contributte\Mailing\IMailBuilderFactory;
use Nette\Application\UI\Presenter;

class HomePresenter extends Presenter
{

	private IMailBuilderFactory $mailBuilderFactory;

	public function __construct(IMailBuilderFactory $mailBuilderFactory)
	{
		parent::__construct();

		$this->mailBuilderFactory = $mailBuilderFactory;
	}

	public function actionDefault(): void
	{
		$mail = $this->mailBuilderFactory->create();
		$mail->setSubject('Example');
		$mail->addTo('foo@example.com');

		$mail->setTemplateFile(__DIR__ . '/templates/Emails/email.latte');
		$mail->setParameters([
			'title' => 'Title',
			'content' => 'Lorem ipsum dolor sit amet',
		]);

		$mail->send();
	}

}
