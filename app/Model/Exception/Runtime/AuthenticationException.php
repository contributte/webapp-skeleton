<?php declare(strict_types = 1);

namespace App\Model\Exception\Runtime;

use Nette\Security\AuthenticationException as NetteAuthenticationException;

final class AuthenticationException extends NetteAuthenticationException
{

}
