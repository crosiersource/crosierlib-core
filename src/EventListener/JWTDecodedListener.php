<?php

namespace CrosierSource\CrosierLibCoreBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTDecodedListener
{
	public function __construct(private RequestStack $requestStack)
	{
	}

	public function onJWTDecoded(JWTDecodedEvent $event): void
	{
		$request = $this->requestStack->getCurrentRequest();

		$payload = $event->getPayload();

//		if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
//			$event->markAsInvalid();
//		}
	}


}

