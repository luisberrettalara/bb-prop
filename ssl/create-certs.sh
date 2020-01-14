openssl req -config ssl.conf -new -x509 -sha256 -newkey rsa:2048 -nodes -keyout server.key.pem -days 365 -out server.cert.pem
