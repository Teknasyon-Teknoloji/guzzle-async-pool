**example** klasöründe bulunan örnek kodları çalıştırmak için sırasıyla aşağıdaki komutları çalıştırabilirsiniz:
```bash
$ cd example
$ docker build -t testserver .
$ docker run -d -p 8080:80 -v $(pwd):/var/www/html testserver
$ php test.php
$ php test_guzzle_pool.php
```