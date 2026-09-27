window.BZJ_PGB=window.BZJ_PGB||{};
BZJ_PGB.request=function(payload,done){
  payload.payment_type='wow_payment';
  $.ajax({url:(typeof site_url!=='undefined'?site_url:'')+'/requests.php?f=payment',type:'POST',data:payload,dataType:'json'})
   .done(function(r){if(r&&r.url){window.location.href=r.url;}else if(done){done(r);}})
   .fail(function(xhr){var r=xhr.responseJSON||{};if(done)done(r);});
};
BZJ_PGB.redirectToPayment=function(url){if(url)window.location.href=url;};
