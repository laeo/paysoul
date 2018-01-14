<?php

namespace Tests\Providers\Alipay;

use Laeo\Paysoul\Providers\Alipay\FakeOpenSSL;
use Tests\TestCase;

class FakeOpenSSLTest extends TestCase
{
    protected $ssl;

    public function setUp()
    {
        $this->ssl = new FakeOpenSSL(
            'MIIEowIBAAKCAQEA6afxx3d8yINRpvcBrqf3qUTQofITzfs6bkL0yORfbo+rsur+d/itWhqtAfV+Cn+1OQgo+DlodVpy5l2eUD+A1kRmTemKyJ8eQIe4trhEERpJof4iZpZBU6CNIs1R2yS1BIg+lBGBGVlOZpUBlI3UoGFC9fAcJAVBnx0asXC6Ja1AumfHKGf1br1CNXGxxD6Sa8mW+wSDKwfn0RcgrdBcQxIezl0E20ED7aMQuo4da+oRDaQ+u8HJHM/CaIvKwpE6g9cQTAzR9Mwj5pixUjZXpIDhusfN2Yu0iimnLqlnkb8eWhPGw3eZIk0WUxKYqbxOhyfquJOWpF13DNI8fD4RiwIDAQABAoIBAB7vinZzXoUZxeTKTWG3gXXa05iteWvLOHhCyzAR9ISp5vzAWkK+HQ2R/JgGzdJMCE1txCbhSvBGsnHHsV9EmSVFVxo3spVPY+z9Szp9+R0ekuMsx5c3i5yr5CPYdV8DZCgeddKa4rHdfiDc16G4iY9WcUwop0EppP+RFeiqWSsFnXpr5Uju34hYtR1euhuXllv8zaDYCMKOOIOgmw5m+lie5SwVDpOJv/waLo9Ltu/IkuckOJBfkPq2CAm2yAy9aZZiupTgjqPs3Av3i+fI0XFt1dFbB6Ip8GtUzoVuRZUGkR7HBe1WtJg52CXxyRdjNWyhT3PhZzR4q2V4JMiGdXkCgYEA/2MNjNbkOAEQ1TWNCtshqc0pglRAf2gvUiHAA2FXbz3D2YZurWNnNqFHIuwTRZGzuvMJzR0mPIlKBkLnTqWpfhRxeLKA3n93p424Xpa7roL1yUWFYE3f1T7Ll/Yb6mxXM4/t+fEcqektFz2SYraYiTvy+Un4f2w9TAiir4gN6PUCgYEA6jeJcRThdUlRgzaY0IA1Ty+C2rAZQukGGzEXmmwCp5TzOsdKOZfhuRA5+woZD/uBo1KJsdSnXpoZ7/ZapSL4IVejKqBUDngWrVt0fhAaS9WX+y9mc6WlmbAsaOdlL95E5Hc9PAJHhfUbYgPFdDJTsIc5bNaymjAiS9Hlbk9JgH8CgYBmD+Dnay3Tj+F5Q6h1MTPX100Cb8dC45Edwq0o7Krzovx1Hzt2AabK91MlirD8+YiZau180mxofvldXTlxfdUptOPQN4423Twlcwa+joC88ktlv2nZHeYZI7pbpZhsbBXBXoDdhVVONgi2I/4vgwecuJ+WrtAnpEsu6riRa88bTQKBgQDZzlWo7EMRv+HmhJItatyoS8WDOrnzKCH+jXOmrgT19KUQJx4WWvCVGcrhci2GWFvhFrNnxRrz9ZhjN5Rp9xAKaaYZ0Mj6P6DPC7pUNQNPPE0+UIEU0JkkR9m0oGLP1gH4+A3gzTnYD+ysBFfNy7NZ+RZk6W9jxZlPKt0R7PesXwKBgEX9FlmrQC8Fejn09/FU8SrCO+Q3fe1owxHoOAqtCbI/oAdRQJl3NfBgRF+GsvkBR5vUI5QexyLXnwc12sUZguWFyaBZJd/KUkCIcr+t9aaNZ6NKNHf1wOoJIKaHdKZpq3hPY03tlwxY9isMFVDlxzXIdQKHq2QRjI2qCT/8/COq',
            'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6afxx3d8yINRpvcBrqf3qUTQofITzfs6bkL0yORfbo+rsur+d/itWhqtAfV+Cn+1OQgo+DlodVpy5l2eUD+A1kRmTemKyJ8eQIe4trhEERpJof4iZpZBU6CNIs1R2yS1BIg+lBGBGVlOZpUBlI3UoGFC9fAcJAVBnx0asXC6Ja1AumfHKGf1br1CNXGxxD6Sa8mW+wSDKwfn0RcgrdBcQxIezl0E20ED7aMQuo4da+oRDaQ+u8HJHM/CaIvKwpE6g9cQTAzR9Mwj5pixUjZXpIDhusfN2Yu0iimnLqlnkb8eWhPGw3eZIk0WUxKYqbxOhyfquJOWpF13DNI8fD4RiwIDAQAB'
        );
    }

    public function testVerifier()
    {
        $data = ['id' => 123456];
        $sign = $this->ssl->sign($data);
        $this->assertNotNull($sign);
        $this->assertTrue($this->ssl->verify($data, $sign));
    }
}
