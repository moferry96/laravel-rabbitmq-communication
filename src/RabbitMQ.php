<?php

namespace MHFereydouni\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    private AMQPStreamConnection $connection;

    private AMQPChannel $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            host: config('rabbitmq.host'),
            port: config('rabbitmq.port'),
            user: config('rabbitmq.user'),
            password: config('rabbitmq.password'),
            vhost: config('rabbitmq.vhost'),
            insist: config('rabbitmq.insist', false),
            login_method: config('rabbitmq.login_method', 'AMQPLAIN'),
            login_response: config('rabbitmq.login_response'),
            locale: config('rabbitmq.locale', 'en_US'),
            connection_timeout: config('rabbitmq.connection_timeout', 3.0),
            read_write_timeout: config('rabbitmq.read_write_timeout', 3.0),
            context: config('rabbitmq.context'),
            keepalive: config('rabbitmq.keepalive', false),
            heartbeat: config('rabbitmq.heartbeat', 0),
            channel_rpc_timeout: config('rabbitmq.channel_rpc_timeout', 0.0),
        );

        $this->channel = $this->connection->channel();
    }

    public function queue(): RabbitMQQueue
    {
        return new RabbitMQQueue($this->channel);
    }

    public function exchange(): RabbitMQExchange
    {
        return new RabbitMQExchange($this->connection);
    }

    public function message(): RabbitMQMessage
    {
        return new RabbitMQMessage($this->channel);
    }

    public function consume(): RabbitMQConsumer
    {
        return new RabbitMQConsumer($this->channel);
    }

    public function __destruct()
    {
        $this->connection->close();
        $this->channel->close();
    }
}
