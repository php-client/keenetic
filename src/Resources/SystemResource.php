<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Resources;

use PhpClient\Keenetic\Requests\More\GetDefaultConfigRequest;
use PhpClient\Keenetic\Requests\More\GetLogRequest;
use PhpClient\Keenetic\Requests\More\GetRunningConfigRequest;
use PhpClient\Keenetic\Requests\More\GetStartupConfigRequest;
use Saloon\Exceptions\SaloonException;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class SystemResource extends BaseResource
{
    /**
     * @throws SaloonException
     */
    public function getDefaultConfig(): Response
    {
        return $this->connector->send(
            request: new GetDefaultConfigRequest(),
        );
    }

    /**
     * @throws SaloonException
     */
    public function getRunningConfig(): Response
    {
        return $this->connector->send(
            request: new GetRunningConfigRequest(),
        );
    }

    /**
     * @throws SaloonException
     */
    public function getStartupConfig(): Response
    {
        return $this->connector->send(
            request: new GetStartupConfigRequest(),
        );
    }

    /**
     * @throws SaloonException
     */
    public function getLog(): Response
    {
        return $this->connector->send(
            request: new GetLogRequest(),
        );
    }
}
