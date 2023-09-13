<?php

namespace Functional\Connection;

use PhpAmqpLib\Exception\AMQPIOException;
use PhpAmqpLib\Tests\Functional\AbstractConnectionTest;

/**
 * @group connection
 * @requires OS Linux|Darwin
 */
class AmqpAmqpsConnectionTest extends AbstractConnectionTest
{
    /**
     * @test
     * @testWith ["amqp", 5671]
     *           ["amqps", 5671]
     *           ["amqp", 5672]
     *           ["amqps", 5671]
     */
    public function connection($protocol, $port)
    {
        $host = HOST;
        self::expectException(AMQPIOException::class);
        self::expectExceptionMessage(
            "stream_socket_client(): unable to connect to $protocol://{$host}:$port (Unable to find the socket transport \"$protocol\" - did you forget to enable it when you configured PHP?)"
        );
        $certsPath = realpath(__DIR__ . '/../../certs');

        $options = [
            'protocol' => $protocol,
            'ssl' => [
                'cafile' => $certsPath . '/ca_certificate.pem',
                'local_cert' => $certsPath . '/client_certificate.pem',
                'local_pk' => $certsPath . '/client_key.pem',
                'verify_peer' => true,
                'verify_peer_name' => false,
            ],
        ];
        $this->conection_create('ssl', $host, $port, $options);
    }
}
