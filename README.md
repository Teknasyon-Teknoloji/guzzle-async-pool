# Hakkında

Bu kütüphane [GuzzleHttp\Pool](http://docs.guzzlephp.org/en/stable/quickstart.html#concurrent-requests) sınıfının özellikle
Soap için kullanımını kolaylaştırmayı amaçlamaktadır.

# Demo

**example** klasöründe bulunan örnek kodları çalıştırmak için sırasıyla aşağıdaki komutları çalıştırabilirsiniz:
```bash
$ docker run -it --rm -v $(pwd):/app composer update
$ cd example
$ docker build -t testserver .
$ docker run -d -p 8080:80 -v $(pwd):/var/www/html testserver
$ php test.php
$ php test_guzzle_pool.php
```

# Kurulum

Kütüphaneyi projenizde kullanmak için composer.json dosyanıza aşağıdaki satırları ekleyin ve composer update komutunu çalıştırın:
```
"require": {
    "teknasyon/guzzle-async-pool": "1.0"
}
```

# Kullanım

Soap servisine yapılacak istekler kütüphane içerisindeki **Teknasyon\GuzzleAsyncPool\SoapRequestFactory** sınıfı ile oluşturulabilir.
Bu sınıfın factory metodu kullanılarak istek gönderimi için gerekli olan **GuzzleHttp\Psr7\Request** türünde bir obje üretilir.
Örnek kullanım:

```php
// Soap servisinin wsdl dosyası
$wsdl = 'http://127.0.0.1:8080/soap_server.php?wsdl';
// Soap servis adresi
$endpoint = 'http://127.0.0.1:8080/soap_server.php';
// İstekte bulunulacak SoapAction bilgisi
$soapAction = 'http://tempuri.org/Multiply';
// İstekte bulunulacak fonksiyon adı.
$functionName = 'Multiply';
// İstekte bulunulacak fonksiyon için parametreler.
$functionParams = ['intA' => 10, 'intB' => 3];
$request = SoapRequestFactory::factory(
    $wsdl,
    $endpoint,
    $soapAction,
    $functionName,
    $functionParams
);
```

Üretilen istek objeleri **Teknasyon\GuzzleAsyncPool\Pool** sınıfı vasıtasıyla gönderilir. Bu sınıfın **onCompletedRequest**
metodu ile başarılı istek cevaplarını, **onFailedRequest** isimli metodu ile hatalı istek cevapları dinlenir.

```
$requests = [
    SoapRequestFactory::factory(
        'http://127.0.0.1:8080/soap_server.php?wsdl',
        'http://127.0.0.1:8080/soap_server.php',
        'http://tempuri.org/Add',
        'Add',
        ['intA' => 10, 'intB' => 3]
    ),
    SoapRequestFactory::factory(
        'http://127.0.0.1:8080/soap_server.php?wsdl',
        'http://127.0.0.1:8080/soap_server.php',
        'http://tempuri.org/Subtract',
        'Subtract',
        ['intA' => 10, 'intB' => 3]
    ),
    SoapRequestFactory::factory(
        'http://127.0.0.1:8080/soap_server.php?wsdl',
        'http://127.0.0.1:8080/soap_server.php',
        'http://tempuri.org/Multiply',
        'Multiply',
        ['intA' => 10, 'intB' => 3]
    )
];

$guzzlePoolSettings = ['concurrency' => 5];
$guzzleClient = new Client();
$pool = new Teknasyon\GuzzleAsyncPool\Pool($requests, $guzzlePoolSettings, $guzzleClient);
$pool->onCompletedRequest(function ($index, RequestInterface $request, ResponseInterface $response) {
});
$pool->onFailedRequest(function ($index, RequestInterface $request, \Exception $exception) {
});
$pool->wait();
```

**onCompletedRequest** metodu ile tanımlayacağınız fonksiyon sırasıyla şu parametreleri alır:
* **$index:** İstek objesinin $requests dizisindeki indis değerini belirtir.
* **$request:** İstek objesi.
* **$response:** Yanıt objesi.

**onFailedRequest** metodu ile tanımlayacağınız fonksiyon sırasıyla şu parametreleri alır:
* **$index:** İstek objesinin $requests dizisindeki indis değerini belirtir.
* **$request:** İstek objesi.
* **$exception:** Hata objesi. Hata objesi **GuzzleHttp\Exception\RequestException** türünde ise **$exception->getResponse()** üzerinden Response objesine erişebilirsiniz.

# Soap istek ve yanıtlarının dönüştürülmesi

**Teknasyon\GuzzleAsyncPool\SoapRequestFactory** sınıfı belirtilen parametrelere göre otomatik olarak XML içeriğini hazırlar ve bu içeriği kullanarak Request objesini oluşturur. Bunun için özel bir işlem yapmanıza
gerek yok. Ancak soap yanıtları için **Teknasyon\GuzzleAsyncPool\Soap\Decoder** sınıfını kullanmalısınız. Bu sınıf aldığınız XML cevabını PHP dizisine dönüştürmektedir.
**onCompletedRequest** ya da **onFailedRequest** metodları içinde elde ettiğiniz **Psr\Http\Message\ResponseInterface** türündeki objeler vasıtasıyla cevabı dönüştürebilirsiniz.

```php
$pool->onCompletedRequest(function ($index, RequestInterface $request, ResponseInterface $response) use ($startTime) {
    $soapResponse = Decoder::decode($response->getBody()->getContents());
    ...
});
$pool->onFailedRequest(function ($index, RequestInterface $request, \Exception $exception) use ($startTime) {
    $soapResponse = null;
    if ($exception instanceof RequestException) {
        $soapResponse = Decoder::decode($exception->getResponse()->getBody()->getContents());
    }
    ...
});
```

# Guzzle Ayarları

**Teknasyon\GuzzleAsyncPool\Pool** sınıfı ikinci parametresi **GuzzleHttp\Pool** ayarlarını, üçüncü parametresi ise **GuzzleHttp\Client** objesini bekler.
Guzzle dökümanlarından değişiklik yapmak istediğiniz ayarları bu parametreler ile güncelleyebilirsiniz.
En sık kullanılacak olan ayar **concurrency** ayarı. Bu ayar ile aynı anda gönderilecek istek sayısını kısıtlarsınız.
Bu ayarı ikinci parametrede istenen dizide belirtmeniz gerekmekte.