<?php declare(strict_types = 1);

namespace App\UI\Modules\Base;

use Nette\Application\BadRequestException;
use Nette\Application\Helpers;
use Nette\Application\Request;
use Nette\Application\Response as AppResponse;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Psr\Log\LogLevel;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

abstract class BaseErrorPresenter extends SecuredPresenter
{

	/**
	 * @return ForwardResponse|CallbackResponse
	 */
	public function run(Request $request): AppResponse
	{
		$e = $request->getParameter('exception');

		if ($e instanceof Throwable) {
			$code = $e->getCode();
			$level = ($code >= 400 && $code <= 499) ? LogLevel::WARNING : LogLevel::ERROR;

			Debugger::log(sprintf(
				'Code %s: %s in %s:%s',
				$code,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			), $level);

			Debugger::log($e, ILogger::EXCEPTION);
		}

		if ($e instanceof BadRequestException) {
			[$module, , $sep] = Helpers::splitName($request->getPresenterName());

			return new ForwardResponse($request->setPresenterName($module . $sep . 'Error4xx'));
		}

		return new CallbackResponse(function (IRequest $httpRequest, IResponse $httpResponse): void {
			$header = $httpResponse->getHeader('Content-Type');
			if ($header !== null && preg_match('#^text/html(?:;|$)#', $header) !== false) {
				require __DIR__ . '/templates/500.phtml';
			}
		});
	}

}
