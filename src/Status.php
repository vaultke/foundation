<?php
namespace vaultke\foundation;
use Yii;
trait Status{
    public  function loadStatus($code){
        $codes = array_merge(self::appCodes(),self::coreCodes());
        $result = $codes[$code] ? $codes[$code] : [$code,'unknown', 'unknown'];
        return ['code'=>$result[0],'label'=>$result[1],'theme'=>$result[2]];
      }
      protected function appCodes() {
        $codes = [
          0   => ['0','No', 'danger'],
          1   => ['1','Yes', 'success'],
          2   => ['2','Partial', 'warning'],
          3   => ['3','Cancelled', 'danger'],
          4   => ['4','Declined', 'warning'],
          5   => ['5','Approved', 'success'],
          6   => ['6','Pending', 'info'],
          7   => ['7','Archived', 'warning'],
          8   => ['8','Deleted', 'danger'],
          9   => ['9','Inactive', 'warning'],
          10  => ['10','Active', 'success'],

          11  => ['11','Due', 'info'],
          12  => ['12','Overdue', 'danger'],
          13  => ['13','Paid', 'success'],
          14  => ['14','Contract', 'info'],
          15  => ['15','Permanent', 'success'],
          16  => ['16','Terminated', 'danger'],
          17  => ['17','Suspended', 'warning'],
          18  => ['18','In Dispute', 'warning'],
          19  => ['19','Unpaid', 'warning'],
          20  => ['20','On Leave', 'info'],
        ];
        return $codes;
      }
      protected function coreCodes() {
          $codes = [
                      100 => ['100','Continue', 'info'],
                      101 => ['101','Switching Protocols', 'info'],
                      103 => ['103','Early Hints', 'info'],
                      200 => ['200','OK', 'success'],
                      201 => ['201','Created', 'success'],
                      202 => ['202','Accepted', 'success'],
                      203 => ['203','Non-Authoritative Information', 'success'],
                      204 => ['204','No Content', 'info'],
                      205 => ['205','Reset Content', 'success'],
                      206 => ['206','Partial Content', 'info'],
                      207 => ['207','Multi-Status', 'success'],
                      226 => ['226','IM Used', 'success'],
                      300 => ['300','Multiple Choices', 'danger'],
                      301 => ['301','Moved Permanently', 'danger'],
                      302 => ['302','Found', 'danger'],
                      303 => ['303','See Other', 'danger'],
                      304 => ['304','Not Modified', 'danger'],
                      305 => ['305','Use Proxy', 'danger'],
                      306 => ['306','Reserved', 'danger'],
                      307 => ['307','Temporary Redirect', 'danger'],
                      400 => ['400','Bad Request', 'danger'],
                      401 => ['401','Unauthorized', 'danger'],
                      402 => ['402','Payment Required', 'danger'],
                      403 => ['403','Forbidden', 'danger'],
                      404 => ['404','Not Found', 'danger'],
                      405 => ['405','Method Not Allowed', 'danger'],
                      406 => ['406','Not Acceptable', 'danger'],
                      407 => ['407','Proxy Authentication Required', 'danger'],
                      408 => ['408','Request Timeout', 'danger'],
                      409 => ['409','Conflict', 'danger'],
                      410 => ['410','Gone', 'danger'],
                      411 => ['411','Length Required', 'danger'],
                      412 => ['412','Precondition Failed', 'danger'],
                      413 => ['413','Request Entity Too Large', 'danger'],
                      414 => ['414','Request-URI Too Long', 'danger'],
                      415 => ['415','Unsupported Media Type', 'danger'],
                      416 => ['416','Requested Range Not Satisfiable', 'danger'],
                      417 => ['417','Expectation Failed', 'danger'],
                      422 => ['422','Unprocessable Entity', 'danger'],
                      423 => ['423','Locked', 'danger'],
                      424 => ['424','Failed Dependency', 'danger'],
                      426 => ['426','Upgrade Required', 'danger'],
                      500 => ['500','Internal Server Error', 'danger'],
                      501 => ['501','Not Implemented', 'danger'],
                      502 => ['502','Bad Gateway', 'danger'],
                      503 => ['503','Service Unavailable', 'danger'],
                      504 => ['504','Gateway Timeout', 'danger'],
                      505 => ['505','HTTP Version Not Supported', 'danger'],
                      506 => ['506','Variant Also Negotiates', 'danger'],
                      507 => ['507','Insufficient Storage', 'danger'],
                      510 => ['510','Not Extended', 'danger']
          ];
            return $codes;
      }

}
