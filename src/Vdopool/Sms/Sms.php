<?php namespace Vdopool\Sms;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Foundation\Application;
/**
 * 
 **/
class Sms
{

  protected $app;

  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  public function test()
  {
    echo $this->app['config']->get('sms::secret_key');
  }

  /**
   * 发短信
   *
   * @param array $data 发送短信需要的数据
   *
   * @return string
   * @author Me
   **/
  public function sendsms($data)
  {
    $sign = Helper::hmacSign('POST', $data, $this->app['config']->get('sms::secret_key'));
    $data['signature'] = $sign;

    $client = new \GuzzleHttp\Client;

    $request = $client->createRequest('POST', $this->app['config']->get('sms::sendsms_url'));
    $postBody = $request->getBody();

    // $postBody is an instance of GuzzleHttp\Post\PostBodyInterface
    foreach ($data as $k => $v) {
      $postBody->setField($k, $v);
    }
    try {
      // Send the POST request
      $response = $client->send($request);
      return $response->getBody();
    } catch (ClientException $exception) {
      $responseBody = $exception->getResponse()->getBody();
      return $responseBody;
    } catch (TransferException $exception) {
      $responseBody = $exception->getMessage();
      return $responseBody;
    }
  }


  /**
   * verify
   *
   * @param array $data 验证需要的数据
   *
   * @return string
   * @author Me
   **/
  public function verify($data)
  {
    $sign = Helper::hmacSign('POST', $data, $this->app['config']->get('sms::secret_key'));
    $data['signature'] = $sign;

    $client = new \GuzzleHttp\Client;

    $request = $client->createRequest('POST', $this->app['config']->get('sms::verify_url'));
    $postBody = $request->getBody();

    // $postBody is an instance of GuzzleHttp\Post\PostBodyInterface
    foreach ($data as $k => $v) {
      $postBody->setField($k, $v);
    }

    try {
      // Send the POST request
      $response = $client->send($request);
      return $response->getBody();
    } catch (ClientException $exception) {
      $responseBody = $exception->getResponse()->getBody();
      return $responseBody;
    } catch (TransferException $exception) {
      $responseBody = $exception->getMessage();
      return $responseBody;
    }
  }

}
