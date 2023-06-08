<?php
namespace MyApp;

// require('vendor/autoload.php');
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Añadir la conexión al almacenamiento de clientes
        $this->clients->attach($conn);
        echo "Nueva conexion : {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId,
            $msg,
            $numRecv, $numRecv == 1 ? '' : 's'
        );

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Eliminar la conexión del almacenamiento de clientes
        $this->clients->detach($conn);
        echo "La conexion: {$conn->resourceId}ha sido desconectada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error en la conexión: {$e->getMessage()}\n";
        $conn->close();
    }
}



?>