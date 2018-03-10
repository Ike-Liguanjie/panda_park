<?php
require_once('../mns_sdk/mns-autoloader.php');
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;
class PublishBatchSMSMessageDemo
{
    public function run()
    {
        session_start();
        /**
         * Step 1. 初始化Client
         */
        $this->endPoint = "http://1641194321471154.mns.cn-hangzhou.aliyuncs.com/"; // eg. http://1234567890123456.mns.cn-shenzhen.aliyuncs.com
        $this->accessId = "LTAIsmrDKfe9Di9N";
        $this->accessKey = "VGnbaiIKywiSjXBiyUwMblDvml4QUA";
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        /**
         * Step 2. 获取主题引用
         */
        $topicName = "sms.topic-cn-hangzhou";
        $topic = $this->client->getTopicRef($topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes("大象天堂", "SMS_69350059");
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
        $code = substr(str_shuffle('0123456789'),0,4);
        $_SESSION['code'] = $code;
        $_SESSION['code_get_time'] = time();
        $_SESSION['code_phone'] = $_POST['phone'];
        $batchSmsAttributes->addReceiver($_POST['phone'], array("code" => $code));
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try
        {
            $res = $topic->publishMessage($request);
            if ($res->isSucceed()){
                $reda = ['code'=>1,'msg'=>'获取成功,30分钟内输入有效'];
                echo json_encode($reda);
                return;
            }else{
                $reda = ['code'=>0,'msg'=>'获取失败,请稍后再试'];
                echo json_encode($reda);
                return;
            }
        }
        catch (MnsException $e)
        {
            $reda = ['code'=>0,'msg'=>$e];
            echo json_encode($reda);
            return;
        }
    }
}
$instance = new PublishBatchSMSMessageDemo();
$instance->run();