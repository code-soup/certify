document.addEventListener("DOMContentLoaded",(()=>{const e=document.querySelector("#license-resend");e&&e.addEventListener("click",(e=>{e.preventDefault();const t=certify.post_id,n=new XMLHttpRequest,d=e.target;d.disabled=!0,n.open("POST",certify.ajax_url,!0),n.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),n.onreadystatechange=function(){4===n.readyState&&200===n.status?d.innerText="Sent!":4===n.readyState&&(d.disabled=!1)};const a=`action=resend_email_license&post_id=${t}&nonce=${certify.nonce}`;n.send(a)}))}));