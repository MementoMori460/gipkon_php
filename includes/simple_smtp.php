<?php
class SimpleSMTP {
    private $host;
    private $port;
    private $username;
    private $password;
    private $secure;
    private $conn;
    private $debug = [];

    public function __construct($host, $port, $username, $password, $secure = 'tls') {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->secure = $secure;
    }

    public function send($to, $subject, $body, $fromEmail, $fromName = '') {
        $this->debug = [];
        try {
            $socket_protocol = '';
            if ($this->secure === 'ssl') $socket_protocol = 'ssl://';
            // TLS usually starts as TCP and then STARTTLS

            $conn = fsockopen($socket_protocol . $this->host, $this->port, $errno, $errstr, 15);
            if (!$conn) throw new Exception("Could not connect: $errstr");
            $this->conn = $conn;

            $this->read(); // Banner

            $this->cmd('EHLO ' . $_SERVER['SERVER_NAME']);

            if ($this->secure === 'tls') {
                $this->cmd('STARTTLS');
                stream_socket_enable_crypto($this->conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->cmd('EHLO ' . $_SERVER['SERVER_NAME']);
            }

            if (!empty($this->username)) {
                $this->cmd('AUTH LOGIN');
                $this->cmd(base64_encode($this->username));
                $this->cmd(base64_encode($this->password));
            }

            $this->cmd("MAIL FROM: <$fromEmail>");
            $this->cmd("RCPT TO: <$to>");
            $this->cmd("DATA");

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";
            $headers .= "Reply-To: $fromName <$fromEmail>\r\n";
            $headers .= "Subject: $subject\r\n";
            $headers .= "X-Mailer: PHP/SimpleSMTP\r\n";

            $this->cmd($headers . "\r\n" . $body . "\r\n.");
            $this->cmd("QUIT");

            fclose($this->conn);
            return true;
        } catch (Exception $e) {
            $this->debug[] = "Error: " . $e->getMessage();
            return false;
        }
    }

    private function cmd($cmd) {
        $this->debug[] = "> $cmd";
        fwrite($this->conn, $cmd . "\r\n");
        return $this->read();
    }

    private function read() {
        $str = '';
        while ($str = fgets($this->conn, 515)) {
            $this->debug[] = "< $str";
            if (substr($str, 3, 1) == ' ') break;
        }
        return $str;
    }

    public function getDebug() {
        return $this->debug;
    }
}
?>
