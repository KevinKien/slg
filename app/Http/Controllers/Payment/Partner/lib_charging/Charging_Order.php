<?php
Class charging_order {
  public $uid;
  public $request_id;
  public $serial;
  public $code;
  public $provider;
  public $session_id;
  public $status;
  public $message;
  public $amount;
  public $time_start;
  public $time_end;
  public $trans_id;
  
  public function insert_charging_order(){
      $id =  db_insert('charging_order')
           ->fields(array(
            'uid' => $this->uid,
            'request_id' => $this->request_id,
            'serial' => $this->serial,
            'code' => $this->code,
            'provider' => $this->provider,
            'session_id' => $this->session_id,
            'status' => $this->status,
            'message' => $this->message,
            'amount' => $this->amount,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'trans_id' => $this->trans_id,
           ))
           ->execute();      
    
  }
  
}