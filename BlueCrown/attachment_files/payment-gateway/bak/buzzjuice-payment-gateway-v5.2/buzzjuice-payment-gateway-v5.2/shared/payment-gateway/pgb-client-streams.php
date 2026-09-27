<?php
/**
 * Runs in WoWonder/Streams context. Never requires wp-load.php.
 */
function pgb_streams_client_log($message,$context=array()){
    $enabled=(bool)(int)(getenv('BZJ_PGB_STREAMS_LOGGING')?:0);
    if(!$enabled)return;
    $dir=__DIR__.'/log';if(!is_dir($dir))@mkdir($dir,0755,true);
    @file_put_contents($dir.'/pgb-client-streams.log',json_encode(array('time'=>gmdate('c'),'message'=>$message,'context'=>$context),JSON_UNESCAPED_SLASHES).PHP_EOL,FILE_APPEND|LOCK_EX);
}
function pgb_streams_client_secret(){
    $s=getenv('BZJ_PGB_SECRET');return $s?:'';
}
function pgb_streams_client_handle(){
    global $wo,$sqlConnect;
    header('Content-Type: application/json');
    if(empty($wo['loggedin'])||empty($wo['user']['user_id'])){http_response_code(401);echo json_encode(array('status'=>'error','message'=>'Please login.'));exit;}
    $post=function($k,$default=null){return isset($_POST[$k])?$_POST[$k]:$default;};
    $kind=strtoupper(trim((string)$post('transaction_kind','')));
    $allowed=array('PRODUCT','PRO','WALLET','DONATE','PURCHASE');
    if(!in_array($kind,$allowed,true)){http_response_code(400);echo json_encode(array('status'=>'error','message'=>'Invalid transaction kind.'));exit;}
    $wow_user=(int)$wo['user']['user_id'];$wow_order='wow_'.bin2hex(random_bytes(12));
    $wp_user=0;$stmt=$sqlConnect->prepare("SELECT wp_user_id FROM ".T_USERS." WHERE user_id=? LIMIT 1");$stmt->bind_param('i',$wow_user);$stmt->execute();$r=$stmt->get_result()->fetch_assoc();$stmt->close();$wp_user=(int)($r['wp_user_id']??0);
    if(!$wp_user){http_response_code(400);echo json_encode(array('status'=>'error','message'=>'WordPress user link is missing.'));exit;}
    $amount=(float)str_replace(',','',(string)$post('amount',0));$unit=(float)str_replace(',','',(string)$post('product_price',$amount));$qty=max(1,(int)$post('product_units',1));
    $currency=strtoupper(preg_replace('/[^A-Z]/','',(string)$post('wow_currency_code',$wo['config']['currency']??'GHS')));
    $product=(int)$post('product_id',0);if(!$product)$product=(int)$post('wow_market_id',0);
    $variation=0;
    if($kind==='PRO'){ $map=array(1=>(int)($wo['config']['wow_pro_package_id']??0),2=>(int)($wo['config']['wow_pro_package_id_2']??0),3=>(int)($wo['config']['wow_pro_package_id_3']??0),4=>(int)($wo['config']['wow_pro_package_id_4']??0));$type=(int)$post('wow_post_id',1);$product=$map[$type]??0;$variation=$product; }
    if($kind==='WALLET')$product=(int)($wo['config']['wow_wallet_topup_id']??0);
    if($kind==='DONATE')$product=(int)($wo['config']['wow_crowdfund_id']??0);
    if(!$product){http_response_code(400);echo json_encode(array('status'=>'error','message'=>'WooCommerce product mapping is missing.'));exit;}
    $site=rtrim((string)($wo['config']['wow_store_url']??''),'/');$endpoint=$site.'/wp-json/bzj-pgb/v1/order';if(!filter_var($endpoint,FILTER_VALIDATE_URL)){http_response_code(500);echo json_encode(array('status'=>'error','message'=>'Payment gateway endpoint is not configured.'));exit;}
    $request_id='pgb_'.bin2hex(random_bytes(16));
    $data=array('request_id'=>$request_id,'wow_order_id'=>$wow_order,'wp_user_id'=>$wp_user,'wow_user_id'=>$wow_user,'transaction_kind'=>$kind,'amount'=>round($amount,6),'unit_price'=>round($unit,6),'quantity'=>$qty,'currency'=>$currency,'product_id'=>$product,'variation_id'=>$variation,'wow_post_id'=>(int)$post('wow_post_id',0),'product_owner_id'=>(int)$post('product_owner_id',0),'address_id'=>(int)$post('address_id',0),'product_name'=>preg_replace('/[^\pL\pN\s\-_.#()]/u','',(string)$post('product_name','Buzzjuice Payment')));
    $body=json_encode($data);$ts=(string)time();$secret=pgb_streams_client_secret();if(!$secret){http_response_code(500);echo json_encode(array('status'=>'error','message'=>'Payment bridge secret is not configured.'));exit;}
    $sig=base64_encode(hash_hmac('sha256',$ts."\n".$request_id."\n".$body,$secret,true));
    $ch=curl_init($endpoint);
    curl_setopt_array($ch,array(CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$body,CURLOPT_TIMEOUT=>25,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_HTTPHEADER=>array('Content-Type: application/json','X-BZJ-PGB-Timestamp: '.$ts,'X-BZJ-PGB-Request-ID: '.$request_id,'X-BZJ-PGB-Signature: '.$sig),CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2));
    $raw=curl_exec($ch);$curl_error=curl_error($ch);$code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
    if($raw===false||$curl_error){pgb_streams_client_log('WordPress request failed',array('error'=>$curl_error));http_response_code(502);echo json_encode(array('status'=>'error','message'=>'Unable to contact payment service.'));exit;}
    $decoded=json_decode($raw,true);
    pgb_streams_client_log('Payment intent response',array('http_code'=>$code,'request_id'=>$request_id));
    if($code<200||$code>=300||empty($decoded['payment_url'])){http_response_code(502);echo json_encode(array('status'=>'error','message'=>$decoded['message']??'Payment service rejected the request.'));exit;}
    echo json_encode(array('status'=>200,'url'=>$decoded['payment_url'],'woo_order_id'=>$decoded['order_id'],'wow_order_id'=>$wow_order));exit;
}
