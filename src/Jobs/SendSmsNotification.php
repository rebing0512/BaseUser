<?php

namespace Jenson\BaseUser\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class SendSmsNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $raw = '';

    /**
     * Create a new job instance.
     *
     * @throws \Exception
     * @param mixed $mixed
     *
     */
    public function __construct($mixed)
    {
        $this->raw = $mixed;


    }

    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle(){
        try {
            $vars = $this->raw['vars'];

            if (!is_array($vars))
                throw new \Exception("Undefined Vars Or The Parameter Is Not An Array");

            $time = $vars['time'];
            $timeout = isset($time)?$time:10; //默认有效时间10分钟


            $templatesId = config('mbcore_baseuser.sms.templates.id');
            if (!$templatesId)
                throw new \Exception('SMS TemplateId Is Not Defined');

//            $template_id = $templatesId;
            $validVars = config('mbcore_baseuser.sms.templates.vars');

            foreach ($validVars as $val) {
                if (!array_key_exists($val,$vars))
                    throw new \Exception("Key {$val} Not Found In Given Vars Parameters");
            }

            $body = [
                'appid' => config('mbcore_baseuser.sms.key.appid'),
                'signature' => config('mbcore_baseuser.sms.key.appkey'),
                'project' => $templatesId,
                'to' => $this->raw['to'],
                'vars' => json_encode($vars)
            ];
            $client = new Client();
            $response = $client->post('https://api.mysubmail.com/message/xsend.json',[
                'form_params' => $body,
                'timeout'=>$timeout
            ]);
            $body = $response->getBody()->getContents();

            $this->delete();
        } catch(\Exception $e) {
            // 发生异常10秒后重试，异常超过10次不再重试
            if ($this->attempts()>100)
                $this->delete();
            else {
                \Log::warning("SmsQueueError:[{$e->getMessage()}][".$this->job->getRawBody()."]");
                $this->delay($this->attempts()*10);
            }
            \Log::error("SMS".$e->getMessage());
        }
    }
}